import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { ShoppingBag, Star, X, Plus, Minus, Filter } from 'lucide-react';
import { assetUrl } from '../config/api.js';
import { apiFetch } from '../services/apiClient.js';

const MenuPage = () => {
  const [activeCategory, setActiveCategory] = useState(null);
  const [menuData, setMenuData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // --- STATE CHO MODAL CHI TIẾT SẢN PHẨM ---
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [productDetail, setProductDetail] = useState(null);
  const [loadingDetail, setLoadingDetail] = useState(false);
  const [quantity, setQuantity] = useState(1);
  const [selectedOptions, setSelectedOptions] = useState({});
  const [totalPrice, setTotalPrice] = useState(0);

  const location = useLocation();

  // --- 1. HÀM XỬ LÝ ẢNH (ĐÃ SỬA: DÙNG PLACEHOLD.CO VÀ LOGIC CHECK LINK) ---
  const getImageUrl = (imagePath) => {
    if (!imagePath) return 'https://placehold.co/400'; // Đổi sang server ảnh ổn định hơn
    if (imagePath.startsWith('http')) return imagePath;

    return assetUrl(imagePath);
  };

  // --- 2. Lấy Menu tổng từ API ---
  useEffect(() => {
    const fetchMenu = async () => {
      try {
        const result = await apiFetch('/api/menu');
        const realData = result.data ? result.data : result;
        setMenuData(Array.isArray(realData) ? realData : []);

        const params = new URLSearchParams(location.search);
        const targetSlug = params.get('category');

        if (Array.isArray(realData) && realData.length > 0) {
          let targetId = realData[0].CategoryID;
          if (targetSlug) {
            const target = realData.find(cat => cat.CategorySlug === targetSlug);
            if (target) targetId = target.CategoryID;
          }
          setActiveCategory(targetId);
          setTimeout(() => scrollToCategory(targetId), 500);
        }
      } catch (err) {
        console.error("Lỗi tải API:", err);
        setError(err.message);
        setMenuData([]);
      } finally {
        setLoading(false);
      }
    };
    fetchMenu();
  }, [location.search]);

  // --- 3. Hàm mở Modal và lấy chi tiết (QUAN TRỌNG: ĐÃ FIX LOGIC CHỌN OPTION) ---
  const openProductModal = async (product) => {
    setSelectedProduct(product);
    setLoadingDetail(true);
    setProductDetail(null);
    setQuantity(1);
    setSelectedOptions({});
    setTotalPrice(Number(product.Price) || 0);

    try {
      const json = await apiFetch(`/api/products/${product.ProductID}`);

      if (json.success || json.data) {
        const detail = json.data || json;
        setProductDetail(detail);

        // --- FIX: CHỈ TỰ ĐỘNG CHỌN NẾU LÀ BẮT BUỘC (REQUIRED) ---
        const defaultOptions = {};
        if (detail.variants) {
          detail.variants.forEach(v => {
            // Kiểm tra flag 'is_required'. Nếu = 1 (Bắt buộc) thì mới chọn cái đầu tiên
            // Nếu không bắt buộc (như Topping), để trống để người dùng tự chọn
            if (v.is_required === 1 && v.variant_values && v.variant_values.length > 0) {
              defaultOptions[v.name] = v.variant_values[0];
            }
          });
        }
        setSelectedOptions(defaultOptions);
      }
    } catch (error) {
      console.error("Lỗi lấy chi tiết:", error);
    } finally {
      setLoadingDetail(false);
    }
  };

  // --- 4. Tính toán giá tiền ---
  useEffect(() => {
    if (!selectedProduct) return;

    let basePrice = Number(selectedProduct.Price) || 0;
    let modifiers = 0;

    // Logic tính giá (Sẽ chỉ cộng nếu option ĐÃ ĐƯỢC CHỌN)
    if (selectedOptions['Size'] === 'Lớn') modifiers += 10000;
    if (selectedOptions['Size'] === 'Vừa') modifiers += 6000;
    
    // Chỉ cộng tiền Topping nếu key 'Topping' tồn tại trong selectedOptions
    if (selectedOptions['Topping'] && selectedOptions['Topping'] !== 'Không topping') modifiers += 8000;

    setTotalPrice((basePrice + modifiers) * quantity);

  }, [selectedOptions, quantity, selectedProduct]);

  const handleOptionSelect = (variantName, value) => {
    // Nếu người dùng click lại vào option đang chọn -> bỏ chọn (toggle)
    // Chỉ áp dụng cho các option không bắt buộc
    setSelectedOptions(prev => {
        const isSelected = prev[variantName] === value;
        // Logic kiểm tra xem variant này có bắt buộc không cần thêm dữ liệu từ productDetail
        // Ở đây ta làm đơn giản: Ghi đè giá trị mới
        return { ...prev, [variantName]: value };
    });
  };

  const closeModal = () => {
    setSelectedProduct(null);
  };

  const scrollToCategory = (id) => {
    setActiveCategory(id);
    const element = document.getElementById(`category-${id}`);
    if (element) {
      const yOffset = -100;
      const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
      window.scrollTo({ top: y, behavior: 'smooth' });
    }
  };

  // --- 5. Hàm thêm vào giỏ hàng ---
  const handleAddToCart = async () => {
    if (!selectedProduct) return;
    
    // Validate: Kiểm tra xem các trường bắt buộc đã được chọn chưa
    if (productDetail && productDetail.variants) {
        const missingRequired = productDetail.variants.find(
            v => v.is_required === 1 && !selectedOptions[v.name]
        );
        if (missingRequired) {
            alert(`Vui lòng chọn ${missingRequired.name}`);
            return;
        }
    }

    const btnText = document.getElementById('btn-add-text');
    if (btnText) btnText.innerText = "Đang xử lý...";

    try {
      const result = await apiFetch('/api/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          product_id: selectedProduct.ProductID,
          quantity: quantity,
          options: selectedOptions
        })
      });

      if (result.success) {
        alert("Đã thêm món vào giỏ thành công! 😋");
        closeModal();
      } else {
        alert(result.message || "Có lỗi xảy ra khi thêm giỏ hàng");
      }
    } catch (error) {
      console.error("Lỗi thêm giỏ hàng:", error);
      alert("Không thể kết nối đến máy chủ");
    } finally {
      if (btnText) btnText.innerText = "Thêm vào giỏ hàng";
    }
  };

  if (loading) return (
    <div className="min-h-screen bg-gray-50 flex pt-24 px-8 gap-8 justify-center">
      <div className="flex flex-col items-center">
        <div className="w-8 h-8 border-2 border-slate-800 border-t-transparent rounded-full animate-spin mb-2"></div>
        <div className="text-slate-400 text-sm animate-pulse">Đang tải thực đơn...</div>
      </div>
    </div>
  );

  return (
    <div className="bg-[#FAFAF8] min-h-screen font-sans text-slate-800">
      <style>
        {`@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap');
          .font-serif { font-family: 'Playfair Display', serif; }
          .font-sans { font-family: 'Inter', sans-serif; }`}
      </style>

      <div className="max-w-[1600px] mx-auto flex items-start gap-8 px-4 md:px-8 pt-4 md:pt-8">

        {/* === LEFT SIDEBAR === */}
        <aside className="hidden lg:block w-64 h-[calc(100vh-6rem)] sticky top-24 overflow-y-auto pr-2 scrollbar-hide">
          <div className="bg-white/80 backdrop-blur-md rounded-2xl p-6 shadow-sm border border-slate-100">
            <h2 className="text-[11px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
              <Filter className="w-3 h-3" /> Danh Mục
            </h2>
            <nav className="space-y-1">
              {menuData.map((cat) => (
                <button
                  key={cat.CategoryID}
                  onClick={() => scrollToCategory(cat.CategoryID)}
                  className={`w-full text-left py-3 px-4 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-between group ${
                    activeCategory === cat.CategoryID
                      ? 'bg-slate-900 text-white shadow-lg transform translate-x-1'
                      : 'text-slate-500 hover:bg-white hover:text-slate-900 hover:shadow-sm'
                  }`}
                >
                  <span>{cat.CategoryName}</span>
                  {activeCategory === cat.CategoryID && <div className="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></div>}
                </button>
              ))}
            </nav>
          </div>
        </aside>

        {/* === MAIN CONTENT === */}
        <main className="flex-1 w-full pb-24">
          {menuData.map((cat) => (
            <div key={cat.CategoryID} id={`category-${cat.CategoryID}`} className="mb-20 scroll-mt-28">

              {/* Category Header Banner - ĐÃ FIX ONERROR */}
              <div className="relative h-56 md:h-72 rounded-[2rem] overflow-hidden mb-10 group shadow-xl shadow-slate-200/50">
                <div className="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent z-10"></div>
                <img 
                  src={getImageUrl(cat.ImageHeader)} 
                  alt={cat.CategoryName} 
                  className="w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105" 
                  onError={(e) => { e.target.onerror = null; e.target.src = 'https://placehold.co/1200x400'; }}
                />
                <div className="absolute bottom-0 left-0 p-8 md:p-12 z-20 text-white w-full">
                  <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[10px] font-bold uppercase tracking-widest mb-4 text-amber-300">
                    <Star className="w-3 h-3 fill-current" /> Collection
                  </span>
                  <h2 className="text-4xl md:text-6xl font-serif font-bold mb-3 tracking-tight">{cat.CategoryName}</h2>
                </div>
              </div>

              {/* Grid Sản Phẩm */}
              {cat.items && cat.items.length > 0 ? (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
                  {cat.items.map((item) => (
                    <div
                      key={item.ProductID}
                      onClick={() => openProductModal(item)}
                      className="group bg-white rounded-2xl hover:shadow-2xl transition-all duration-500 flex flex-col overflow-hidden relative border border-transparent hover:border-slate-100 cursor-pointer"
                    >
                      <div className="relative aspect-[4/5] overflow-hidden bg-gray-100">
                        {item.BadgeTag && (
                          <span className="absolute top-4 left-4 z-20 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg text-white shadow-lg backdrop-blur-md bg-slate-900/90">
                            {item.BadgeTag}
                          </span>
                        )}
                        {/* Ảnh Sản Phẩm - ĐÃ FIX ONERROR */}
                        <img 
                          src={getImageUrl(item.MainImage)} 
                          alt={item.ProductName} 
                          className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                          onError={(e) => { e.target.onerror = null; e.target.src = 'https://placehold.co/400'; }}
                        />
                        <button className="absolute bottom-4 right-4 w-10 h-10 bg-white text-slate-900 rounded-full flex items-center justify-center shadow-lg translate-y-12 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 z-10">
                          <ShoppingBag className="w-5 h-5" />
                        </button>
                      </div>
                      <div className="p-6 flex flex-col flex-1">
                        <h3 className="font-bold text-slate-900 text-lg mb-1 group-hover:text-amber-700 transition-colors line-clamp-1">{item.ProductName}</h3>
                        <div className="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                          <span className="font-serif font-bold text-2xl text-slate-900">
                            {Number(item.Price).toLocaleString('vi-VN')}<span className="text-sm text-slate-400 font-sans ml-0.5">đ</span>
                          </span>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="py-20 text-center text-slate-400">Đang cập nhật...</div>
              )}
            </div>
          ))}
        </main>
      </div>

      {/* ================================================= */}
      {/* === MODAL CHI TIẾT SẢN PHẨM (Popup) === */}
      {/* ================================================= */}
      {selectedProduct && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 px-4 sm:px-6">
          <div className="absolute inset-0 bg-black/60 backdrop-blur-sm" onClick={closeModal}></div>

          <div className="bg-white w-full max-w-4xl rounded-3xl shadow-2xl relative z-10 overflow-hidden flex flex-col md:flex-row max-h-[85vh] animate-in zoom-in-95 duration-300">
            <button onClick={closeModal} className="absolute top-4 right-4 z-20 p-2 bg-white/50 hover:bg-white rounded-full transition-colors shadow-sm">
              <X className="w-5 h-5 text-slate-800" />
            </button>

            {/* Cột Trái: Ảnh trong Modal - ĐÃ FIX ONERROR */}
            <div className="w-full md:w-1/2 bg-gray-100 relative h-48 md:h-auto">
              <img
                src={getImageUrl(selectedProduct.MainImage)}
                alt={selectedProduct.ProductName}
                className="w-full h-full object-cover"
                onError={(e) => { e.target.onerror = null; e.target.src = 'https://placehold.co/600'; }}
              />
            </div>

            {/* Cột Phải: Thông tin & Options */}
            <div className="w-full md:w-1/2 p-6 md:p-8 flex flex-col overflow-y-auto">
              <h2 className="text-2xl md:text-3xl font-serif font-bold text-slate-900 mb-2">{selectedProduct.ProductName}</h2>
              <p className="text-slate-500 text-sm mb-6 font-light leading-relaxed">{selectedProduct.ShortDesc}</p>

              {loadingDetail ? (
                <div className="space-y-4 flex-1">
                  {[1, 2, 3].map(i => <div key={i} className="h-12 bg-gray-100 rounded-lg animate-pulse w-3/4"></div>)}
                </div>
              ) : (
                <div className="space-y-6 flex-1">
                  {productDetail?.variants?.length > 0 ? (
                    productDetail.variants.map((variant) => (
                      <div key={variant.id}>
                        <h4 className="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-1">
                          {variant.name} {variant.is_required === 1 && <span className="text-red-500">*</span>}
                        </h4>
                        <div className="flex flex-wrap gap-2">
                          {variant.variant_values.map((val) => (
                            <button
                              key={val}
                              onClick={() => handleOptionSelect(variant.name, val)}
                              className={`px-4 py-2 rounded-lg text-xs font-bold border transition-all ${selectedOptions[variant.name] === val
                                  ? 'border-slate-900 bg-slate-900 text-white shadow-md'
                                  : 'border-slate-200 text-slate-600 hover:border-slate-400 hover:bg-slate-50'
                                }`}
                            >
                              {val}
                            </button>
                          ))}
                        </div>
                      </div>
                    ))
                  ) : (
                    <p className="text-sm text-slate-400 italic">Sản phẩm này không có tùy chọn.</p>
                  )}
                </div>
              )}

              {/* Footer Modal */}
              <div className="mt-8 pt-6 border-t border-slate-100">
                <div className="flex items-center justify-between mb-6">
                  <span className="font-bold text-sm text-slate-900">Số lượng</span>
                  <div className="flex items-center gap-3 bg-gray-100 rounded-full p-1">
                    <button onClick={() => setQuantity(Math.max(1, quantity - 1))} className="w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-sm hover:text-amber-600 transition-colors"><Minus className="w-3 h-3" /></button>
                    <span className="font-bold w-6 text-center text-sm">{quantity}</span>
                    <button onClick={() => setQuantity(quantity + 1)} className="w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-sm hover:text-amber-600 transition-colors"><Plus className="w-3 h-3" /></button>
                  </div>
                </div>

                <button
                  onClick={handleAddToCart}
                  className="w-full py-4 bg-slate-900 text-white rounded-xl font-bold text-sm uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl shadow-slate-200 flex items-center justify-between px-6"
                >
                  <span id="btn-add-text">Thêm vào giỏ hàng</span>
                  <span className="text-lg font-serif">{totalPrice.toLocaleString('vi-VN')}đ</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default MenuPage;