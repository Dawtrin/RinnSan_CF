import React, { useEffect, useState } from 'react';
import { Link, useLocation, Navigate } from 'react-router-dom';
import { Check, ArrowRight, Home, ShoppingBag, Coffee, Copy, Download, Share2, CheckCircle, Truck } from 'lucide-react';

const OrderSuccessPage = () => {
  const location = useLocation();
  const order = location.state?.order;
  const [copied, setCopied] = useState(false);

  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  // --- CẤU HÌNH TÀI KHOẢN NGÂN HÀNG ---
  const BANK_INFO = {
    BANK_ID: 'MB',
    ACCOUNT_NO: '0396704484', 
    TEMPLATE: 'compact2',
    ACCOUNT_NAME: 'Tran Viet Dat'
  };

  // Tạo link VietQR
  const generateQR = () => {
    if (!order) return '';
    const amount = order.total_amount;
    const content = encodeURIComponent(`THANH TOAN DON ${order.order_code}`);
    return `https://img.vietqr.io/image/${BANK_INFO.BANK_ID}-${BANK_INFO.ACCOUNT_NO}-${BANK_INFO.TEMPLATE}.png?amount=${amount}&addInfo=${content}&accountName=${BANK_INFO.ACCOUNT_NAME}`;
  };

  const handleCopy = (text) => {
    navigator.clipboard.writeText(text);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  const handleDownloadQR = () => {
    const link = document.createElement('a');
    link.href = generateQR();
    link.download = `QR-${order.order_code}.png`;
    link.click();
  };

  if (!order) {
    return <Navigate to="/menu" replace />;
  }

  const isBanking = order.payment_method === 'banking';

  return (
    <div className="min-h-screen bg-[#F8F9FA] flex items-center justify-center p-4 md:p-8 font-sans text-slate-800">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap');
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Outfit', sans-serif; }
        .animate-scale-up { animation: scale-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes scale-up { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        @keyframes bounce-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .animate-bounce-slow { animation: bounce-slow 3s ease-in-out infinite; }
      `}</style>

      <div className="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-2 bg-white rounded-3xl shadow-2xl overflow-hidden animate-scale-up border border-slate-100">
        
        {/* CỘT TRÁI: THÔNG BÁO & CHI TIẾT */}
        <div className="p-8 md:p-12 flex flex-col justify-center bg-white order-2 lg:order-1 relative">
            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <Check className="w-8 h-8 text-green-600 stroke-[3]" />
            </div>
            
            <h1 className="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-3 leading-tight">
                Đặt hàng thành công!
            </h1>
            <p className="text-slate-500 mb-8 leading-relaxed">
                Cảm ơn bạn đã lựa chọn RinnSan Cafe. <br/>
                Mã đơn hàng của bạn là <span className="font-bold text-slate-900 font-mono text-lg">#{order.order_code}</span>
            </p>

            {/* Thông tin đơn hàng tóm tắt */}
            <div className="bg-slate-50 rounded-2xl p-5 mb-8 border border-slate-100">
                <div className="flex justify-between items-center mb-4 pb-4 border-b border-slate-200 border-dashed">
                    <span className="text-sm font-bold text-slate-500 uppercase tracking-wider">Tổng thanh toán</span>
                    <span className="text-2xl font-serif font-bold text-amber-600">{Number(order.total_amount).toLocaleString()}đ</span>
                </div>
                <div className="space-y-3 text-sm">
                    <div className="flex justify-between">
                        <span className="text-slate-500">Khách hàng</span>
                        <span className="font-medium text-slate-900">{order.customer_name}</span>
                    </div>
                    <div className="flex justify-between">
                        <span className="text-slate-500">Số điện thoại</span>
                        <span className="font-medium text-slate-900">{order.customer_phone}</span>
                    </div>
                    <div className="flex justify-between">
                        <span className="text-slate-500">Phương thức</span>
                        <span className="font-bold text-slate-900 uppercase">
                          {order.payment_method === 'banking' ? 'Chuyển khoản' : 'Tiền mặt (COD)'}
                        </span>
                    </div>
                </div>
            </div>

            <div className="flex gap-3">
                <Link to="/menu" className="flex-1 py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm uppercase tracking-widest hover:bg-amber-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-slate-200">
                    <ShoppingBag className="w-4 h-4" /> Đặt món thêm
                </Link>
                <Link to="/" className="w-14 flex items-center justify-center bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-all">
                    <Home className="w-5 h-5" />
                </Link>
            </div>
        </div>

        {/* CỘT PHẢI: QR CODE (NẾU BANKING) HOẶC BIỂU TƯỢNG COD */}
        <div className="bg-gradient-to-br from-slate-50 to-blue-50 p-8 md:p-12 flex flex-col items-center justify-center order-1 lg:order-2 border-b lg:border-b-0 lg:border-l border-slate-100">
            {isBanking ? (
                <div className="text-center w-full max-w-sm">
                    <h2 className="text-lg font-bold text-slate-900 mb-6 flex items-center justify-center gap-2">
                        <Coffee className="w-5 h-5 text-blue-600" /> Quét mã để thanh toán
                    </h2>
                    
                    <div className="bg-white p-4 rounded-2xl shadow-xl border border-slate-200 mb-6 relative group">
                        <img 
                            src={generateQR()} 
                            alt="VietQR" 
                            className="w-full h-auto rounded-xl"
                            onError={(e) => {
                              e.target.onerror = null;
                              e.target.src = 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300"><rect fill="%23f0f0f0" width="300" height="300"/><text x="50%" y="50%" text-anchor="middle" fill="%23999" font-size="16">QR Code Error</text></svg>';
                            }}
                        />
                        <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center gap-4 backdrop-blur-sm">
                            <button 
                              onClick={handleDownloadQR}
                              className="p-3 bg-white rounded-full hover:scale-110 transition-transform"
                            >
                              <Download className="w-5 h-5 text-slate-900"/>
                            </button>
                        </div>
                    </div>

                    <div className="bg-white border-2 border-slate-200 rounded-xl p-4 mb-4">
                        <div className="text-xs text-slate-500 font-bold uppercase mb-2">Thông tin chuyển khoản</div>
                        <div className="space-y-2 text-sm">
                            <div className="flex justify-between">
                                <span className="text-slate-600">Ngân hàng:</span>
                                <span className="font-bold">MB Bank</span>
                            </div>
                            <div className="flex justify-between">
                                <span className="text-slate-600">STK:</span>
                                <span className="font-bold font-mono">{BANK_INFO.ACCOUNT_NO}</span>
                            </div>
                            <div className="flex justify-between">
                                <span className="text-slate-600">Chủ TK:</span>
                                <span className="font-bold">Trần Viết Đạt</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-blue-50 border border-blue-200 rounded-xl p-4 text-left">
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-xs text-blue-600 font-bold uppercase">Nội dung CK</span>
                            <button 
                                onClick={() => handleCopy(`THANH TOAN DON ${order.order_code}`)}
                                className="text-[10px] bg-white px-2 py-1 rounded border border-blue-300 text-blue-600 font-bold hover:bg-blue-100 flex items-center gap-1"
                            >
                                {copied ? <Check className="w-3 h-3" /> : <Copy className="w-3 h-3" />} 
                                {copied ? 'Đã chép' : 'Sao chép'}
                            </button>
                        </div>
                        <p className="font-mono font-bold text-slate-900 text-sm">THANH TOAN DON {order.order_code}</p>
                    </div>
                    
                    <p className="text-xs text-slate-400 mt-6 italic">
                        * Vui lòng không sửa nội dung chuyển khoản để đơn hàng được xử lý nhanh nhất.
                    </p>
                </div>
            ) : (
                <div className="text-center relative">
                    {/* Background decoration */}
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-green-200 rounded-full blur-3xl opacity-20"></div>
                    
                    {/* Icon COD với tick */}
                    <div className="relative z-10 mb-6">
                        <div className="w-48 h-48 mx-auto bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center shadow-2xl border-4 border-white animate-bounce-slow">
                            <div className="relative">
                                <Truck className="w-24 h-24 text-green-600" strokeWidth={1.5} />
                                {/* Badge tick */}
                                <div className="absolute -top-2 -right-2 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                    <CheckCircle className="w-7 h-7 text-white" strokeWidth={3} />
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 className="text-2xl font-bold text-slate-900 mb-3 font-serif">Đơn hàng COD</h3>
                    <p className="text-slate-600 text-sm leading-relaxed max-w-xs mx-auto mb-6">
                        Shipper sẽ liên hệ với bạn sớm thôi.<br/>
                        Vui lòng chuẩn bị tiền mặt khi nhận hàng nhé!
                    </p>

                    <div className="bg-green-50 border-2 border-green-200 rounded-xl p-4 inline-block">
                        <div className="flex items-center gap-3">
                            <CheckCircle className="w-6 h-6 text-green-600" />
                            <div className="text-left">
                                <div className="text-xs text-green-600 font-bold uppercase">Số tiền thanh toán</div>
                                <div className="text-xl font-bold text-green-700">{Number(order.total_amount).toLocaleString()}đ</div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>

      </div>
    </div>
  );
};

export default OrderSuccessPage;