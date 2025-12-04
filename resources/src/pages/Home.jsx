import React from 'react';
import { Link } from 'react-router-dom';
import { 
  ArrowRight, Star, Clock, Coffee, Sparkles, 
  ChevronRight, ShoppingBag, Heart, ChefHat, Wheat, 
  MapPin, Phone, Mail
} from 'lucide-react';

const Home = () => {
  return (
    <div className="bg-[#FAFAF8] min-h-screen font-sans selection:bg-amber-200 text-slate-800">
      
      {/* === 1. HERO SECTION (Cinematic & Clean) === */}
      <section className="relative h-screen w-full overflow-hidden">
        <div className="absolute inset-0">
          <div className="absolute inset-0 bg-black/20 z-10"></div>
          <div className="absolute inset-0 bg-gradient-to-t from-[#FAFAF8] via-transparent to-transparent z-10"></div>
          {/* Ảnh nền đổi sang Coffee Barista & Bánh */}
          <img 
            src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=2400&auto=format&fit=crop" 
            alt="Coffee and Bakery Atmosphere" 
            className="w-full h-full object-cover animate-slow-zoom"
          />
        </div>
        <div className="relative z-20 h-full flex flex-col justify-center items-center text-center px-6 pt-20">
          <div className="inline-flex items-center gap-3 mb-8 animate-fade-in-up">
            <div className="h-[1px] w-12 bg-white/60"></div>
            <span className="text-white/90 text-[10px] md:text-xs font-bold tracking-[0.4em] uppercase shadow-sm">
              Est. 2024 • Da Nang Flagship
            </span>
            <div className="h-[1px] w-12 bg-white/60"></div>
          </div>
          <h1 className="text-7xl md:text-9xl font-serif text-white tracking-tighter mb-8 leading-[0.85] drop-shadow-2xl animate-fade-in-up delay-100">
            BREW <span className="font-sans font-light italic text-amber-300 mx-2">&</span> BAKE
          </h1>
          <p className="text-white/90 text-lg font-light max-w-xl mb-12 leading-relaxed animate-fade-in-up delay-200 drop-shadow-md">
            Nơi hương vị cà phê thượng hạng gặp gỡ nghệ thuật làm bánh thủ công. <br className="hidden md:block"/>
            Một bản giao hưởng đánh thức mọi giác quan tại Đà Nẵng.
          </p>
          <div className="flex gap-4 animate-fade-in-up delay-300">
            <Link to="/menu" className="group relative px-8 py-4 bg-white text-slate-900 text-xs font-bold tracking-widest overflow-hidden hover:shadow-lg transition-shadow">
              <span className="relative z-10 flex items-center gap-2 group-hover:gap-4 transition-all">
                KHÁM PHÁ MENU <ArrowRight className="w-4 h-4" />
              </span>
              <div className="absolute inset-0 bg-amber-400 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 ease-out"></div>
            </Link>
          </div>
        </div>
      </section>

      {/* === 2. SECTION: VỀ CHÚNG TÔI (Chi tiết & Có chiều sâu - Style B&B) === */}
      <section className="py-32 px-6 md:px-12 max-w-[1600px] mx-auto">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
          
          {/* Cột ảnh (Layering effect & Stamp) */}
          <div className="relative group">
            {/* Ảnh chính - Đổi sang Latte Art */}
            <div className="relative z-10 rounded-t-full overflow-hidden border-[1px] border-slate-200 shadow-2xl aspect-[3/4] lg:aspect-auto lg:h-[650px]">
              <img 
                src="https://images.unsplash.com/photo-1511920170033-f8396924c348?q=80&w=1200&auto=format&fit=crop" 
                alt="Latte Art" 
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[2s]"
              />
            </div>
            
            {/* Ảnh phụ (Parallax nhỏ) */}
            <div className="absolute -bottom-12 -left-8 z-20 w-48 h-48 border-8 border-[#FAFAF8] rounded-full overflow-hidden shadow-xl hidden md:block">
               <img 
                src="https://images.unsplash.com/photo-1585476532402-9ae39d48227b?q=80&w=500" 
                alt="Baker Hand" 
                className="w-full h-full object-cover"
              />
            </div>

            {/* Tem trang trí (Stamp xoay) */}
            <div className="absolute -top-6 -right-6 z-20 bg-amber-500 rounded-full p-1 shadow-xl animate-spin-slow hidden md:block">
               <svg viewBox="0 0 100 100" width="100" height="100" className="fill-current text-white">
                  <path id="curve" d="M 50 50 m -37 0 a 37 37 0 1 1 74 0 a 37 37 0 1 1 -74 0" fill="transparent"/>
                  <text fontSize="12" fontWeight="bold" letterSpacing="1">
                    <textPath href="#curve">
                      PREMIUM QUALITY • SINCE 2024 •
                    </textPath>
                  </text>
               </svg>
            </div>
          </div>

          {/* Cột chữ (Rich Content) */}
          <div className="pl-0 lg:pl-4">
            <div className="flex items-center gap-4 mb-6">
               <div className="h-[1px] w-12 bg-amber-600"></div>
               <span className="text-amber-600 font-bold text-xs tracking-widest uppercase">Câu chuyện thương hiệu</span>
            </div>
            
            <h2 className="text-4xl md:text-6xl font-serif text-slate-900 mb-8 leading-[1.1]">
              Từ đam mê nhỏ bé <br/>
              <span className="italic text-slate-400 font-light">đến biểu tượng Đà Nẵng.</span>
            </h2>
            
            <div className="prose prose-lg prose-slate text-slate-600 mb-10 text-justify">
              <p className="mb-4">
                Tọa lạc ngay tại trung tâm đường Bạch Đằng thơ mộng, <strong className="text-slate-900 font-bold underline decoration-amber-300 decoration-2 underline-offset-4">Rinn's Bakery</strong> không chỉ là một tiệm bánh, mà là một điểm đến văn hóa.
              </p>
              <p>
                Chúng tôi tin rằng "Bánh ngon phải đi kèm Cà phê chuẩn". Đó là lý do mỗi mẻ bánh tại Rinn's đều được tính toán tỉ mỉ về độ ngọt, độ giòn để cân bằng hoàn hảo với hương vị đậm đà của hạt Arabica rang mộc.
              </p>
            </div>

            {/* Features Icons */}
            <div className="grid grid-cols-2 gap-y-8 gap-x-4 border-t border-slate-200 pt-8 mb-10">
              <div className="flex items-start gap-4">
                <div className="p-3 bg-amber-100 text-amber-700 rounded-full"><Clock className="w-5 h-5"/></div>
                <div>
                  <h4 className="font-bold text-slate-900 text-sm uppercase tracking-wide mb-1">Tươi Mỗi Ngày</h4>
                  <p className="text-xs text-slate-500">Bánh nướng mới mỗi 4 giờ</p>
                </div>
              </div>
              <div className="flex items-start gap-4">
                <div className="p-3 bg-amber-100 text-amber-700 rounded-full"><Wheat className="w-5 h-5"/></div>
                <div>
                  <h4 className="font-bold text-slate-900 text-sm uppercase tracking-wide mb-1">Nguyên Liệu Gốc</h4>
                  <p className="text-xs text-slate-500">Bột Pháp & Bơ Elle & Vire</p>
                </div>
              </div>
            </div>

            <Link to="/about" className="group inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-900 border-b-2 border-slate-900 pb-1 hover:text-amber-600 hover:border-amber-600 transition-all">
              Đọc toàn bộ câu chuyện <ArrowRight className="w-4 h-4 transform group-hover:translate-x-1 transition-transform"/>
            </Link>
          </div>
        </div>
      </section>



      

      {/* === 3. SECTION: SẢN PHẨM BÁN CHẠY (2 Hàng - Có thức uống) === */}
      <section className="py-24 bg-[#FAFAF8] border-y border-slate-100">
        <div className="max-w-[1600px] mx-auto px-6">
          <div className="text-center mb-16">
            <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">Rinn's Favorites</span>
            <h2 className="text-4xl md:text-5xl font-serif text-slate-900 mb-4">Best Sellers</h2>
            <div className="w-20 h-1 bg-amber-400 mx-auto rounded-full"></div>
          </div>

          {/* Grid 2 Hàng (6 Sản phẩm: 3 Bánh, 3 Nước) */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 gap-y-12">
            {[
              // Hàng 1: Bánh
              {
                id: 1, name: 'New York Roll', sub: 'Chocolate & Hazelnut', price: '65.000đ',
                img: 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=800', tag: 'HOT'
              },
              {
                id: 2, name: 'Charcoal Bagel', sub: 'Shrimp & Avocado', price: '85.000đ',
                img: 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?q=80&w=800', tag: 'CHEF CHOICE'
              },
              {
                id: 3, name: 'Berry Mousse Box', sub: 'Fresh Strawberry', price: '120.000đ',
                img: 'https://images.unsplash.com/photo-1563729768647-d81b3a0029cc?q=80&w=800', tag: null
              },
              // Hàng 2: Thức uống (Cập nhật hình ảnh đồ uống)
              {
                id: 4, name: 'Cold Brew Cam Vàng', sub: 'Signature Coffee', price: '65.000đ',
                img: 'https://images.unsplash.com/photo-1517701604599-bb29b5dd7359?q=80&w=800', tag: 'NEW'
              },
              {
                id: 5, name: 'Matcha Latte', sub: 'Premium Japanese Matcha', price: '70.000đ',
                img: 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?q=80&w=800', tag: null
              },
              {
                id: 6, name: 'Trà Vải Hoa Hồng', sub: 'Summer Refreshing', price: '55.000đ',
                img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=800', tag: 'BEST SELLER'
              }
            ].map((item) => (
              <div key={item.id} className="group cursor-pointer">
                {/* Image Container */}
                <div className="relative overflow-hidden rounded-2xl mb-5 bg-white aspect-[4/5] border border-slate-100 shadow-sm group-hover:shadow-xl transition-all duration-500">
                  <img 
                    src={item.img} 
                    alt={item.name} 
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  />
                  
                  {/* Tags */}
                  {item.tag && (
                    <div className="absolute top-4 left-4 bg-white/95 backdrop-blur px-3 py-1 text-[10px] font-bold tracking-widest uppercase text-slate-900 border border-slate-200 shadow-sm rounded-sm">
                      {item.tag}
                    </div>
                  )}

                  {/* Overlay Actions */}
                  <div className="absolute bottom-4 left-1/2 -translate-x-1/2 w-[90%] flex justify-between items-center bg-white/90 backdrop-blur p-2 rounded-full shadow-lg opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                      <button className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-colors">
                         <Heart className="w-4 h-4" />
                      </button>
                      <span className="text-xs font-bold uppercase tracking-wide text-slate-800">Thêm vào giỏ</span>
                      <button className="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-amber-600 transition-colors">
                         <ShoppingBag className="w-4 h-4" />
                      </button>
                  </div>
                </div>

                {/* Info */}
                <div className="text-center px-2">
                  <p className="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">{item.sub}</p>
                  <h3 className="text-xl font-serif text-slate-900 mb-2 group-hover:text-amber-700 transition-colors line-clamp-1">{item.name}</h3>
                  <p className="text-lg font-bold text-slate-900">{item.price}</p>
                </div>
              </div>
            ))}
          </div>

          <div className="text-center mt-16">
            <Link to="/menu" className="inline-block px-10 py-4 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-full hover:bg-amber-600 transition-all shadow-lg hover:shadow-amber-600/30">
              Xem tất cả sản phẩm
            </Link>
          </div>
        </div>
      </section>

      {/* === 4. SECTION: TIN TỨC (Journal Layout) === */}
      <section className="py-24 bg-white">
        <div className="max-w-[1600px] mx-auto px-6">
          <div className="text-center mb-20">
            <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">Rinn's Journal</span>
            <h2 className="text-4xl md:text-6xl font-serif text-slate-900">Tin Tức & Sự Kiện</h2>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
            {[
              {
                date: '29', month: 'OCT',
                title: 'Bánh Eclair: Nữ hoàng của các loại bánh ngọt Pháp',
                desc: 'Hành trình khám phá hương vị tinh tế của Eclair tại Đà Nẵng.',
                img: 'https://images.unsplash.com/photo-1603532648955-039310d9ed75?q=80&w=500'
              },
              {
                date: '22', month: 'OCT',
                title: 'Quy trình làm bánh Viennoiserie 3 ngày',
                desc: 'Tại sao Croissant tại Rinn\'s lại có 27 lớp giòn tan?',
                img: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=500'
              },
              {
                date: '29', month: 'SEP',
                title: 'Mousse Cake: Đỉnh cao nghệ thuật Haute Pâtisserie',
                desc: 'Sự cân bằng giữa vị ngọt, vị chua và kết cấu mềm mịn.',
                img: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?q=80&w=500'
              },
              {
                date: '18', month: 'SEP',
                title: 'Workshop: Tự tay làm bánh Danish cuối tuần',
                desc: 'Lớp học làm bánh dành cho người mới bắt đầu.',
                img: 'https://images.unsplash.com/photo-1509365465985-25d11c17e812?q=80&w=500'
              }
            ].map((news, idx) => (
              <div key={idx} className="group flex flex-col h-full cursor-pointer">
                {/* Image Wrapper */}
                <div className="relative overflow-hidden mb-6 rounded-lg aspect-[4/3] shadow-md">
                  <div className="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors z-10"></div>
                  <img src={news.img} alt={news.title} className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                  
                  {/* Date Badge: Thiết kế giống Bookmark */}
                  <div className="absolute top-0 left-4 bg-[#2F3E2E] text-white w-12 pt-3 pb-4 flex flex-col items-center justify-center shadow-lg rounded-b-lg border-t-4 border-amber-500 z-20">
                    <span className="text-lg font-bold font-serif leading-none">{news.date}</span>
                    <span className="text-[9px] font-bold tracking-widest mt-1 uppercase text-white/70">{news.month}</span>
                  </div>
                </div>
                
                {/* Content */}
                <div className="flex-1 flex flex-col">
                  <h3 className="text-xl font-serif font-bold text-slate-900 mb-3 leading-snug group-hover:text-amber-700 transition-colors">
                    {news.title}
                  </h3>
                  <p className="text-sm text-slate-500 mb-4 line-clamp-2 leading-relaxed">
                    {news.desc}
                  </p>
                  
                  {/* Read More Link */}
                  <div className="mt-auto pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-900 transition-colors">
                    <span>Đọc tiếp</span>
                    <div className="w-8 h-[1px] bg-slate-300 group-hover:bg-slate-900 transition-colors"></div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* === 5. SECTION: TRUYỀN THÔNG (Instagram Grid) === */}
      <section className="bg-[#F9F9F9] border-t border-slate-200">
        <div className="py-16 text-center">
          <div className="inline-flex items-center gap-2 mb-3">
             <div className="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
             <span className="text-slate-500 text-xs font-bold tracking-widest uppercase">Live Feed</span>
          </div>
          <h2 className="text-3xl font-serif text-slate-900 mb-2">@RinnsBakery.DN</h2>
          <p className="text-slate-500 text-sm">Chia sẻ khoảnh khắc ngọt ngào #RinnsMoment</p>
        </div>
        
        {/* Grid ảnh liền mạch */}
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-0.5">
          {[
            'https://images.unsplash.com/photo-1483695028939-0fa49a27963e?w=500',
            'https://images.unsplash.com/photo-1550614000-4b9519e09eb3?w=500',
            'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500',
            'https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=500',
            'https://images.unsplash.com/photo-1495147466023-ac5c588e2e94?w=500',
            'https://images.unsplash.com/photo-1509365465985-25d11c17e812?w=500',
          ].map((img, i) => (
            <a href="#" key={i} className="group relative aspect-square overflow-hidden block">
              <img src={img} alt="Instagram" className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
              <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                <Coffee className="w-6 h-6 mb-2" />
                <span className="font-bold text-xs tracking-widest border-b border-white pb-1">FOLLOW US</span>
              </div>
            </a>
          ))}
        </div>
      </section>

    </div>
  );
};

export default Home;