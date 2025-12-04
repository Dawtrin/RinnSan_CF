import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { 
  ShoppingCart, User, Search, Menu, X, Phone, 
  MapPin, Clock, ChevronDown, ArrowRight 
} from 'lucide-react';

// --- MOCK DATA: Dùng link ảnh Unsplash chất lượng cao ---
const MEGA_MENU_DATA = [
  {
    id: 1,
    title: "CÀ PHÊ & ESPRESSO",
    path: "/menu?category=cafe",
    desc: "Hương vị Ý đích thực từ hạt Arabica tuyển chọn.",
    image: "https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=1000&auto=format&fit=crop", 
    items: ["Espresso", "Cafe Latte", "Cappuccino", "Cold Brew", "Cafe Cốt Dừa"]
  },
  {
    id: 2,
    title: "TRÀ & TRÀ SỮA",
    path: "/menu?category=tra",
    desc: "Sự hòa quyện giữa lá trà Bảo Lộc và sữa tươi thanh trùng.",
    image: "https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=1000&auto=format&fit=crop",
    items: ["Trà Sữa Trân Châu", "Trà Đào Cam Sả", "Trà Sen Vàng", "Matcha Latte"]
  },
  {
    id: 3,
    title: "ICE BLENDED (ĐÁ XAY)",
    path: "/menu?category=da-xay",
    desc: "Mát lạnh sảng khoái, đánh tan cái nóng mùa hè.",
    image: "https://images.unsplash.com/photo-1577805947697-89e18249d767?q=80&w=1000&auto=format&fit=crop",
    items: ["Chocolate Đá Xay", "Matcha Đá Xay", "Cookies & Cream", "Mango Đá Xay"]
  },
  {
    id: 4,
    title: "BÁNH NGỌT & PASTRY",
    path: "/menu?category=banh-ngot",
    desc: "Ngọt ngào, tinh tế trong từng lớp bánh thủ công.",
    image: "https://images.unsplash.com/photo-1571115177098-24ec42ed204d?q=80&w=1000&auto=format&fit=crop",
    items: ["Tiramisu", "Croissant", "Red Velvet", "Cheesecake", "Macaron"]
  },
  {
    id: 5,
    title: "BÁNH MÌ VIỆT NAM",
    path: "/menu?category=banh-mi",
    desc: "Nét văn hóa ẩm thực đường phố được nâng tầm.",
    image: "https://images.unsplash.com/photo-1635586638533-3d7c35777176?q=80&w=1000&auto=format&fit=crop",
    items: ["Bánh Mì Que", "Baguette", "Bánh Mì Gối"]
  },
  {
    id: 6,
    title: "BÁNH ÂU & TRÁNG MIỆNG",
    path: "/menu?category=banh-au",
    desc: "Hương vị cổ điển từ những tiệm bánh Paris.",
    image: "https://images.unsplash.com/photo-1509482560494-4126f8225994?q=80&w=1000&auto=format&fit=crop",
    items: ["Tart Trứng", "Bánh Su Kem", "Bánh Phô Mai Chanh Leo"]
  },
  {
    id: 7,
    title: "NƯỚC ÉP & SINH TỐ",
    path: "/menu?category=nuoc-ep",
    desc: "Vitamin tươi ngon từ trái cây nhiệt đới.",
    image: "https://images.unsplash.com/photo-1613478223719-2ab802602423?q=80&w=1000&auto=format&fit=crop",
    items: ["Nước Ép Cam", "Nước Ép Dưa Hấu", "Sinh Tố Bơ", "Sinh Tố Xoài"]
  }
];

