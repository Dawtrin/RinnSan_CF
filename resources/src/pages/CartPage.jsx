import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { 
  Trash2, Plus, Minus, ArrowLeft, ShoppingBag, 
  Loader2, ArrowRight, MessageSquare, PlusCircle, Star, Check
} from 'lucide-react';
import { apiUrl, assetUrl } from '../config/api.js';

const CartPage = () => {
  const [cartData, setCartData] = useState(null);
  const [menuItems, setMenuItems] = useState([]); 
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [updating, setUpdating] = useState(null);
  const [notes, setNotes] = useState({}); 

  // --- 1. HÀM XỬ LÝ ẢNH ---
  const getImageUrl = (imagePath) => {
    if (!imagePath) return 'https://placehold.co/150'; 
    if (imagePath.startsWith('http')) return imagePath; 
    return assetUrl(imagePath);
  };

  // --- 2. GỌI API ---
  const fetchData = async () => {
    try {
      const [cartRes, menuRes] = await Promise.all([
        fetch(apiUrl('/api/cart'), {
            method: 'GET',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
        }),
        fetch(apiUrl('/api/menu'))
      ]);

      const cartJson = await cartRes.json();
      const menuJson = await menuRes.json();

      if (cartJson.success || cartJson.data) setCartData(cartJson.data || cartJson);
      
      const realMenu = menuJson.data ? menuJson.data : menuJson;
      let allProducts = [];
      if (Array.isArray(realMenu)) {
          realMenu.forEach(cat => {
              if (cat.items) allProducts = [...allProducts, ...cat.items];
          });
      }
      setMenuItems(allProducts);

    } catch (error) { console.error("Lỗi:", error); } 
    finally { setLoading(false); }
  };

  useEffect(() => { fetchData(); }, []);

  // --- 3. HÀM XỬ LÝ (Update/Remove/Note) ---
  const handleUpdateQuantity = async (key, currentQty, change) => {
    const newQty = currentQty + change;
    if (newQty < 1) return;
    setUpdating(key);
    try {
      const res = await fetch(apiUrl('/api/cart/update'), {
        method: 'PUT',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ key, quantity: newQty })
      });
      const json = await res.json();
      if (json.success || json.data) setCartData(json.data || json);
    } catch (err) { console.error(err); } 
    finally { setUpdating(null); }
  };

  const handleRemoveItem = async (key) => {
    if (!window.confirm("Xóa món này khỏi giỏ?")) return;
    try {
      const res = await fetch(apiUrl('/api/cart/remove'), {
        method: 'DELETE',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ key })
      });
      const json = await res.json();
      if (json.success || json.data) setCartData(json.data || json);
    } catch (err) { console.error(err); }
  };

  const handleNoteChange = (key, e) => {
    setNotes(prev => ({ ...prev, [key]: e.target.value }));
  };

  // Logic gợi ý món
  const getSuggestions = () => {
    if (!menuItems.length) return [];
    const currentItemIds = cartData?.items?.map(i => i.product_id) || [];
    const potential = menuItems.filter(p => !currentItemIds.includes(p.ProductID));
    return potential.sort(() => 0.5 - Math.random()).slice(0, 5);
  };

  const handleAddQuick = async (product) => {
    const btn = document.getElementById(`btn-add-${product.ProductID}`);
    if(btn) btn.innerHTML = '<span class="animate-spin">↻</span>';
    try {
        const res = await fetch(apiUrl('/api/cart/add'), {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: product.ProductID, quantity: 1, options: {} })
        });
        if(res.ok) fetchData(); 
    } catch(e) { console.error(e); }
  };
  const handleCheckout = () => {
    navigate('/checkout'); 
  };

  if (loading) return (
    <div className="h-screen flex flex-col items-center justify-center bg-[#FDFBF9]">
      <Loader2 className="w-12 h-12 animate-spin text-slate-800 mb-4" />
      <span className="text-slate-500 font-serif italic">Đang chuẩn bị không gian...</span>
    </div>
  );

  const items = cartData?.items || [];
  const summary = cartData?.summary || { subtotal: 0 };
  const FREE_SHIP_LIMIT = 150000;
  const currentTotal = Number(summary.subtotal);
  const progress = Math.min((currentTotal / FREE_SHIP_LIMIT) * 100, 100);
  const remaining = FREE_SHIP_LIMIT - currentTotal;
  const suggestedItems = getSuggestions();

  return (
    <div className="h-screen bg-white font-sans overflow-hidden flex flex-col lg:flex-row text-slate-800">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap');
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Outfit', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
      `}</style>

      {/* CỘT TRÁI: BRANDING */}
      <div className="hidden lg:block w-[35%] xl:w-[30%] relative bg-slate-900 h-full">
        <img 
          src="https://images.unsplash.com/photo-1559496417-e7f25cb247f3?q=80&w=1964&auto=format&fit=crop" 
          alt="Coffee Art" 
          className="w-full h-full object-cover opacity-50 mix-blend-overlay"
        />
        <div className="absolute inset-0 bg-gradient-to-b from-transparent to-black/90"></div>
        <div className="absolute bottom-0 left-0 p-10 w-full text-white z-10">
            <h2 className="text-5xl font-serif font-bold mb-4 leading-tight">
                RinnSan <br/> Moments.
            </h2>
            <p className="text-slate-300 font-light text-base mb-8 leading-relaxed">
                Thưởng thức hương vị trọn vẹn trong từng khoảnh khắc.
            </p>
        </div>
      </div>

      {/* CỘT PHẢI: NỘI DUNG GIỎ HÀNG */}
      <div className="flex-1 h-full flex flex-col bg-[#F8F9FA]">
        
        {/* HEADER */}
        <div className="shrink-0 pt-8 pb-4 px-6 md:px-10 bg-white z-20 shadow-sm">
            <div className="flex justify-between items-center mb-6">
                <Link to="/menu" className="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-900 hover:text-white hover:border-transparent transition-all shadow-sm group">
                    <ArrowLeft className="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" />
                </Link>
                
                <div className="flex items-center gap-3 text-[10px] sm:text-xs font-bold tracking-widest uppercase">
                    <span className="text-slate-900 border-b-2 border-amber-500 pb-1">01. Giỏ hàng</span>
                    <span className="text-slate-300">⎯⎯</span>
                    <span className="text-slate-300">02. Thanh toán</span>
                    <span className="text-slate-300 hidden sm:inline">⎯⎯</span>
                    <span className="text-slate-300 hidden sm:inline">03. Hoàn tất</span>
                </div>
            </div>

            <div>
                <p className="text-slate-500 text-sm font-medium mb-1">👋 Chào bạn, hôm nay bạn muốn nạp năng lượng gì?</p>
                <h1 className="font-serif font-bold text-3xl md:text-4xl text-slate-900 flex items-center gap-3">
                    Giỏ hàng của bạn <span className="text-lg font-sans font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-lg align-middle">{items.length} món</span>
                </h1>
            </div>
        </div>

        {/* SCROLLABLE CONTENT */}
        <div className="flex-1 overflow-y-auto custom-scrollbar px-6 md:px-10 pb-40 pt-6"> 
            {items.length > 0 ? (
                <div className="max-w-3xl mx-auto space-y-8">
                    
                    {/* FREESHIP BAR */}
                    <div className="bg-white p-5 rounded-2xl shadow-sm border border-amber-100 relative overflow-hidden">
                        <div className="flex justify-between items-end mb-3 relative z-10">
                            <span className="text-sm font-semibold text-slate-700">
                                {remaining > 0 
                                    ? <span>Mua thêm <span className="text-amber-600 font-bold text-lg">{remaining.toLocaleString('vi-VN')}đ</span> để được Freeship</span>
                                    : <span className="text-green-600 flex items-center gap-1 font-bold"><Check className="w-4 h-4" /> Chúc mừng! Bạn đã được Freeship</span>
                                }
                            </span>
                            <span className="text-[10px] font-bold bg-amber-50 text-amber-800 px-2 py-1 rounded-lg">{Math.round(progress)}%</span>
                        </div>
                        <div className="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden relative z-10">
                            <div className={`h-full transition-all duration-1000 ease-out rounded-full ${progress >= 100 ? 'bg-green-500' : 'bg-gradient-to-r from-amber-400 to-amber-600'}`} style={{ width: `${progress}%` }}></div>
                        </div>
                    </div>

                    {/* LIST ITEMS */}
                    <div className="space-y-5">
                        {items.map((item) => (
                            <div key={item.key} className="bg-white p-5 rounded-[2rem] shadow-sm border border-transparent hover:border-amber-100 hover:shadow-xl transition-all duration-300 group">
                                <div className="flex flex-col sm:flex-row gap-5">
                                    <div className="w-full sm:w-28 h-28 shrink-0 rounded-2xl overflow-hidden bg-[#F5F5F0] relative shadow-md">
                                        {updating === item.key && (
                                            <div className="absolute inset-0 bg-white/70 z-10 flex items-center justify-center backdrop-blur-[1px]"><Loader2 className="w-6 h-6 animate-spin text-slate-800" /></div>
                                        )}
                                        <img 
                                          src={getImageUrl(item.image || item.MainImage)}
                                          className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 mix-blend-multiply" 
                                          alt={item.name} 
                                          onError={(e) => { e.target.onerror = null; e.target.src = 'https://placehold.co/150'; }}
                                        />
                                    </div>
                                    
                                    <div className="flex-1 min-w-0 flex flex-col justify-between">
                                        <div className="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 className="text-lg font-bold text-slate-900 font-serif leading-tight mb-2">{item.name}</h3>
                                                <div className="flex flex-wrap gap-1.5">
                                                    {item.options && Object.entries(item.options).map(([k, v]) => (
                                                        <span key={k} className="inline-flex items-center px-2 py-1 rounded-lg bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wide">
                                                            <span className="text-slate-300 mr-1">{k}:</span> {v}
                                                        </span>
                                                    ))}
                                                </div>
                                            </div>
                                            <span className="font-serif font-bold text-lg text-slate-900">{(item.price * item.quantity).toLocaleString()}đ</span>
                                        </div>

                                        <div className="flex flex-col sm:flex-row items-end sm:items-center justify-between gap-4 mt-2">
                                            <div className="w-full sm:w-auto flex-1 max-w-full sm:max-w-[220px] relative group/input mr-auto">
                                                <div className="flex items-center gap-2 bg-slate-50 rounded-xl px-3 py-2 border border-transparent group-focus-within/input:bg-white group-focus-within/input:border-amber-200 group-focus-within/input:shadow-sm transition-all">
                                                    <MessageSquare className="w-3.5 h-3.5 text-slate-400 group-focus-within/input:text-amber-500" />
                                                    <input 
                                                        type="text" 
                                                        placeholder="Ghi chú (vd: Ít ngọt...)" 
                                                        value={notes[item.key] || ''}
                                                        onChange={(e) => handleNoteChange(item.key, e)}
                                                        className="w-full bg-transparent border-none p-0 text-xs font-medium text-slate-700 placeholder:text-slate-400 focus:ring-0"
                                                    />
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-4">
                                                <div className="flex items-center bg-slate-100 rounded-full p-1 shadow-inner gap-2">
                                                    <button onClick={() => handleUpdateQuantity(item.key, item.quantity, -1)} className="w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-600 shadow-sm hover:text-slate-900 hover:scale-105 transition-all"><Minus className="w-3.5 h-3.5" /></button>
                                                    <span className="w-6 text-center font-bold text-sm text-slate-900">{item.quantity}</span>
                                                    <button onClick={() => handleUpdateQuantity(item.key, item.quantity, 1)} className="w-8 h-8 flex items-center justify-center rounded-full bg-slate-900 text-white shadow-sm hover:bg-amber-500 hover:scale-105 transition-all"><Plus className="w-3.5 h-3.5" /></button>
                                                </div>
                                                <button onClick={() => handleRemoveItem(item.key)} className="w-9 h-9 flex items-center justify-center rounded-full border border-slate-100 text-slate-300 hover:text-red-500 hover:bg-red-50 hover:border-red-100 transition-all"><Trash2 className="w-4 h-4" /></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* 3. CROSS-SELL (Voucher Input đã bị xóa) */}
                    {suggestedItems.length > 0 && (
                        <div className="pt-4">
                            <h3 className="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                                <PlusCircle className="w-4 h-4" /> Có thể bạn sẽ thích
                            </h3>
                            <div className="flex gap-4 overflow-x-auto pb-4 custom-scrollbar snap-x">
                                {suggestedItems.map((prod) => (
                                    <div key={prod.ProductID} className="snap-start min-w-[150px] w-[150px] bg-white p-3 rounded-2xl border border-slate-100 hover:border-amber-400 cursor-pointer transition-all relative group shadow-sm" onClick={() => handleAddQuick(prod)}>
                                        <div className="h-24 rounded-xl overflow-hidden mb-3 bg-[#F5F5F0]">
                                            <img 
                                              src={getImageUrl(prod.MainImage)} 
                                              className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 mix-blend-multiply" 
                                              alt={prod.ProductName} 
                                              onError={(e) => { e.target.onerror = null; e.target.src = 'https://placehold.co/150'; }}
                                            />
                                        </div>
                                        <h4 className="text-xs font-bold text-slate-800 line-clamp-1 mb-1">{prod.ProductName}</h4>
                                        <div className="flex justify-between items-center">
                                            <span className="text-xs font-medium text-slate-500">{Number(prod.Price).toLocaleString()}đ</span>
                                            <button id={`btn-add-${prod.ProductID}`} className="w-7 h-7 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-slate-900 hover:text-white transition-all">
                                                <Plus className="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            ) : (
                /* EMPTY STATE */
                <div className="h-full flex flex-col items-center justify-center text-center">
                    <div className="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <ShoppingBag className="w-10 h-10 text-slate-300" />
                    </div>
                    <h2 className="text-xl font-bold text-slate-900 mb-2">Giỏ hàng trống</h2>
                    <p className="text-sm text-slate-500 mb-6">Bạn chưa chọn món nào.</p>
                    <Link to="/menu" className="px-6 py-2.5 bg-slate-900 text-white rounded-lg font-bold text-sm hover:bg-amber-600 transition-all">
                        Xem thực đơn
                    </Link>
                </div>
            )}
        </div>

        {/* STICKY FOOTER */}
        {items.length > 0 && (
            <div className="shrink-0 bg-white border-t border-slate-100 p-5 md:px-8 shadow-[0_-5px_30px_rgb(0,0,0,0.08)] z-30 w-full">
                <div className="max-w-2xl mx-auto flex flex-col gap-4">
                    <div className="flex justify-between items-center">
                        <div className="flex flex-col">
                            <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">Tổng cộng</span>
                            <div className="flex items-baseline gap-1">
                                <span className="text-3xl font-serif font-bold text-slate-900">{Number(summary.subtotal).toLocaleString('vi-VN')}</span>
                                <span className="text-sm font-bold text-slate-500">đ</span>
                            </div>
                        </div>
                        <div className="text-right">
                            <span className="text-xs text-slate-400 block mb-1">(Đã bao gồm thuế)</span>
                            {progress >= 100 && <span className="text-xs text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded flex items-center gap-1 justify-end"><Check className="w-3 h-3" /> Free Ship</span>}
                        </div>
                    </div>

                    <button 
                    onClick={handleCheckout}
                    className="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-base uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl shadow-slate-200/50 flex items-center justify-center gap-3 group active:scale-[0.98]">
                        <span>Thanh toán ngay</span>
                        <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                    </button>
                </div>
            </div>
        )}
      </div>
    </div>
  );
};

export default CartPage;