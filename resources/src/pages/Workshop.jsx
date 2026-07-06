import React, { useState } from 'react';
import { 
  Calendar, Clock, Users, MapPin, ArrowRight, 
  CheckCircle2, Star, ChefHat, Heart, Instagram, Play,
  Coffee, Sparkles, HelpCircle, ChevronDown, ChevronUp
} from 'lucide-react';

const Workshop = () => {
  const [openFaq, setOpenFaq] = useState(null);

  // --- MOCK DATA ---
  const WORKSHOPS = [
    {
      id: 1,
      title: "Masterclass: Croissant Ngàn Lớp",
      level: "Nâng cao",
      date: "15/11/2024",
      time: "09:00 - 14:00",
      price: 1500000,
      slots: 8,
      enrolled: 6,
      image: "https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=800&auto=format&fit=crop",
      desc: "Chinh phục kỹ thuật cán bột thủ công (Lamination) để tạo ra cấu trúc tổ ong hoàn hảo cho bánh sừng bò Pháp.",
      instructor: "Chef Minh Long"
    },
    {
      id: 2,
      title: "Korean Flower Cake: Kem Bơ",
      level: "Cơ bản",
      date: "18/11/2024",
      time: "14:00 - 17:00",
      price: 850000,
      slots: 10,
      enrolled: 2,
      image: "https://images.unsplash.com/photo-1535141192574-5d4897c12636?q=80&w=800&auto=format&fit=crop",
      desc: "Học cách pha màu Pastel chuẩn Hàn và kỹ thuật bắt hoa kem bơ trong veo, tinh tế như hoa thật.",
      instructor: "Ms. Sarah Nguyen"
    },
    {
      id: 3,
      title: "Workshop: Tiramisu & Wine",
      level: "Thư giãn",
      date: "20/11/2024",
      time: "18:00 - 20:30",
      price: 650000,
      slots: 12,
      enrolled: 12, // Full
      image: "https://images.unsplash.com/photo-1571115177098-24ec42ed204d?q=80&w=800&auto=format&fit=crop",
      desc: "Một buổi tối chill đúng nghĩa: Vừa làm bánh Tiramisu truyền thống, vừa thưởng thức rượu vang Ý.",
      instructor: "Barista Tuan Anh"
    },
    {
      id: 4,
      title: "Cocktail Mixology: Cơ Bản",
      level: "Cơ bản",
      date: "25/11/2024",
      time: "19:00 - 22:00",
      price: 1500000,
      slots: 8,
      enrolled: 3,
      image: "https://images.unsplash.com/photo-1470337458703-46ad1756a187?w=800&auto=format&fit=crop",
      desc: "Học các công thức Cocktail cổ điển: Mojito, Old Fashioned, Martini và kỹ thuật shake, stir chuẩn Bartender.",
      instructor: "Bartender Minh Long"
    },
    {
     id: 5,
      title: "Smoothie Bowl & Healthy Drinks",
      level: "Gia đình",
      date: "28/11/2024",
      time: "09:00 - 11:30",
      price: 650000,
      slots: 15,
      enrolled: 5,
      image: "https://images.unsplash.com/photo-1590301157890-4810ed352733?w=800&auto=format&fit=crop",
      desc: "Tạo ra những ly smoothie bowl đầy màu sắc, nước ép detox và đồ uống healthy cho cả gia đình.",
      instructor: "Ms. Sarah Nguyen"
    },
    {
      id: 6,
      title: "Tea Sommelier: Nghệ Thuật Trà",
      level: "Chuyên sâu",
      date: "01/12/2024",
      time: "14:00 - 18:00",
      price: 1800000,
      slots: 6,
      enrolled: 1,
      image: "https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=800&auto=format&fit=crop",
      desc: "Khám phá thế giới trà từ Oolong, Pu-erh đến các loại trà hiếm, kỹ thuật pha trà Công Phu và tea pairing.",
      instructor: "Tea Master Linh"
    }
  ];

  const FAQS = [
    { q: "Tôi chưa từng làm bánh thì có tham gia được không?", a: "Hoàn toàn được! 80% học viên tại Rinn's là người mới bắt đầu. Các giảng viên sẽ cầm tay chỉ việc từng bước một." },
    { q: "Học phí đã bao gồm nguyên liệu chưa?", a: "Đã bao gồm tất cả: Nguyên liệu cao cấp, tạp dề, dụng cụ, công thức mang về và cả Tea-break giữa giờ." },
    { q: "Tôi có được mang bánh thành phẩm về không?", a: "Chắc chắn rồi! Bạn sẽ được đóng gói thành phẩm trong hộp quà xinh xắn của Rinn's để mang về tặng người thân." },
  ];

  return (
    <div className="bg-[#FAFAF8] min-h-screen font-sans text-slate-800 selection:bg-amber-100 selection:text-amber-900">
      
      <style>
        {`@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap');
          .font-serif { font-family: 'Playfair Display', serif; }
          .font-sans { font-family: 'Inter', sans-serif; }`}
      </style>

     {/* === 1. HERO SECTION === */}
      <section className="relative h-[70vh] md:h-[85vh] w-full overflow-hidden flex items-center justify-center text-center">
        <div className="absolute inset-0">
           <div className="absolute inset-0 bg-black/40 z-10"></div>
           <img 
             src="https://images.unsplash.com/photo-1511920170033-f8396924c348?q=80&w=2400&auto=format&fit=crop" 
             alt="Barista Workshop Background" 
             className="w-full h-full object-cover animate-slow-zoom"
           />
        </div>
        <div className="relative z-20 px-6 max-w-5xl mx-auto text-white">
           <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/20 bg-white/10 backdrop-blur-md text-[10px] font-bold uppercase tracking-[0.3em] mb-8 animate-fade-in-up">
              <Sparkles className="w-3 h-3 text-amber-400 fill-amber-400" /> Rinn's Academy
           </div>
           <h1 className="text-5xl md:text-7xl lg:text-8xl font-serif font-medium mb-8 leading-[1.1] drop-shadow-2xl">
             Nơi đam mê <br/>
             <span className="italic text-amber-300 font-normal">hóa thành nghệ thuật.</span>
           </h1>
           <p className="text-lg md:text-xl text-white/90 font-light mb-12 max-w-2xl mx-auto leading-relaxed">
             Hơn cả một lớp học, Rinn's Academy là không gian để bạn sống chậm lại, tận hưởng mùi bơ thơm lừng và tự tay tạo nên những chiếc bánh hạnh phúc.
           </p>
           <button className="px-12 py-4 bg-white text-slate-900 font-bold rounded-full hover:bg-amber-500 hover:text-white transition-all shadow-xl hover:shadow-amber-500/20 transform hover:-translate-y-1">
             Khám phá lịch học
           </button>
        </div>
      </section>
      {/* === 2. TỔNG QUAN === */}
      <section className="py-24 px-6 md:px-12 max-w-[1600px] mx-auto">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
           <div className="relative">
              <div className="grid grid-cols-2 gap-4">
                 <img src="https://images.unsplash.com/photo-1507048331197-7d4ac70811cf?q=80&w=800&auto=format&fit=crop" className="rounded-3xl w-full h-64 object-cover -translate-y-8 shadow-xl" alt="Studio 1" />
                 <img src="https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=800&auto=format&fit=crop" className="rounded-3xl w-full h-64 object-cover translate-y-8 shadow-xl" alt="Studio 2" />
              </div>
              <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-full shadow-2xl border border-slate-100 flex flex-col items-center justify-center w-40 h-40 text-center z-10">
                 <span className="text-4xl font-serif font-bold text-amber-500">5+</span>
                 <span className="text-[10px] uppercase font-bold tracking-widest text-slate-400 mt-1">Năm đào tạo</span>
              </div>
           </div>

           <div className="lg:pl-10">
              <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-4 block">Về Rinn's Academy</span>
              <h2 className="text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">
                Không gian bếp tiêu chuẩn <br/>
                <span className="italic text-slate-400">dành cho người yêu bánh.</span>
              </h2>
              <div className="prose prose-lg text-slate-500 mb-8 text-justify leading-relaxed">
                 <p className="mb-4">
                   Tọa lạc tại tầng 2 của Rinn's Bakery, Studio của chúng tôi được thiết kế như một căn bếp gia đình ấm cúng nhưng trang bị đầy đủ máy móc chuẩn công nghiệp từ Ý và Đức.
                 </p>
                 <p>
                   Tại đây, chúng tôi không dạy công thức "công nghiệp". Chúng tôi dạy bạn **cảm nhận nguyên liệu**, **hiểu về nhiệt độ**, và **tôn trọng quy trình**.
                 </p>
              </div>
              <div className="grid grid-cols-3 gap-6 pt-8 border-t border-slate-200">
                 <div className="text-center md:text-left">
                    <ChefHat className="w-8 h-8 text-slate-800 mb-3 mx-auto md:mx-0" />
                    <h4 className="font-bold text-sm mb-1">Giảng viên Master</h4>
                    <p className="text-xs text-slate-400">10+ năm kinh nghiệm</p>
                 </div>
                 <div className="text-center md:text-left">
                    <Star className="w-8 h-8 text-slate-800 mb-3 mx-auto md:mx-0" />
                    <h4 className="font-bold text-sm mb-1">Dụng cụ Xịn sò</h4>
                    <p className="text-xs text-slate-400">KitchenAid, Unox...</p>
                 </div>
                 <div className="text-center md:text-left">
                    <Coffee className="w-8 h-8 text-slate-800 mb-3 mx-auto md:mx-0" />
                    <h4 className="font-bold text-sm mb-1">Tea-break Free</h4>
                    <p className="text-xs text-slate-400">Thư giãn giữa giờ</p>
                 </div>
              </div>
           </div>
        </div>
      </section>

      {/* === 3. DANH SÁCH LỚP HỌC === */}
      <section className="py-24 bg-white" id="schedule">
        <div className="max-w-[1600px] mx-auto px-6">
           <div className="text-center mb-16">
              <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">Lịch Khai Giảng Tháng 11</span>
              <h2 className="text-4xl md:text-6xl font-serif text-slate-900 mb-4">Lớp Học Sắp Tới</h2>
              <div className="w-24 h-1 bg-amber-200 mx-auto rounded-full"></div>
           </div>

           <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {WORKSHOPS.map((ws) => (
                <div key={ws.id} className="group flex flex-col h-full bg-[#FAFAF8] rounded-[2rem] overflow-hidden border border-slate-100 hover:border-amber-200 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                   <div className="relative h-64 overflow-hidden shrink-0">
                      <div className="absolute top-4 left-4 z-10 flex gap-2">
                         <span className="px-3 py-1 bg-white/90 backdrop-blur text-[10px] font-bold rounded-lg uppercase tracking-wide text-slate-900 shadow-sm border border-slate-100">
                           {ws.level}
                         </span>
                         {ws.slots - ws.enrolled <= 2 && ws.slots > ws.enrolled && (
                           <span className="px-3 py-1 bg-rose-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wide shadow-sm animate-pulse">
                             Sắp đầy
                           </span>
                         )}
                         {ws.slots === ws.enrolled && (
                           <span className="px-3 py-1 bg-slate-800 text-white text-[10px] font-bold rounded-lg uppercase tracking-wide shadow-sm">
                             Hết chỗ
                           </span>
                         )}
                      </div>
                      <img src={ws.image} alt={ws.title} className="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                   </div>

                   <div className="p-8 flex flex-col flex-1">
                      <div className="mb-4">
                         <h3 className="text-2xl font-serif font-bold text-slate-900 mb-2 group-hover:text-amber-700 transition-colors line-clamp-2 min-h-[4rem]">
                           {ws.title}
                         </h3>
                         <p className="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <ChefHat className="w-3 h-3" /> Mentor: {ws.instructor}
                         </p>
                      </div>
                      <p className="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed border-l-2 border-slate-200 pl-4">
                        {ws.desc}
                      </p>
                      <div className="space-y-3 mb-8 mt-auto">
                         <div className="flex items-center gap-3 text-sm text-slate-600">
                            <Calendar className="w-4 h-4 text-amber-500" /> <span>{ws.date}</span>
                         </div>
                         <div className="flex items-center gap-3 text-sm text-slate-600">
                            <Clock className="w-4 h-4 text-amber-500" /> <span>{ws.time}</span>
                         </div>
                         <div className="flex items-center gap-3 text-sm text-slate-600">
                            <MapPin className="w-4 h-4 text-amber-500" /> <span>Rinn's Studio (Tầng 2)</span>
                         </div>
                      </div>
                      <div className="mt-auto pt-6 border-t border-slate-200 flex items-center justify-between">
                         <div>
                            <span className="block text-[10px] text-slate-400 font-bold uppercase">Học phí</span>
                            <span className="text-xl font-serif font-bold text-slate-900">{ws.price.toLocaleString()}<span className="text-sm font-sans text-slate-400">đ</span></span>
                         </div>
                         <button 
                           disabled={ws.slots === ws.enrolled}
                           className={`px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all ${
                             ws.slots === ws.enrolled 
                               ? 'bg-slate-200 text-slate-400 cursor-not-allowed' 
                               : 'bg-slate-900 text-white hover:bg-amber-600 shadow-lg hover:shadow-amber-500/30'
                           }`}
                         >
                           {ws.slots === ws.enrolled ? 'Đã hết vé' : 'Đăng ký'}
                         </button>
                      </div>
                   </div>
                </div>
              ))}
           </div>
        </div>
      </section>

      {/* === 4. QUY TRÌNH === */}
      <section className="py-24 bg-[#FAFAF8] px-6">
         <div className="max-w-[1200px] mx-auto">
            <div className="text-center mb-16">
               <h2 className="text-4xl font-serif text-slate-900 mb-4">Hành trình trải nghiệm</h2>
               <p className="text-slate-500">Từ đăng ký đến khi mang thành phẩm về nhà</p>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
               <div className="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-slate-200 -z-0"></div>
               {[
                 { step: "01", title: "Đăng ký Online", desc: "Chọn lớp học yêu thích và đặt chỗ qua website hoặc hotline." },
                 { step: "02", title: "Nhận xác nhận", desc: "Nhận email xác nhận kèm hướng dẫn chuẩn bị trước buổi học." },
                 { step: "03", title: "Thực hành tại lớp", desc: "Tự tay làm bánh dưới sự hướng dẫn 1:1 của giảng viên." },
                 { step: "04", title: "Mang quà về", desc: "Đóng gói thành phẩm đẹp mắt và nhận chứng nhận hoàn thành." }
               ].map((item, idx) => (
                 <div key={idx} className="relative z-10 text-center bg-[#FAFAF8] p-4">
                    <div className="w-24 h-24 bg-white border-4 border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg text-2xl font-serif font-bold text-amber-500 transition-transform hover:scale-110 duration-300">
                       {item.step}
                    </div>
                    <h3 className="font-bold text-lg text-slate-900 mb-2">{item.title}</h3>
                    <p className="text-sm text-slate-500 leading-relaxed">{item.desc}</p>
                 </div>
               ))}
            </div>
         </div>
      </section>

      {/* === 5. GALLERY (FULL WIDTH MASONRY - UPDATED) === */}
      <section className="py-24 bg-slate-900 text-white relative overflow-hidden">
         <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
         <div className="container mx-auto px-6 mb-12 text-center relative z-10">
            <h2 className="text-4xl md:text-6xl font-serif mb-4">Moments at Rinn's</h2>
            <p className="text-slate-400 text-lg">Những khoảnh khắc hạnh phúc của học viên</p>
         </div>

         {/* FULL WIDTH GRID - 5 Cột, nhiều ảnh hơn */}
         {/* FULL WIDTH GRID - 5 Cột, nhiều ảnh hơn */}
         <div className="w-full overflow-hidden px-4 md:px-8">
            <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4 auto-rows-[250px]">
               
               {/* Hàng 1 */}
               <div className="col-span-1 row-span-1 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Coffee beans" />
               </div>
               <div className="col-span-2 row-span-2 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1453614512568-c4024d13c247?w=800&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Latte Art Workshop" />
                  <div className="absolute bottom-6 left-6 text-2xl font-serif font-bold drop-shadow-lg">Latte Art Workshop</div>
               </div>
               <div className="col-span-1 row-span-1 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Coffee making" />
               </div>
               <div className="col-span-1 row-span-1 bg-amber-500 rounded-2xl flex flex-col items-center justify-center p-6 text-center shadow-lg group">
                  <Star className="w-8 h-8 fill-white text-white mb-2" />
                  <p className="font-serif italic text-sm leading-relaxed">"Trải nghiệm tuyệt vời nhất tuần qua!"</p>
               </div>

               {/* Hàng 2 */}
               <div className="col-span-1 row-span-1 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1497515114629-f71d768fd07c?w=600&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Barista work" />
               </div>
               <div className="col-span-1 row-span-1 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=600&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Cappuccino" />
               </div>
               <div className="col-span-1 row-span-2 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Espresso machine" />
                  <div className="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                  <div className="absolute bottom-6 left-6 text-xl font-serif font-bold drop-shadow-lg">Espresso Mastery</div>
               </div>

               {/* Hàng 3 */}
               <div className="col-span-2 row-span-1 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=800&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Coffee shop atmosphere" />
               </div>
               <div className="col-span-1 row-span-1 rounded-2xl overflow-hidden group relative">
                  <img src="https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?w=600&auto=format&fit=crop" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Fresh coffee" />
               </div>
               <div className="col-span-1 row-span-1 bg-slate-800 rounded-2xl flex flex-col items-center justify-center p-6 text-center cursor-pointer hover:bg-slate-700 transition-colors">
                  <Instagram className="w-8 h-8 mb-2" />
                  <span className="font-bold text-sm uppercase tracking-widest">Follow Us</span>
                  <span className="text-xs text-slate-400">@RinnsAcademy</span>
               </div>
            </div>
         </div>
      </section>

      {/* === 6. FAQ (Accordion) === */}
      <section className="py-24 px-6 md:px-12 max-w-4xl mx-auto">
         <h2 className="text-3xl font-serif text-center font-bold mb-12">Câu hỏi thường gặp</h2>
         <div className="space-y-4">
            {FAQS.map((item, idx) => (
               <div key={idx} className="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-md">
                  <button 
                    onClick={() => setOpenFaq(openFaq === idx ? null : idx)}
                    className="w-full flex items-center justify-between p-6 text-left bg-white hover:bg-slate-50 transition-colors"
                  >
                     <span className="font-bold text-slate-900 pr-8">{item.q}</span>
                     {openFaq === idx ? <ChevronUp className="w-5 h-5 text-amber-500" /> : <ChevronDown className="w-5 h-5 text-slate-400" />}
                  </button>
                  {openFaq === idx && (
                     <div className="p-6 pt-0 bg-white text-slate-500 text-sm leading-relaxed border-t border-slate-100 animate-in slide-in-from-top-2">
                        {item.a}
                     </div>
                  )}
               </div>
            ))}
         </div>
      </section>

      {/* === 7. NEWSLETTER CTA === */}
      <section className="py-20 bg-[#FAFAF8] text-center border-t border-slate-200">
         <div className="max-w-xl mx-auto px-6">
            <h2 className="text-3xl font-serif font-bold mb-4">Đừng bỏ lỡ lịch học mới</h2>
            <p className="text-slate-500 mb-8">Đăng ký để nhận thông báo về các workshop tháng sau và ưu đãi Early Bird.</p>
            <form className="flex flex-col sm:flex-row gap-2">
               <input type="email" placeholder="Email của bạn..." className="flex-1 px-6 py-3 rounded-full border border-slate-300 focus:outline-none focus:border-amber-500 bg-white transition-all shadow-sm" />
               <button className="px-8 py-3 bg-slate-900 text-white font-bold rounded-full hover:bg-amber-600 transition-all shadow-lg hover:shadow-slate-900/20">Đăng ký</button>
            </form>
         </div>
      </section>

    </div>
  );
};

export default Workshop;
