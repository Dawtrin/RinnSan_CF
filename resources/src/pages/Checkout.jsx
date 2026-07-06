import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { 
  ArrowLeft, ArrowRight, MapPin, User, Phone, FileText, 
  Banknote, Loader2, ShieldCheck, QrCode, ShoppingBag, Ticket, X 
} from 'lucide-react';
import { apiUrl } from '../config/api.js';

const Checkout = () => {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [cartData, setCartData] = useState(null);
  
  const [couponCode, setCouponCode] = useState('');
  const [couponLoading, setCouponLoading] = useState(false);
  const [appliedCoupon, setAppliedCoupon] = useState(null);
  const [couponError, setCouponError] = useState('');

  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    address: '',
    note: '',
    paymentMethod: 'cash'
  });

  useEffect(() => {
    const fetchCart = async () => {
      try {
        const res = await fetch(apiUrl('/api/cart'), {
          credentials: 'include',
          headers: { 'Accept': 'application/json' }
        });
        const json = await res.json();
        
        if (json.data && json.data.items && json.data.items.length > 0) {
          setCartData(json.data);
        } else {
          navigate('/menu');
        }
      } catch (error) {
        console.error("Lỗi tải giỏ hàng:", error);
      } finally {
        setLoading(false);
      }
    };
    fetchCart();
  }, [navigate]);

  const handleApplyCoupon = async () => {
    if (!couponCode.trim()) return;
    setCouponLoading(true);
    setCouponError('');
    setAppliedCoupon(null);

    const subtotal = Number(cartData?.summary?.subtotal || 0);

    try {
      const res = await fetch(apiUrl('/api/coupons/validate'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            code: couponCode, 
            order_amount: subtotal 
        })
      });
      const data = await res.json();

      if (data.success) {
        setAppliedCoupon({
            code: data.data.coupon.code,
            discount_amount: data.data.discount_amount,
            discount_type: data.data.coupon.discount_type,
            discount_value: data.data.coupon.discount_value
        });
      } else {
        setCouponError(data.message || 'Mã giảm giá không hợp lệ');
      }
    } catch (err) {
      setCouponError('Lỗi kết nối kiểm tra mã');
    } finally {
      setCouponLoading(false);
    }
  };

  const removeCoupon = () => {
    setAppliedCoupon(null);
    setCouponCode('');
    setCouponError('');
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handlePaymentSelect = (method) => {
    setFormData(prev => ({ ...prev, paymentMethod: method }));
  };

  const SHIPPING_FEE = 15000;
  const subtotal = Number(cartData?.summary?.subtotal || 0);
  const discountAmount = Number(appliedCoupon?.discount_amount || 0);
  const total = Math.max(0, subtotal + SHIPPING_FEE - discountAmount);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);

    const payload = {
      items: cartData.items,
      customer_name: formData.name,
      customer_phone: formData.phone,
      customer_email: "", 
      shipping_address: formData.address,
      note: formData.note,
      payment_method: formData.paymentMethod,
      shipping_fee: SHIPPING_FEE, 
      discount_amount: discountAmount,
      coupon_code: appliedCoupon ? appliedCoupon.code : null 
    };

    try {
      const res = await fetch(apiUrl('/api/orders'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const result = await res.json();

     if (res.ok && result.success) {
        // [FIX 1] Đổi POST thành DELETE để khớp với Backend (sửa lỗi 404)
        await fetch(apiUrl('/api/cart/clear'), { 
            method: 'DELETE', 
            credentials: 'include' 
        }).catch(err => console.warn("Lỗi xóa giỏ:", err));
        
        // [FIX 2] Sửa lỗi NaN: Kiểm tra kỹ xem API có trả về tiền không
        // Nếu không có (undefined), dùng ngay biến 'total' đã tính ở Frontend làm dự phòng
        const finalTotal = (result.data && result.data.total_amount !== undefined)
            ? Number(result.data.total_amount) 
            : total; 

        navigate('/order-success', { 
          state: { 
            order: {
              ...result.data,
              total_amount: finalTotal, // <--- Quan trọng: Gán giá trị đã fix vào đây
              payment_method: formData.paymentMethod,
              customer_name: formData.name,
              customer_phone: formData.phone
            }
          } 
        });
      } else {
        alert("Lỗi đặt hàng: " + (result.message || "Vui lòng thử lại"));
      }
    } catch (error) {
      console.error("Lỗi gửi đơn:", error);
      alert("Không thể kết nối đến máy chủ.");
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) return (
    <div className="h-screen flex flex-col items-center justify-center bg-[#FDFBF9]">
      <Loader2 className="w-12 h-12 animate-spin text-slate-800 mb-4" />
      <span className="text-slate-500 font-serif italic">Đang tải dữ liệu...</span>
    </div>
  );

  const items = cartData?.items || [];

  return (
    <div className="min-h-screen bg-white font-sans flex flex-col lg:flex-row text-slate-800">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap');
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Outfit', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
      `}</style>

      <div className="flex-1 bg-[#FDFBF9] px-6 md:px-16 py-10 overflow-y-auto custom-scrollbar">
        <div className="flex items-center justify-between mb-10">
            <Link to="/cart" className="flex items-center gap-2 text-slate-500 hover:text-slate-900 transition-colors group">
                <div className="w-9 h-9 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-slate-400 transition-all shadow-sm">
                    <ArrowLeft className="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" />
                </div>
                <span className="text-sm font-bold uppercase tracking-wide">Quay lại</span>
            </Link>
            <div className="hidden sm:flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-300">
                <span>01. Cart</span>
                <span>⎯⎯</span>
                <span className="text-slate-900 border-b-2 border-amber-500 pb-1">02. Checkout</span>
                <span>⎯⎯</span>
                <span>03. Done</span>
            </div>
        </div>

        <div className="max-w-xl mx-auto">
            <h1 className="text-3xl font-serif font-bold text-slate-900 mb-2">Thông tin giao hàng</h1>
            <p className="text-slate-500 text-sm mb-8">Hoàn tất đơn hàng của bạn.</p>

            <form onSubmit={handleSubmit} className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div className="space-y-1.5">
                        <label className="text-xs font-bold uppercase tracking-wider text-slate-500 ml-1">Họ tên</label>
                        <div className="relative group">
                            <User className="w-4 h-4 text-slate-400 absolute left-3 top-3.5 group-focus-within:text-amber-500 transition-colors" />
                            <input type="text" name="name" required placeholder="Nguyễn Văn A" value={formData.name} onChange={handleInputChange} className="w-full bg-white border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-50 transition-all shadow-sm" />
                        </div>
                    </div>
                    <div className="space-y-1.5">
                        <label className="text-xs font-bold uppercase tracking-wider text-slate-500 ml-1">Số điện thoại</label>
                        <div className="relative group">
                            <Phone className="w-4 h-4 text-slate-400 absolute left-3 top-3.5 group-focus-within:text-amber-500 transition-colors" />
                            <input type="tel" name="phone" required placeholder="0905..." value={formData.phone} onChange={handleInputChange} className="w-full bg-white border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-50 transition-all shadow-sm" />
                        </div>
                    </div>
                </div>

                <div className="space-y-1.5">
                    <label className="text-xs font-bold uppercase tracking-wider text-slate-500 ml-1">Địa chỉ</label>
                    <div className="relative group">
                        <MapPin className="w-4 h-4 text-slate-400 absolute left-3 top-3.5 group-focus-within:text-amber-500 transition-colors" />
                        <input type="text" name="address" required placeholder="Số nhà, đường, phường..." value={formData.address} onChange={handleInputChange} className="w-full bg-white border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-50 transition-all shadow-sm" />
                    </div>
                </div>

                <div className="space-y-1.5">
                    <label className="text-xs font-bold uppercase tracking-wider text-slate-500 ml-1">Ghi chú</label>
                    <div className="relative group">
                        <FileText className="w-4 h-4 text-slate-400 absolute left-3 top-3.5 group-focus-within:text-amber-500 transition-colors" />
                        <textarea name="note" placeholder="Lời nhắn cho quán..." value={formData.note} onChange={handleInputChange} className="w-full bg-white border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-50 transition-all shadow-sm resize-none h-20" />
                    </div>
                </div>

                <div className="pt-4">
                    <h3 className="text-lg font-bold text-slate-900 mb-4 font-serif border-t border-slate-200 pt-6">Thanh toán</h3>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div onClick={() => handlePaymentSelect('cash')} className={`cursor-pointer p-4 rounded-2xl border transition-all flex items-center gap-4 ${formData.paymentMethod === 'cash' ? 'border-amber-500 bg-[#FFFDF5] ring-1 ring-amber-500 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300'}`}>
                            <div className={`w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 ${formData.paymentMethod === 'cash' ? 'border-amber-500' : 'border-slate-300'}`}>
                                {formData.paymentMethod === 'cash' && <div className="w-2.5 h-2.5 bg-amber-500 rounded-full"></div>}
                            </div>
                            <div>
                                <div className="flex items-center gap-2 mb-0.5">
                                    <Banknote className="w-4 h-4 text-slate-500" />
                                    <span className="font-bold text-sm text-slate-900">Tiền mặt (COD)</span>
                                </div>
                            </div>
                        </div>

                        <div onClick={() => handlePaymentSelect('banking')} className={`cursor-pointer p-4 rounded-2xl border transition-all flex items-center gap-4 ${formData.paymentMethod === 'banking' ? 'border-amber-500 bg-[#FFFDF5] ring-1 ring-amber-500 shadow-md' : 'border-slate-200 bg-white hover:border-slate-300'}`}>
                            <div className={`w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 ${formData.paymentMethod === 'banking' ? 'border-amber-500' : 'border-slate-300'}`}>
                                {formData.paymentMethod === 'banking' && <div className="w-2.5 h-2.5 bg-amber-500 rounded-full"></div>}
                            </div>
                            <div>
                                <div className="flex items-center gap-2 mb-0.5">
                                    <QrCode className="w-4 h-4 text-blue-600" />
                                    <span className="font-bold text-sm text-slate-900">Chuyển khoản QR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" disabled={submitting} className="lg:hidden w-full py-4 bg-slate-900 text-white rounded-xl font-bold hover:bg-amber-600 transition-all shadow-xl flex items-center justify-center gap-2 disabled:opacity-70">
                    {submitting ? <Loader2 className="w-5 h-5 animate-spin" /> : <span>Đặt hàng ({total.toLocaleString()}đ)</span>}
                </button>
            </form>
        </div>
      </div>

      <div className="hidden lg:flex w-[35%] bg-white border-l border-slate-100 p-10 flex-col h-screen sticky top-0">
        <div className="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100 shadow-sm">
            <h3 className="text-lg font-serif font-bold text-slate-900 mb-6 flex items-center gap-2">
                <ShoppingBag className="w-5 h-5 text-amber-600" /> Đơn hàng
            </h3>
            <div className="space-y-4 max-h-[30vh] overflow-y-auto custom-scrollbar pr-2 mb-6">
                {items.map((item) => (
                    <div key={item.key} className="flex gap-4">
                        <div className="w-14 h-14 rounded-lg bg-white border border-slate-200 overflow-hidden shrink-0 relative">
                            <span className="absolute top-0 right-0 bg-slate-900 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-bl-lg z-10">{item.quantity}</span>
                            <img src={item.image || 'https://via.placeholder.com/100'} className="w-full h-full object-cover" alt={item.name} />
                        </div>
                        <div className="flex-1 min-w-0">
                            <h4 className="text-sm font-bold text-slate-800 line-clamp-1">{item.name}</h4>
                            <p className="text-xs text-slate-500 line-clamp-1">{item.options && Object.values(item.options).join(', ')}</p>
                            <span className="text-sm font-bold text-slate-900">{(item.price * item.quantity).toLocaleString()}đ</span>
                        </div>
                    </div>
                ))}
            </div>
            
            <div className="py-4 border-t border-slate-200">
                {!appliedCoupon ? (
                    <div className="space-y-2">
                        <label className="text-xs font-bold text-slate-400 uppercase tracking-wider">Mã giảm giá</label>
                        <div className="flex gap-2">
                            <div className="relative flex-1">
                                <Ticket className="w-4 h-4 text-slate-400 absolute left-3 top-3" />
                                <input 
                                    type="text" 
                                    placeholder="Nhập mã voucher" 
                                    className="w-full bg-white border border-slate-200 rounded-xl py-2.5 pl-9 pr-3 text-sm focus:outline-none focus:border-amber-400 uppercase font-bold text-slate-700 placeholder:normal-case placeholder:font-normal"
                                    value={couponCode}
                                    onChange={(e) => setCouponCode(e.target.value.toUpperCase())}
                                />
                            </div>
                            <button 
                                onClick={handleApplyCoupon}
                                disabled={couponLoading || !couponCode}
                                className="px-4 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {couponLoading ? <Loader2 className="w-4 h-4 animate-spin"/> : 'Áp dụng'}
                            </button>
                        </div>
                        {couponError && <p className="text-xs text-red-500 font-medium pl-1">{couponError}</p>}
                    </div>
                ) : (
                    <div className="bg-green-50 border border-green-200 rounded-xl p-3 flex justify-between items-center">
                        <div className="flex items-center gap-2">
                            <Ticket className="w-4 h-4 text-green-600" />
                            <div>
                                <p className="text-sm font-bold text-green-700">{appliedCoupon.code}</p>
                                <p className="text-[10px] text-green-600">Đã giảm {appliedCoupon.discount_amount.toLocaleString()}đ</p>
                            </div>
                        </div>
                        <button onClick={removeCoupon} className="p-1 hover:bg-green-100 rounded-full text-green-600 transition-colors">
                            <X className="w-4 h-4" />
                        </button>
                    </div>
                )}
            </div>

            <div className="space-y-3 pt-4 border-t border-slate-200">
                <div className="flex justify-between text-sm text-slate-500"><span>Tạm tính</span><span className="font-medium text-slate-900">{subtotal.toLocaleString()}đ</span></div>
                <div className="flex justify-between text-sm text-slate-500"><span>Vận chuyển</span><span className="font-medium text-slate-900">{SHIPPING_FEE.toLocaleString()}đ</span></div>
                
                {appliedCoupon && (
                    <div className="flex justify-between text-sm text-green-600 font-bold">
                        <span>Giảm giá ({appliedCoupon.code})</span>
                        <span>-{discountAmount.toLocaleString()}đ</span>
                    </div>
                )}

                <div className="flex justify-between items-end pt-4 border-t border-dashed border-slate-300">
                    <span className="text-base font-bold text-slate-900">Tổng cộng</span>
                    <div className="text-right">
                        <span className="text-2xl font-serif font-bold text-slate-900">{total.toLocaleString()}</span>
                        <span className="text-xs font-bold text-slate-400 ml-1">VND</span>
                    </div>
                </div>
            </div>
        </div>
        <div className="flex items-center justify-center gap-2 text-xs text-slate-400 mb-8 bg-slate-50 py-2.5 rounded-lg border border-slate-100">
            <ShieldCheck className="w-4 h-4 text-green-500" /><span>Bảo mật tuyệt đối</span>
        </div>
        <button onClick={handleSubmit} disabled={submitting} className="w-full py-4 bg-slate-900 text-white rounded-xl font-bold uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl flex items-center justify-center gap-3 disabled:opacity-70 mt-auto">
            {submitting ? <Loader2 className="w-5 h-5 animate-spin" /> : <><span>Đặt hàng ngay</span><ArrowRight className="w-5 h-5" /></>}
        </button>
      </div>
    </div>
  );
};

export default Checkout;