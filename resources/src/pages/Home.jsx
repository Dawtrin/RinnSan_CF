import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { 
  ArrowRight, Star, Clock, Coffee, Sparkles, 
  ChevronRight, ShoppingBag, Heart, ChefHat, Wheat, 
  MapPin, Phone, Mail, Award, Droplets, Flame 
} from 'lucide-react';
import { apiUrl, assetUrl } from '../config/api.js';

const Home = () => {
  // --- STATE QUẢN LÝ DỮ LIỆU ---
  const [bestSellers, setBestSellers] = useState([]);
  const [loading, setLoading] = useState(true);

  // --- GỌI API LẤY BEST SELLERS ---
  useEffect(() => {
    const fetchBestSellers = async () => {
      try {
        // Gọi API Public (Không cần token)
        const res = await fetch(apiUrl('/api/products/best-sellers'));
        const json = await res.json();
        
        if (json.success) {
          setBestSellers(json.data);
        }
      } catch (error) {
        console.error("Lỗi tải Best Sellers:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchBestSellers();
  }, []);

  // --- HÀM XỬ LÝ ĐƯỜNG DẪN ẢNH ---
  const getImageUrl = (imagePath) => {
    if (!imagePath) return 'https://via.placeholder.com/500x600?text=No+Image'; // Ảnh fallback
    if (imagePath.startsWith('http')) return imagePath; // Link tuyệt đối (Unsplash, Cloudinary...)
    
    // Xử lý link tương đối từ Server
    return assetUrl(imagePath);
  };

  return (
    <div className="bg-[#FAFAF8] min-h-screen font-sans selection:bg-amber-200 text-slate-800">
      
      {/* CSS ANIMATION */}
      <style>
        {`
          @keyframes marquee-left { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
          @keyframes marquee-right { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }
          .animate-marquee-left { animation: marquee-left 40s linear infinite; }
          .animate-marquee-right { animation: marquee-right 40s linear infinite; }
          .animate-slow-zoom { animation: slowZoom 20s infinite alternate; }
          @keyframes slowZoom { from { transform: scale(1); } to { transform: scale(1.1); } }
          .animate-fade-in-up { animation: fadeInUp 1s ease-out forwards; opacity: 0; }
          @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
          .delay-100 { animation-delay: 0.1s; }
          .delay-200 { animation-delay: 0.2s; }
          .delay-300 { animation-delay: 0.3s; }
        `}
      </style>

      {/* 1. HERO SECTION */}
      <section className="relative h-screen w-full overflow-hidden">
        <div className="absolute inset-0">
          <div className="absolute inset-0 bg-black/20 z-10"></div>
          <div className="absolute inset-0 bg-gradient-to-t from-[#FAFAF8] via-transparent to-transparent z-10"></div>
          <img 
            src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=2400&auto=format&fit=crop" 
            alt="Coffee Atmosphere" 
            className="w-full h-full object-cover animate-slow-zoom"
          />
        </div>
        <div className="relative z-20 h-full flex flex-col justify-center items-center text-center px-6 pt-20">
          <div className="inline-flex items-center gap-3 mb-8 animate-fade-in-up">
            <div className="h-[1px] w-12 bg-white/60"></div>
            <span className="text-white/90 text-[10px] md:text-xs font-bold tracking-[0.4em] uppercase shadow-sm">Est. 2024 • Da Nang Flagship</span>
            <div className="h-[1px] w-12 bg-white/60"></div>
          </div>
          <h1 className="text-7xl md:text-9xl font-serif text-white tracking-tighter mb-8 leading-[0.85] drop-shadow-2xl animate-fade-in-up delay-100">
            BREW <span className="font-sans font-light italic text-amber-300 mx-2">&</span> BAKE
          </h1>
          <p className="text-white/90 text-lg font-light max-w-xl mb-12 leading-relaxed animate-fade-in-up delay-200 drop-shadow-md">
            Nơi hương vị cà phê thượng hạng gặp gỡ nghệ thuật làm bánh thủ công.<br className="hidden md:block"/> Một bản giao hưởng đánh thức mọi giác quan.
          </p>
          <div className="flex gap-4 animate-fade-in-up delay-300">
            <Link to="/menu" className="group relative px-8 py-4 bg-white text-slate-900 text-xs font-bold tracking-widest overflow-hidden hover:shadow-lg transition-shadow">
              <span className="relative z-10 flex items-center gap-2 group-hover:gap-4 transition-all">KHÁM PHÁ MENU <ArrowRight className="w-4 h-4" /></span>
              <div className="absolute inset-0 bg-amber-400 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 ease-out"></div>
            </Link>
          </div>
        </div>
      </section>

      {/* 2. SECTION: VỀ CHÚNG TÔI */}
      <section className="py-32 px-6 md:px-12 max-w-[1600px] mx-auto">
         {/* ... (Giữ nguyên code phần About Us như file gốc của bạn) ... */}
         <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
          <div className="relative group">
            <div className="relative z-10 rounded-t-full overflow-hidden border-[1px] border-slate-200 shadow-2xl aspect-[3/4] lg:aspect-auto lg:h-[650px]">
              <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?q=80&w=1200" alt="Latte Art" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[2s]"/>
            </div>
            <div className="absolute -bottom-12 -left-8 z-50 w-48 h-48 border-8 border-white rounded-full shadow-xl overflow-hidden bg-gray-100">
               <img src="https://images.pexels.com/photos/894695/pexels-photo-894695.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Baker" className="w-full h-full object-cover"/>
            </div>
          </div>
          <div className="pl-0 lg:pl-4">
            <div className="flex items-center gap-4 mb-6">
               <div className="h-[1px] w-12 bg-amber-600"></div>
               <span className="text-amber-600 font-bold text-xs tracking-widest uppercase">Câu chuyện thương hiệu</span>
            </div>
            <h2 className="text-4xl md:text-6xl font-serif text-slate-900 mb-8 leading-[1.1]">Từ đam mê nhỏ bé <br/><span className="italic text-slate-400 font-light">đến biểu tượng Đà Nẵng.</span></h2>
            <div className="prose prose-lg prose-slate text-slate-600 mb-10 text-justify">
              <p className="mb-4">Tọa lạc ngay tại trung tâm đường Bạch Đằng thơ mộng, <strong className="text-slate-900 font-bold underline decoration-amber-300">Rinn's Bakery</strong> không chỉ là một tiệm bánh, mà là một điểm đến văn hóa.</p>
            </div>
            <div className="grid grid-cols-2 gap-y-8 gap-x-4 border-t border-slate-200 pt-8 mb-10">
              <div className="flex items-start gap-4">
                <div className="p-3 bg-amber-100 text-amber-700 rounded-full"><Clock className="w-5 h-5"/></div>
                <div><h4 className="font-bold text-slate-900 text-sm uppercase tracking-wide mb-1">Tươi Mỗi Ngày</h4><p className="text-xs text-slate-500">Bánh nướng mới mỗi 4 giờ</p></div>
              </div>
              <div className="flex items-start gap-4">
                <div className="p-3 bg-amber-100 text-amber-700 rounded-full"><Wheat className="w-5 h-5"/></div>
                <div><h4 className="font-bold text-slate-900 text-sm uppercase tracking-wide mb-1">Nguyên Liệu Gốc</h4><p className="text-xs text-slate-500">Bột Pháp & Bơ Elle & Vire</p></div>
              </div>
            </div>
            <Link to="/about" className="group inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-900 border-b-2 border-slate-900 pb-1 hover:text-amber-600 hover:border-amber-600 transition-all">Đọc toàn bộ câu chuyện <ArrowRight className="w-4 h-4 transform group-hover:translate-x-1 transition-transform"/></Link>
          </div>
        </div>
      </section>

      {/* MARQUEE 1 */}
      <div className="bg-[#1a1d24] py-5 overflow-hidden border-t border-slate-800 select-none relative z-20">
        <div className="whitespace-nowrap flex animate-marquee-right">
          {[...Array(2)].map((_, i) => (
             <React.Fragment key={i}>
                <span className="text-2xl md:text-3xl font-black text-slate-500 mx-4 uppercase italic tracking-tighter">
                  SPECIALTY COFFEE <span className="text-amber-600 mx-2">✦</span> COLD BREW <span className="text-amber-600 mx-2">✦</span> MATCHA LATTE <span className="text-amber-600 mx-2">✦</span> ARABICA BEANS <span className="text-amber-600 mx-2">✦</span>
                </span>
             </React.Fragment>
          ))}
        </div>
      </div>

      {/* 3. SECTION: TIÊU CHUẨN */}
      <section className="py-24 bg-[#111318] text-white relative overflow-hidden">
        <div className="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1610632380989-680fe40816c6?q=80&w=2000')] bg-cover bg-fixed bg-center opacity-10"></div>
        <div className="max-w-[1600px] mx-auto px-6 relative z-10">
          <div className="text-center mb-16">
             <span className="text-amber-500 font-bold text-xs tracking-[0.3em] uppercase mb-4 block animate-pulse">The Art of Making</span>
             <h2 className="text-4xl md:text-6xl font-serif text-white mb-6">TIÊU CHUẨN <span className="italic text-slate-500">RINN'S</span></h2>
             <p className="text-slate-400 max-w-2xl mx-auto font-light leading-relaxed">Tại Rinn's, mỗi sản phẩm không chỉ là thức ăn, mà là kết tinh của quy trình chế biến nghiêm ngặt.</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
             <div className="bg-white/5 backdrop-blur-md p-10 border border-white/10 group hover:bg-white/10 transition-all duration-500">
                <div className="w-16 h-16 bg-amber-500/10 rounded-full flex items-center justify-center mb-8 group-hover:bg-amber-500 transition-colors"><Award className="w-8 h-8 text-amber-500 group-hover:text-white transition-colors" /></div>
                <h3 className="text-2xl font-serif text-white mb-4">Premium Quality</h3><p className="text-slate-400 text-sm leading-relaxed">Sử dụng 100% bơ nhập khẩu từ Pháp.</p>
             </div>
             <div className="bg-white/5 backdrop-blur-md p-10 border border-white/10 group hover:bg-white/10 transition-all duration-500 md:-translate-y-4">
                <div className="w-16 h-16 bg-amber-500/10 rounded-full flex items-center justify-center mb-8 group-hover:bg-amber-500 transition-colors"><Droplets className="w-8 h-8 text-amber-500 group-hover:text-white transition-colors" /></div>
                <h3 className="text-2xl font-serif text-white mb-4">Fresh Daily</h3><p className="text-slate-400 text-sm leading-relaxed">Bánh được nướng mới liên tục mỗi 4 giờ.</p>
             </div>
             <div className="bg-white/5 backdrop-blur-md p-10 border border-white/10 group hover:bg-white/10 transition-all duration-500">
                <div className="w-16 h-16 bg-amber-500/10 rounded-full flex items-center justify-center mb-8 group-hover:bg-amber-500 transition-colors"><Flame className="w-8 h-8 text-amber-500 group-hover:text-white transition-colors" /></div>
                <h3 className="text-2xl font-serif text-white mb-4">Master Craft</h3><p className="text-slate-400 text-sm leading-relaxed">Đội ngũ thợ làm bánh và Barista giàu kinh nghiệm.</p>
             </div>
          </div>
        </div>
      </section>

      {/* MARQUEE 2 */}
      <div className="bg-[#1a1d24] py-5 overflow-hidden border-b border-slate-800 select-none relative z-20">
        <div className="whitespace-nowrap flex animate-marquee-left">
           {[...Array(2)].map((_, i) => (<React.Fragment key={i}><span className="text-2xl md:text-3xl font-black text-slate-500 mx-4 uppercase italic tracking-tighter">FRESH CROISSANTS <span className="text-amber-600 mx-2">✦</span> FRENCH BUTTER <span className="text-amber-600 mx-2">✦</span> HANDMADE CAKES <span className="text-amber-600 mx-2">✦</span></span></React.Fragment>))}
        </div>
      </div>

      {/* === 4. SECTION: SẢN PHẨM BÁN CHẠY (DATA THẬT) === */}
      <section className="py-24 bg-[#FAFAF8] border-b border-slate-100">
        <div className="max-w-[1600px] mx-auto px-6">
          <div className="text-center mb-16">
            <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">Rinn's Favorites</span>
            <h2 className="text-4xl md:text-5xl font-serif text-slate-900 mb-4">Best Sellers</h2>
            <div className="w-20 h-1 bg-amber-400 mx-auto rounded-full"></div>
          </div>

          {loading ? (
             <div className="flex justify-center items-center h-64">
                <div className="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
             </div>
          ) : (
             <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 gap-y-12">
               {bestSellers && bestSellers.length > 0 ? bestSellers.map((item) => (
                 <div key={item.id} className="group cursor-pointer">
                   <div className="relative overflow-hidden rounded-2xl mb-5 bg-white aspect-[4/5] border border-slate-100 shadow-sm group-hover:shadow-xl transition-all duration-500">
                     <img 
                       src={getImageUrl(item.image)} 
                       alt={item.name} 
                       className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                       onError={(e) => { e.target.src = 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=500'; }}
                     />
                     {item.tag && (
                       <div className="absolute top-4 left-4 bg-white/95 backdrop-blur px-3 py-1 text-[10px] font-bold tracking-widest uppercase text-slate-900 border border-slate-200 shadow-sm rounded-sm z-10">
                         {item.tag}
                       </div>
                     )}
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
                   <div className="text-center px-2">
                     <p className="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">{item.sales} đã bán</p>
                     <h3 className="text-xl font-serif text-slate-900 mb-2 group-hover:text-amber-700 transition-colors line-clamp-1">{item.name}</h3>
                     <p className="text-lg font-bold text-slate-900">{Number(item.price).toLocaleString('vi-VN')}đ</p>
                   </div>
                 </div>
               )) : (
                 <div className="col-span-full text-center text-slate-400 py-10">
                    <p className="mb-2 text-lg font-medium">Đang cập nhật danh sách Best Sellers...</p>
                    <p className="text-xs">Hãy đặt hàng và hoàn thành đơn để sản phẩm xuất hiện tại đây.</p>
                 </div>
               )}
             </div>
          )}

          <div className="text-center mt-16">
            <Link to="/menu" className="inline-block px-10 py-4 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-full hover:bg-amber-600 transition-all shadow-lg hover:shadow-amber-600/30">
              Xem tất cả sản phẩm
            </Link>
          </div>
        </div>
      </section>

      {/* 5. MENU BENTO GRID */}
      <section className="py-24 bg-[#FAFAF8] px-6">
        <div className="max-w-[1600px] mx-auto">
             {/* ... (Giữ nguyên phần Bento Grid) ... */}
          <div className="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
              <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">Discover Taste</span>
              <h2 className="text-4xl md:text-5xl font-serif text-slate-900 leading-tight">Danh mục <span className="italic text-slate-400">Yêu thích</span></h2>
            </div>
            <Link to="/menu" className="hidden md:flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-900 hover:text-amber-600 transition-colors">Xem toàn bộ Menu <ArrowRight className="w-4 h-4" /></Link>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 grid-rows-3 lg:grid-rows-2 gap-4 h-auto lg:h-[600px]">
                {/* Giữ nguyên nội dung Bento Grid */}
            <div className="lg:col-span-2 lg:row-span-2 relative group rounded-3xl overflow-hidden cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500">
              <div className="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors z-10"></div>
              <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=1200" alt="Morning Combo" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
              <div className="absolute bottom-0 left-0 p-8 z-20 w-full bg-gradient-to-t from-black/80 to-transparent">
                <div className="flex justify-between items-end">
                  <div><span className="px-3 py-1 bg-amber-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full mb-3 inline-block">Best Seller</span><h3 className="text-3xl font-serif text-white mb-2">Morning Combo</h3><p className="text-white/80 text-sm max-w-md line-clamp-2">Khởi đầu ngày mới hoàn hảo với 1 cà phê máy và 1 bánh Croissant bơ Pháp thượng hạng.</p></div>
                  <div className="w-12 h-12 bg-white rounded-full flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all"><ArrowRight className="w-5 h-5" /></div>
                </div>
              </div>
            </div>
            <div className="lg:col-span-1 lg:row-span-2 relative group rounded-3xl overflow-hidden cursor-pointer shadow-sm hover:shadow-xl transition-all duration-500 bg-[#1e1e1e]">
               <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=600" alt="Specialty Coffee" className="w-full h-full object-cover opacity-80 group-hover:opacity-60 transition-opacity duration-500"/>
              <div className="absolute top-6 left-6 z-20"><h3 className="text-2xl font-serif text-white leading-none mb-1">Specialty<br/>Coffee</h3></div>
              <div className="absolute bottom-6 left-6 z-20"><p className="text-amber-400 text-xs font-bold uppercase tracking-widest mb-2">Từ 45.000đ</p><Link to="/menu/coffee" className="text-white text-sm underline underline-offset-4 decoration-amber-500 hover:text-amber-400">Khám phá (12 món)</Link></div>
            </div>
            <div className="relative group rounded-3xl overflow-hidden cursor-pointer shadow-sm hover:shadow-xl transition-all duration-500 bg-[#F5E6D3]">
               <div className="absolute right-0 top-0 w-1/2 h-full"><img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?q=80&w=400" alt="Cake" className="w-full h-full object-cover"/></div>
               <div className="p-6 relative z-10 w-2/3"><h3 className="text-xl font-serif text-slate-900 mb-2">Sweet<br/>Cakes</h3><p className="text-xs text-slate-500 mb-4">Tiramisu, Mousse & more</p><div className="w-8 h-8 rounded-full border border-slate-900 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors"><ChevronRight className="w-4 h-4"/></div></div>
            </div>
            <div className="relative group rounded-3xl overflow-hidden cursor-pointer shadow-sm hover:shadow-xl transition-all duration-500 bg-amber-500 flex flex-col justify-center items-center text-center p-6">
               <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
               <div className="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm animate-bounce-slow"><Star className="w-6 h-6 text-white" /></div>
               <h3 className="text-xl font-serif text-white mb-1">Membership</h3><p className="text-white/80 text-xs mb-4">Giảm 10% cho thành viên mới</p><button className="bg-white text-amber-600 px-5 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-colors shadow-lg">Đăng ký ngay</button>
            </div>
          </div>
          <div className="mt-8 text-center md:hidden">
             <Link to="/menu" className="inline-block px-8 py-3 bg-slate-100 text-slate-900 rounded-full text-xs font-bold uppercase tracking-widest">Xem toàn bộ Menu</Link>
          </div>
        </div>
      </section>

      {/* 6. NEWS & SOCIAL */}
      <section className="py-24 bg-white border-t border-slate-100">
        <div className="max-w-[1600px] mx-auto px-6">
          <div className="text-center mb-20"><span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">Rinn's Journal</span><h2 className="text-4xl md:text-6xl font-serif text-slate-900">Tin Tức & Sự Kiện</h2></div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
            {[
              { date: '29', month: 'OCT', title: 'Bánh Eclair: Nữ hoàng của các loại bánh ngọt Pháp', desc: 'Hành trình khám phá hương vị tinh tế của Eclair tại Đà Nẵng.', img: 'https://images.unsplash.com/photo-1603532648955-039310d9ed75?q=80&w=500' },
              { date: '22', month: 'OCT', title: 'Quy trình làm bánh Viennoiserie 3 ngày', desc: 'Tại sao Croissant tại Rinn\'s lại có 27 lớp giòn tan?', img: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=500' },
              { date: '29', month: 'SEP', title: 'Mousse Cake: Đỉnh cao nghệ thuật Haute Pâtisserie', desc: 'Sự cân bằng giữa vị ngọt, vị chua và kết cấu mềm mịn.', img: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?q=80&w=500' },
              { date: '18', month: 'SEP', title: 'Workshop: Tự tay làm bánh Danish cuối tuần', desc: 'Lớp học làm bánh dành cho người mới bắt đầu.', img: 'https://images.unsplash.com/photo-1509365465985-25d11c17e812?q=80&w=500' }
            ].map((news, idx) => (
              <div key={idx} className="group flex flex-col h-full cursor-pointer">
                <div className="relative overflow-hidden mb-6 rounded-lg aspect-[4/3] shadow-md">
                  <div className="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors z-10"></div>
                  <img src={news.img} alt={news.title} className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                  <div className="absolute top-0 left-4 bg-[#2F3E2E] text-white w-12 pt-3 pb-4 flex flex-col items-center justify-center shadow-lg rounded-b-lg border-t-4 border-amber-500 z-20">
                    <span className="text-lg font-bold font-serif leading-none">{news.date}</span><span className="text-[9px] font-bold tracking-widest mt-1 uppercase text-white/70">{news.month}</span>
                  </div>
                </div>
                <div className="flex-1 flex flex-col">
                  <h3 className="text-xl font-serif font-bold text-slate-900 mb-3 leading-snug group-hover:text-amber-700 transition-colors">{news.title}</h3>
                  <p className="text-sm text-slate-500 mb-4 line-clamp-2 leading-relaxed">{news.desc}</p>
                  <div className="mt-auto pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-900 transition-colors"><span>Đọc tiếp</span><div className="w-8 h-[1px] bg-slate-300 group-hover:bg-slate-900 transition-colors"></div></div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* FOOTER */}
      <section className="bg-[#F9F9F9] border-t border-slate-200">
        <div className="py-16 text-center">
          <div className="inline-flex items-center gap-2 mb-3"><div className="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div><span className="text-slate-500 text-xs font-bold tracking-widest uppercase">Live Feed</span></div>
          <h2 className="text-3xl font-serif text-slate-900 mb-2">@RinnsBakery.DN</h2><p className="text-slate-500 text-sm">Chia sẻ khoảnh khắc ngọt ngào #RinnsMoment</p>
        </div>
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-0.5">
          {['https://images.unsplash.com/photo-1483695028939-0fa49a27963e?w=500','https://images.unsplash.com/photo-1550614000-4b9519e09eb3?w=500','https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500','https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=500','https://images.unsplash.com/photo-1495147466023-ac5c588e2e94?w=500','https://images.unsplash.com/photo-1509365465985-25d11c17e812?w=500'].map((img, i) => (
            <a href="#" key={i} className="group relative aspect-square overflow-hidden block">
              <img src={img} alt="IG" className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
              <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white"><Coffee className="w-6 h-6 mb-2" /><span className="font-bold text-xs tracking-widest border-b border-white pb-1">FOLLOW US</span></div>
            </a>
          ))}
        </div>
      </section>

      {/* ... (Giữ nguyên phần News & Footer) ... */}
    </div>
  );
};

export default Home;