const Header = () => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState(null);
  // State mới: Theo dõi danh mục đang được hover để hiển thị ảnh tương ứng
  const [hoveredCategory, setHoveredCategory] = useState(MEGA_MENU_DATA[0]); 
  const [cartCount] = useState(3);
  const location = useLocation();

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 10);
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const isActive = (path) => location.pathname === path;

  return (
    <>
      {/* 1. TOP BAR (Dark Luxury) */}
      <div className="bg-slate-950 text-slate-400 text-[10px] uppercase font-bold tracking-widest relative z-[60]">
        <div className="max-w-[1600px] mx-auto px-8">
          <div className="h-10 flex items-center justify-between">
            <div className="flex items-center gap-6">
               <span className="text-white">Rinn's Artisan Bakery</span>
               <span className="hidden md:inline w-px h-3 bg-slate-800"></span>
               <span className="hidden md:inline">Since 2024</span>
            </div>
            <div className="flex items-center gap-6">
              <a href="tel:+841900636999" className="hover:text-white transition-colors flex items-center gap-2">
                <Phone className="w-3 h-3" />
                <span>1900 636 999</span>
              </a>
              <span className="text-emerald-500 flex items-center gap-1">
                <div className="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                Open Now
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* 2. MAIN HEADER */}
      <header className={`sticky top-0 z-50 transition-all duration-500 border-b border-transparent ${
        isScrolled 
          ? 'bg-white/90 backdrop-blur-xl border-slate-100 shadow-sm' 
          : 'bg-white'
      }`}>
        <div className="max-w-[1600px] mx-auto px-8">
          <div className={`flex items-center justify-between transition-all duration-500 ${
            isScrolled ? 'h-18' : 'h-24'
          }`}>

            {/* Logo - Typographic Style (Sang trọng hơn) */}
            <Link to="/" className="flex flex-col group z-50">
              <span className="text-2xl font-black tracking-tighter text-slate-900 group-hover:text-amber-700 transition-colors">RINN'S.</span>
              <span className="text-[9px] tracking-[0.4em] font-bold text-slate-400 uppercase group-hover:tracking-[0.5em] transition-all">Bakery & Coffee</span>
            </Link>

            {/* Desktop Navigation */}
            <nav className="hidden lg:flex items-center gap-12 h-full">
              {[
                { name: 'TRANG CHỦ', path: '/' },
                { name: 'THỰC ĐƠN', path: '/menu', isMega: true },
                { name: 'CÂU CHUYỆN', path: '/story' },
                { name: 'WORKSHOP', path: '/workshop' },
                { name: 'LIÊN HỆ', path: '/contact' },
              ].map((item) => (
                <div 
                  key={item.name} 
                  className="relative h-full flex items-center group/nav"
                  onMouseEnter={() => {
                    if (item.isMega) {
                      setActiveDropdown('MENU');
                      setHoveredCategory(MEGA_MENU_DATA[0]); // Reset về mục đầu tiên khi mới mở
                    }
                  }}
                  onMouseLeave={() => setActiveDropdown(null)}
                >
                  <Link
                    to={item.path}
                    className={`relative text-xs font-bold tracking-[0.2em] transition-all duration-300 py-6 ${
                      isActive(item.path) || (item.isMega && activeDropdown === 'MENU')
                        ? 'text-slate-900'
                        : 'text-slate-500 hover:text-slate-900'
                    }`}
                  >
                    {item.name}
                    {/* Hover Underline Animation */}
                    <span className={`absolute bottom-5 left-0 w-full h-px bg-slate-900 transform origin-left transition-transform duration-300 ${
                       isActive(item.path) || (item.isMega && activeDropdown === 'MENU') ? 'scale-x-100' : 'scale-x-0 group-hover/nav:scale-x-100'
                    }`}></span>
                  </Link>

                  {/* === NEW FLAGSHIP MEGA MENU (FULL WIDTH) === */}
                 {item.isMega && activeDropdown === 'MENU' && (
  <div 
    className="fixed left-0 w-full bg-white shadow-2xl border-t border-slate-100 animate-in fade-in slide-in-from-top-2 duration-200 z-[999]"
    style={{
      // Dòng này giúp Menu tự tìm đáy của Header và dính chặt vào đó, không bao giờ bị hở
      top: document.querySelector('header')?.getBoundingClientRect().bottom + 'px'
    }}
  >
                      {/* Container giới hạn độ rộng nội dung nhưng background full màn hình */}
                      <div className="w-full h-[500px]"> 
                        <div className="max-w-[1600px] mx-auto h-full flex">
                          
                          {/* CỘT TRÁI: DANH SÁCH (30%) */}
                          <div className="w-[30%] py-12 pr-12 border-r border-slate-100 flex flex-col justify-between bg-white z-10">
                            <div>
                              <p className="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-8 pl-4">Danh mục sản phẩm</p>
                              <ul className="space-y-1">
                                {MEGA_MENU_DATA.map((category) => (
                                  <li 
                                    key={category.id}
                                    onMouseEnter={() => setHoveredCategory(category)}
                                    className="relative"
                                  >
                                    <Link 
                                      to={category.path}
                                      className={`block py-3 pl-4 text-sm font-bold tracking-wide transition-all duration-300 flex items-center justify-between group ${
                                        hoveredCategory.id === category.id 
                                          ? 'text-white bg-slate-900 shadow-lg shadow-slate-200 translate-x-2 rounded-lg' 
                                          : 'text-slate-500 hover:text-slate-900'
                                      }`}
                                    >
                                      {category.title}
                                      {hoveredCategory.id === category.id && <ArrowRight className="w-4 h-4 mr-3" />}
                                    </Link>
                                  </li>
                                ))}
                              </ul>
                            </div>
                            <div className="pl-4 pt-6 border-t border-slate-100">
                                <Link to="/menu" className="text-xs font-bold underline decoration-slate-300 underline-offset-4 hover:text-amber-700 transition-colors">
                                  Xem tất cả sản phẩm
                                </Link>
                            </div>
                          </div>

                          {/* CỘT PHẢI: HÌNH ẢNH & PREVIEW (70%) */}
                          <div className="w-[70%] relative overflow-hidden bg-slate-50">
                             {/* Loop qua data để render ảnh, dùng opacity để transition mượt mà */}
                             {MEGA_MENU_DATA.map((category) => (
                               <div 
                                  key={category.id}
                                  className={`absolute inset-0 transition-opacity duration-700 ease-in-out ${
                                    hoveredCategory.id === category.id ? 'opacity-100 z-10' : 'opacity-0 z-0'
                                  }`}
                               >
                                  {/* Background Image */}
                                  <div 
                                    className="absolute inset-0 bg-cover bg-center transition-transform duration-[2000ms] ease-out transform scale-100"
                                    style={{ backgroundImage: `url(${category.image})` }}
                                  ></div>
                                  
                                  {/* Dark Gradient Overlay */}
                                  <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                  {/* Nội dung nổi trên ảnh */}
                                  <div className="absolute bottom-0 left-0 w-full p-16 text-white transform transition-transform duration-500 translate-y-0">
                                    <span className="inline-block py-1 px-3 border border-white/30 rounded-full text-[10px] font-bold tracking-widest uppercase mb-4 backdrop-blur-md">
                                      Featured Collection
                                    </span>
                                    <h3 className="text-5xl font-black tracking-tight mb-4 leading-tight">
                                      {category.title}
                                    </h3>
                                    <p className="text-lg text-slate-200 font-light max-w-xl mb-8 leading-relaxed">
                                      {category.desc}
                                    </p>
                                    
                                    {/* List items con nằm ngang */}
                                    <div className="flex gap-3 flex-wrap">
                                      {category.items.map((item, idx) => (
                                        <span key={idx} className="text-xs font-medium text-white/80 bg-white/10 px-3 py-1.5 rounded backdrop-blur-sm border border-white/10 hover:bg-white hover:text-black transition-colors cursor-pointer">
                                          {item}
                                        </span>
                                      ))}
                                    </div>
                                  </div>
                               </div>
                             ))}
                          </div>

                        </div>
                      </div>
                    </div>
                  )}
                  {/* === END NEW MEGA MENU === */}

                </div>
              ))}
            </nav>

            {/* Right Icons */}
            <div className="flex items-center gap-2">
               <button className="p-3 hover:bg-slate-50 rounded-full transition-colors">
                 <Search className="w-5 h-5 text-slate-900" />
               </button>
               <Link to="/cart" className="p-3 hover:bg-slate-50 rounded-full transition-colors relative">
                 <ShoppingCart className="w-5 h-5 text-slate-900" />
                 {cartCount > 0 && (
                   <span className="absolute top-1 right-1 w-4 h-4 bg-amber-600 text-white text-[9px] font-bold flex items-center justify-center rounded-full">
                     {cartCount}
                   </span>
                 )}
               </Link>
               <button 
                  onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                  className="lg:hidden p-3"
               >
                 <Menu className="w-6 h-6 text-slate-900" />
               </button>
               <Link to="/order" className="hidden lg:flex ml-4 px-8 py-3 bg-slate-900 text-white text-xs font-bold tracking-widest hover:bg-amber-700 transition-colors">
                 ORDER ONLINE
               </Link>
            </div>
          </div>
        </div>

        {/* Mobile Menu (Giữ nguyên hoặc tùy chỉnh sau) */}
        {isMobileMenuOpen && (
           <div className="lg:hidden fixed inset-0 top-0 bg-white z-[100] p-6 overflow-y-auto">
             <div className="flex justify-between items-center mb-8">
               <span className="text-xl font-bold">MENU</span>
               <button onClick={() => setIsMobileMenuOpen(false)}><X className="w-6 h-6" /></button>
             </div>
             {/* ... Mobile content ... */}
           </div>
        )}
      </header>
    </>
  );
};

export default Header;