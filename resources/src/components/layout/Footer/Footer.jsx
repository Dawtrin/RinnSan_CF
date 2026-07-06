import React from 'react';
import { Link } from 'react-router-dom';
import { 
  ArrowRight, ArrowUpRight, 
  Instagram, Facebook, Twitter, 
  MapPin, Clock, Shield 
} from 'lucide-react';

const Footer = () => {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-[#0b0c0f] text-slate-300 font-sans border-t border-white/10 relative z-10">
      
      <div className="max-w-[1920px] mx-auto flex flex-col lg:flex-row min-h-[600px]">
        
        {/* ================= CỘT TRÁI: NỘI DUNG (60%) ================= */}
        <div className="w-full lg:w-[60%] flex flex-col border-r border-white/10">
          
          {/* 1. TOP SECTION */}
          <div className="p-12 md:p-16 border-b border-white/10 flex-grow">
            <Link to="/" className="inline-block mb-12">
               <span className="text-6xl md:text-8xl font-black tracking-tighter text-white leading-[0.8]">
                 RINN'S.
               </span>
               <span className="block text-xs font-bold tracking-[0.5em] text-amber-600 mt-2 uppercase pl-2">
                 Est. 2024 — Đà Nẵng
               </span>
            </Link>

            <div className="max-w-xl mt-8">
              <label className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4 block">
                Đăng ký nhận ưu đãi
              </label>
              <div className="relative group">
                <input 
                  type="email" 
                  placeholder="Nhập email của bạn..." 
                  className="w-full bg-transparent border-b border-slate-700 py-4 text-xl text-white placeholder-slate-600 focus:outline-none focus:border-amber-600 transition-colors rounded-none"
                />
                <button className="absolute right-0 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-amber-600 hover:text-white transition-colors uppercase text-xs font-bold tracking-widest flex items-center gap-2">
                  Gửi ngay <ArrowRight className="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>

          {/* 2. BOTTOM SECTION: GRID LINKS */}
          <div className="grid grid-cols-2 md:grid-cols-3">
             
             {/* Cột 1: DANH MỤC SẢN PHẨM */}
             <div className="p-8 md:p-12 border-r border-b md:border-b-0 border-white/10">
                <h4 className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-6">Danh mục sản phẩm</h4>
                <ul className="space-y-3">
                  {[
                    'Cà phê & Espresso', 
                    'Trà & Trà sữa', 
                    'Ice Blended (Đá xay)', 
                    'Bánh ngọt & Pastry', 
                    'Bánh mì Việt Nam', 
                    'Bánh Âu & Tráng miệng', 
                    'Nước ép & Sinh tố'
                  ].map(item => (
                    <li key={item}>
                      <Link to="/menu" className="block text-sm text-slate-300 hover:text-white transition-colors hover:translate-x-1 duration-300">
                        {item}
                      </Link>
                    </li>
                  ))}
                </ul>
             </div>

             {/* Cột 2: Hỗ trợ */}
             <div className="p-8 md:p-12 border-r border-white/10">
                <h4 className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-6">Hỗ trợ khách hàng</h4>
                <ul className="space-y-4">
                  {['Chính sách giao hàng', 'Đổi trả & Hoàn tiền', 'Bảo mật thông tin', 'Liên hệ hợp tác', 'Tuyển dụng'].map(item => (
                    <li key={item}>
                      <Link to="#" className="block text-sm text-slate-300 hover:text-white transition-colors hover:translate-x-1 duration-300">
                        {item}
                      </Link>
                    </li>
                  ))}
                </ul>
             </div>

             {/* Cột 3: Socials & Copyright */}
             <div className="p-8 md:p-12 flex flex-col justify-between">
                <div>
                   <h4 className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-6">Mạng xã hội</h4>
                   <div className="flex gap-4">
                      {[Facebook, Instagram, Twitter].map((Icon, i) => (
                        <a key={i} href="#" className="text-slate-400 hover:text-amber-500 transition-colors">
                          <Icon className="w-5 h-5" />
                        </a>
                      ))}
                   </div>
                </div>
                <div className="mt-8 md:mt-0 space-y-4">
                   <p className="text-[10px] text-slate-600 uppercase tracking-wide">
                     © {currentYear} Rinn's Bakery.<br/>All rights reserved.
                   </p>
                   
                   {/* --- NÚT ADMIN Ở ĐÂY --- */}
                   <Link 
                     to="/admin/login" 
                     className="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-700 hover:text-amber-600 transition-colors group"
                   >
                     <Shield className="w-3 h-3 group-hover:text-amber-600 transition-colors" />
                     <span>Dành cho nhân viên</span>
                   </Link>
                </div>
             </div>
          </div>
        </div>


        {/* ================= CỘT PHẢI: ẢNH (40%) ================= */}
        <div className="w-full lg:w-[40%] relative group bg-slate-900 overflow-hidden border-b lg:border-b-0 border-white/10 min-h-[400px] lg:min-h-auto">
          
          <div 
             className="absolute inset-0 bg-cover bg-center opacity-80 group-hover:opacity-100 transition-opacity duration-700"
             style={{ backgroundImage: "url('https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1600&auto=format&fit=crop')" }}
          ></div>
          
          <div className="absolute inset-0 bg-gradient-to-t from-[#0b0c0f] via-black/10 to-transparent"></div>

          <div className="absolute bottom-0 left-0 w-full p-12">
             <div className="mb-8">
               <div className="inline-flex items-center gap-2 px-3 py-1 border border-white/20 backdrop-blur-md rounded-full text-white text-[10px] font-bold uppercase tracking-widest mb-4">
                 <span className="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                 Open Now
               </div>
               <h3 className="text-4xl font-black text-white leading-tight mb-2 drop-shadow-lg">
                 FLAGSHIP STORE
               </h3>
               <p className="text-white/90 font-medium border-l-2 border-amber-500 pl-4 drop-shadow-md">
                 Trải nghiệm không gian nghệ thuật tại Đà Nẵng.
               </p>
             </div>

             <ul className="space-y-4 pt-8 border-t border-white/20">
               <li className="flex items-start gap-4">
                 <MapPin className="w-5 h-5 text-amber-500 shrink-0 mt-0.5 drop-shadow-sm" />
                 <div>
                   <span className="text-white font-bold block drop-shadow-md">123 Đường Bạch Đằng</span>
                   <span className="text-white/80 text-sm drop-shadow-md">Quận Hải Châu, TP. Đà Nẵng</span>
                 </div>
               </li>
               <li className="flex items-start gap-4">
                 <Clock className="w-5 h-5 text-amber-500 shrink-0 mt-0.5 drop-shadow-sm" />
                 <div>
                   <span className="text-white font-bold block drop-shadow-md">07:00 - 22:00</span>
                   <span className="text-white/80 text-sm drop-shadow-md">Mở cửa tất cả các ngày trong tuần</span>
                 </div>
               </li>
               <li className="flex items-center gap-4 pt-4">
                  <Link to="/menu" className="h-12 px-6 bg-white text-black hover:bg-amber-600 hover:text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2 transition-all shadow-lg">
  ORDER ONLINE
</Link>
                  <Link to="/locations" className="h-12 w-12 border border-white/50 bg-black/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white hover:text-black transition-all rounded-full">
                    <ArrowUpRight className="w-5 h-5" />
                  </Link>
               </li>
             </ul>
          </div>
        </div>

      </div>
    </footer>
  );
};

export default Footer;