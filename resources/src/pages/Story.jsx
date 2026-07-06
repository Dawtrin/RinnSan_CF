import React from 'react';
import { Quote, Leaf, Award, Heart, Clock, ArrowDown, Coffee, Users, TrendingUp, Sparkles } from 'lucide-react';

const Story = () => {
  return (
    <div className="bg-[#FAFAF8] min-h-screen font-sans text-slate-800 selection:bg-amber-100 selection:text-amber-900">
      
      <style>
        {`@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');
          .font-serif { font-family: 'Playfair Display', serif; }
          .font-sans { font-family: 'Inter', sans-serif; }
          @keyframes slow-zoom {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
          }
          .animate-slow-zoom {
            animation: slow-zoom 20s ease-in-out infinite;
          }
          @keyframes fade-in-up {
            from {
              opacity: 0;
              transform: translateY(30px);
            }
            to {
              opacity: 1;
              transform: translateY(0);
            }
          }
          .animate-fade-in-up {
            animation: fade-in-up 1s ease-out;
          }
          .delay-100 { animation-delay: 0.1s; }
          .delay-200 { animation-delay: 0.2s; }
          .delay-300 { animation-delay: 0.3s; }`}
      </style>

      {/* === 1. HERO SECTION (Parallax Text) === */}
      <section className="relative h-screen w-full flex items-center justify-center overflow-hidden">
        <div className="absolute inset-0 z-0">
           <div className="absolute inset-0 bg-black/40 z-10"></div>
           <img 
             src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2400&auto=format&fit=crop" 
             alt="Coffee and Bakery Story" 
             className="w-full h-full object-cover animate-slow-zoom"
           />
        </div>
        
        <div className="relative z-20 text-center text-white px-6 max-w-4xl mx-auto">
           <span className="block text-xs font-bold tracking-[0.4em] uppercase mb-6 animate-fade-in-up">The Rinn's Journey</span>
           <h1 className="text-5xl md:text-8xl font-serif font-medium mb-8 leading-tight drop-shadow-2xl animate-fade-in-up delay-100">
             Từ hạt cà phê <br/>
             <span className="italic text-amber-200">đến chiếc bánh hạnh phúc.</span>
           </h1>
           <div className="w-[1px] h-24 bg-white/50 mx-auto mt-12 animate-bounce"></div>
        </div>
      </section>

      {/* === 2. INTRODUCTION (Letter Style) === */}
      <section className="py-32 px-6 md:px-12 max-w-[1200px] mx-auto">
        <div className="flex flex-col md:flex-row gap-16 items-start">
           <div className="w-full md:w-1/3 sticky top-32">
              <h2 className="text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">
                Không chỉ là <br/>tiệm cà phê.
              </h2>
              <div className="w-20 h-1 bg-amber-500 mb-8"></div>
              <p className="text-slate-500 font-medium italic text-lg">
                "Chúng tôi tin rằng sự cân bằng hoàn hảo nằm ở một tách cà phê đậm đà bên cạnh một chiếc bánh ngọt ngào."
              </p>
              <img 
                src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Signature_sample.svg" 
                alt="Signature" 
                className="h-16 mt-8 opacity-60"
              />
              <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">Founder, Minh Long</p>
           </div>
           
           <div className="w-full md:w-2/3 prose prose-lg text-slate-600 leading-relaxed text-justify">
              <p className="first-letter:text-7xl first-letter:font-serif first-letter:text-slate-900 first-letter:float-left first-letter:mr-4 first-letter:leading-[0.8]">
                Vào một buổi sáng mùa thu năm 2020, trong không gian yên tĩnh của một con hẻm nhỏ Hội An, mùi cà phê rang xay thơm lừng hòa quyện cùng hương bơ nướng đã đánh thức những giác quan. Đó là nơi Rinn's Cafe & Bakery ra đời, nơi hội tụ của hai niềm đam mê: Hạt cà phê và Bột mì.
              </p>
              <p>
                Chúng tôi bắt đầu hành trình này với triết lý <strong>"Perfect Pairing"</strong>. Trong khi các Barista tỉ mỉ căn chỉnh từng giây chiết xuất Espresso từ hạt Arabica Cầu Đất, thì các thợ làm bánh kiên nhẫn ủ bột Croissant suốt 48 giờ. Hai quy trình tưởng chừng riêng biệt nhưng lại hòa quyện để tạo nên trải nghiệm thưởng thức trọn vẹn.
              </p>
              <p>
                Tại sao chúng tôi khắt khe đến thế? Bởi vì một tách Cappuccino ngon cần một chiếc Tiramisu xứng tầm. Sự đắng nhẹ, thanh tao của cà phê sẽ tôn lên vị ngọt ngào, béo ngậy của bánh. Đó là bản giao hưởng hương vị mà Rinn's muốn gửi trao.
              </p>
              <div className="grid grid-cols-2 gap-4 my-12">
                 <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600" className="rounded-2xl w-full h-64 object-cover" alt="Coffee Brewing" />
                 <img src="https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?w=600" className="rounded-2xl w-full h-64 object-cover translate-y-8" alt="Baking Details" />
              </div>
              <p>
                Ngày nay, Rinn's đã trở thành điểm đến quen thuộc của những tâm hồn yêu cái đẹp và hương vị nguyên bản tại Đà Nẵng. Dù là một buổi sáng tĩnh lặng hay một chiều rộn rã, chúng tôi vẫn ở đây, pha từng tách cà phê và nướng từng mẻ bánh với tất cả sự Tận tâm và Yêu thương.
              </p>
           </div>
        </div>
      </section>

      {/* === 3. FULL-BLEED IMAGE DIVIDER === */}
      <section className="relative h-[70vh] w-full overflow-hidden">
         <div className="absolute inset-0">
            <img 
              src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=2400&auto=format&fit=crop" 
              alt="Coffee and Pastry" 
              className="w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
         </div>
         <div className="absolute bottom-16 left-6 md:left-16 max-w-2xl text-white z-10">
            <h3 className="text-5xl md:text-7xl font-serif mb-4 leading-tight">
              Bản giao hưởng<br/>của hương vị
            </h3>
            <p className="text-white/80 text-lg font-light">Vị đắng êm dịu của cà phê và vị ngọt ngào của lớp bánh mềm.</p>
         </div>
      </section>

      {/* === 4. THE NUMBERS (Stats Grid) === */}
      <section className="py-24 px-6 bg-white">
         <div className="max-w-[1400px] mx-auto">
            <div className="text-center mb-16">
               <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-4 block">By The Numbers</span>
               <h2 className="text-4xl md:text-5xl font-serif text-slate-900">Hành trình bằng con số</h2>
            </div>
            
            <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
               {[
                 { number: "100%", label: "Hạt Arabica Cầu Đất", icon: Leaf },
                 { number: "1000+", label: "Khách hàng mỗi tuần", icon: Users },
                 { number: "24h", label: "Quy trình Cold Brew", icon: Clock },
                 { number: "27", label: "Lớp bơ trong Croissant", icon: Award }
               ].map((stat, idx) => (
                 <div key={idx} className="text-center group">
                    <div className="w-16 h-16 mx-auto mb-6 bg-amber-50 rounded-full flex items-center justify-center group-hover:bg-amber-500 transition-all duration-500">
                       <stat.icon className="w-8 h-8 text-amber-600 group-hover:text-white transition-colors" />
                    </div>
                    <h3 className="text-5xl font-black text-slate-900 mb-2 font-serif">{stat.number}</h3>
                    <p className="text-slate-500 text-sm font-medium">{stat.label}</p>
                 </div>
               ))}
            </div>
         </div>
      </section>

      {/* === 5. PHILOSOPHY - INTERACTIVE REVEAL === */}
      <section className="py-32 bg-slate-900 text-white px-6 relative overflow-hidden">
         {/* Decorative Elements */}
         <div className="absolute top-20 left-10 w-72 h-72 bg-amber-500/5 rounded-full blur-3xl"></div>
         <div className="absolute bottom-20 right-10 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
         
         <div className="max-w-[1400px] mx-auto relative z-10">
            <div className="text-center mb-20">
               <span className="text-amber-500 text-xs font-bold tracking-widest uppercase mb-4 block">Our Philosophy</span>
               <h2 className="text-5xl md:text-7xl font-serif mb-6">Triết lý RinnSan</h2>
               <p className="text-slate-400 text-lg max-w-2xl mx-auto">Ba nguyên tắc vàng định hình ly cà phê và chiếc bánh bạn cầm trên tay</p>
            </div>
            
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
               {[
                 { 
                   icon: Coffee, 
                   title: "Farm to Cup", 
                   desc: "Hạt cà phê được tuyển chọn từ nông trại, rang mộc thủ công để giữ trọn vẹn hương vị nguyên bản của vùng đất.",
                   number: "01",
                   image: "https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80"
                 },
                 { 
                   icon: TrendingUp, 
                   title: "Artisan Baking", 
                   desc: "Bánh được làm mới mỗi ngày với bơ Pháp và men tự nhiên. Không đường hóa học, không chất bảo quản.",
                   number: "02",
                   image: "https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=800&q=80"
                 },
                 { 
                   icon: Heart, 
                   title: "Sự Kết Nối", 
                   desc: "Chúng tôi tạo ra không gian để bạn kết nối với chính mình và những người thân yêu bên tách cà phê thơm.",
                   number: "03",
                   image: "https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=800&q=80"
                 }
               ].map((item, idx) => (
                 <div key={idx} className="group relative overflow-hidden rounded-3xl bg-white/5 border border-white/10 hover:border-amber-500/50 transition-all duration-700">
                    {/* Background Image */}
                    <div className="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-700">
                       <img src={item.image} alt={item.title} className="w-full h-full object-cover" />
                    </div>
                    
                    {/* Content */}
                    <div className="relative p-10 h-full flex flex-col">
                       {/* Number */}
                       <span className="text-8xl font-black text-white/5 absolute top-4 right-6 group-hover:text-amber-500/10 transition-colors duration-500">
                         {item.number}
                       </span>
                       
                       {/* Icon */}
                       <div className="w-20 h-20 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-amber-500 group-hover:text-white group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 relative z-10">
                          <item.icon className="w-10 h-10" />
                       </div>
                       
                       {/* Text */}
                       <h3 className="text-3xl font-serif font-bold mb-4 relative z-10">{item.title}</h3>
                       <p className="text-slate-400 leading-relaxed text-base flex-grow relative z-10 group-hover:text-slate-300 transition-colors">
                         {item.desc}
                       </p>
                       
                       {/* Hover Line */}
                       <div className="mt-8 pt-6 border-t border-white/10 relative z-10">
                          <span className="text-amber-500 text-sm font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            Khám phá Menu →
                          </span>
                       </div>
                    </div>
                 </div>
               ))}
            </div>
         </div>
      </section>

      {/* === 6. SPLIT CONTENT (Magazine Layout) === */}
      <section className="py-32 px-6 bg-[#FAFAF8]">
         <div className="max-w-[1400px] mx-auto">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
               <div className="order-2 md:order-1">
                  <img 
                    src="https://images.unsplash.com/photo-1517231925375-bf2cb42917a5?q=80&w=1200&auto=format&fit=crop" 
                    alt="Coffee and Cake Pairing" 
                    className="rounded-3xl shadow-2xl w-full h-[500px] object-cover"
                  />
               </div>
               <div className="order-1 md:order-2">
                  <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-4 block">Perfect Match</span>
                  <h2 className="text-4xl md:text-5xl font-serif text-slate-900 mb-6 leading-tight">
                    Sự kết hợp<br/>hoàn hảo
                  </h2>
                  <p className="text-slate-600 text-lg leading-relaxed mb-6">
                    Không có gì tuyệt vời hơn cảm giác nhâm nhi một tách Latte nóng hổi và thưởng thức một miếng bánh Red Velvet ngọt dịu.
                  </p>
                  <p className="text-slate-600 text-lg leading-relaxed mb-8">
                    Tại RinnSan, chúng tôi không chỉ bán từng món riêng lẻ. Chúng tôi thiết kế hương vị để cà phê và bánh bổ trợ cho nhau, tạo nên một trải nghiệm ẩm thực cân bằng và tinh tế.
                  </p>
                  <div className="flex gap-4">
                     <div className="flex-1 p-6 bg-white rounded-2xl border border-slate-100">
                        <h4 className="font-bold text-3xl text-slate-900 mb-2 font-serif">100%</h4>
                        <p className="text-slate-500 text-sm">Hạt Arabica & Robusta</p>
                     </div>
                     <div className="flex-1 p-6 bg-white rounded-2xl border border-slate-100">
                        <h4 className="font-bold text-3xl text-slate-900 mb-2 font-serif">15+</h4>
                        <p className="text-slate-500 text-sm">Năm kinh nghiệm F&B</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>

      {/* === 7. TIMELINE (Vertical History) === */}
      <section className="py-32 px-6 bg-white overflow-hidden">
         <div className="max-w-[1000px] mx-auto">
            <div className="text-center mb-20">
               <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-4 block">Our Journey</span>
               <h2 className="text-4xl md:text-5xl font-serif text-slate-900">Dấu ấn thời gian</h2>
            </div>
            
            <div className="relative">
               <div className="absolute left-[19px] md:left-1/2 top-0 bottom-0 w-0.5 bg-slate-200 -ml-[1px]"></div>
               
               {[
                 { 
                   year: "2020", 
                   title: "Khởi đầu nhỏ", 
                   desc: "Căn bếp nhỏ 20m2 tại Hội An với chiếc máy pha cà phê cũ và lò nướng gia đình.",
                   image: "https://images.unsplash.com/photo-1556910103-1c02745a30bf?w=600&q=80"
                 },
                 { 
                   year: "2021", 
                   title: "Thử nghiệm & Kết hợp", 
                   desc: "Tìm ra công thức rang xay độc quyền RinnSan Blend và dòng bánh Sourdough đặc trưng.",
                   image: "https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=600&q=80"
                 },
                 { 
                   year: "2022", 
                   title: "Cửa hàng đầu tiên", 
                   desc: "Khai trương RinnSan Cafe & Bakery tại trung tâm Đà Nẵng. Đón 500 khách ngày đầu.",
                   image: "https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600&q=80"
                 },
                 { 
                   year: "2023", 
                   title: "Không gian kết nối", 
                   desc: "Mở rộng không gian làm việc (Co-working) kết hợp thưởng thức cà phê nghệ thuật.",
                   image: "https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?w=600&q=80"
                 },
                 { 
                   year: "2024", 
                   title: "Vươn xa", 
                   desc: "Được bình chọn là 'Top Coffee & Pastry Spot' tại Đà Nẵng và chuẩn bị mở chi nhánh thứ 2.",
                   image: "https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=600&q=80"
                 }
               ].map((item, idx) => (
                 <div key={idx} className={`relative flex items-center mb-16 ${idx % 2 === 0 ? 'md:flex-row-reverse' : ''}`}>
                    <div className="absolute left-0 md:left-1/2 w-10 h-10 bg-white border-4 border-amber-500 rounded-full flex items-center justify-center z-10 -translate-x-0 md:-translate-x-1/2 shadow-lg">
                       <div className="w-2 h-2 bg-slate-900 rounded-full"></div>
                    </div>
                    
                    <div className="w-full md:w-1/2 pl-16 md:pl-0 md:pr-16 md:text-right group">
                       <div className={`bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 overflow-hidden ${idx % 2 === 0 ? 'md:mr-16' : 'md:ml-16'}`}>
                          {/* Image */}
                          <div className="relative h-48 overflow-hidden">
                             <img 
                               src={item.image} 
                               alt={item.title} 
                               className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                             />
                             <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                             <span className="absolute bottom-4 left-6 text-white text-6xl font-black font-serif opacity-80">
                               {item.year}
                             </span>
                          </div>
                          
                          {/* Content */}
                          <div className="p-8">
                             <span className="text-amber-600 font-bold text-sm block mb-2">{item.year}</span>
                             <h3 className="text-2xl font-serif font-bold text-slate-900 mb-3">{item.title}</h3>
                             <p className="text-slate-500 text-sm leading-relaxed">{item.desc}</p>
                          </div>
                       </div>
                    </div>
                    <div className="hidden md:block w-1/2"></div>
                 </div>
               ))}
            </div>
         </div>
      </section>

      {/* === 8. IMAGE GALLERY (3-Column) === */}
      <section className="py-24 px-6 bg-[#FAFAF8]">
         <div className="max-w-[1600px] mx-auto">
            <div className="text-center mb-16">
               <h2 className="text-4xl md:text-5xl font-serif text-slate-900">Khoảnh khắc tại RinnSan</h2>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
               <img src="https://images.unsplash.com/photo-1498804103079-a6351b050096?w=600&q=80" className="w-full h-80 object-cover rounded-2xl hover:scale-[1.02] transition-transform duration-500" alt="Moment 1" />
               <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80" className="w-full h-80 object-cover rounded-2xl hover:scale-[1.02] transition-transform duration-500 md:translate-y-8" alt="Moment 2" />
               <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80" className="w-full h-80 object-cover rounded-2xl hover:scale-[1.02] transition-transform duration-500" alt="Moment 3" />
            </div>
         </div>
      </section>

      {/* === 9. TEAM (Minimal Portrait) === */}
      <section className="py-24 bg-white px-6">
         <div className="max-w-[1600px] mx-auto">
            <div className="text-center mb-20">
               <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-4 block">Meet The Team</span>
               <h2 className="text-4xl md:text-5xl font-serif text-slate-900 mb-4">Người giữ lửa</h2>
               <p className="text-slate-500 max-w-2xl mx-auto">Đội ngũ tâm huyết đứng sau từng ly nước và chiếc bánh.</p>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
               {[
                 { name: "Minh Long", role: "Founder & Head Barista", img: "https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&q=80" },
                 { name: "Sarah Nguyen", role: "Head Pastry Chef", img: "https://images.unsplash.com/photo-1595273670150-bd0c3c392e46?w=600&q=80" },
                 { name: "Tuan Anh", role: "Senior Barista", img: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80" },
                 { name: "Linh Dan", role: "Store Manager", img: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&q=80" }
               ].map((member, idx) => (
                 <div key={idx} className="group relative overflow-hidden rounded-[2rem]">
                    <img src={member.img} alt={member.name} className="w-full h-[400px] object-cover filter grayscale group-hover:grayscale-0 transition-all duration-700" />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                       <h3 className="text-white text-xl font-serif font-bold">{member.name}</h3>
                       <p className="text-amber-400 text-xs font-bold uppercase tracking-widest">{member.role}</p>
                    </div>
                 </div>
               ))}
            </div>
         </div>
      </section>

      {/* === 10. TESTIMONIAL (Large Quote) === */}
      <section className="py-32 px-6 bg-slate-900 text-white relative overflow-hidden">
         <div className="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
         <div className="max-w-4xl mx-auto text-center relative z-10">
            <Sparkles className="w-12 h-12 text-amber-500 mx-auto mb-8" />
            <blockquote className="text-3xl md:text-4xl font-serif mb-8 leading-relaxed italic">
              "RinnSan không chỉ là quán cà phê, đó là nơi tôi tìm thấy sự bình yên. Một tách Cappuccino ấm nóng và chiếc bánh sừng bò giòn tan là khởi đầu hoàn hảo cho ngày mới."
            </blockquote>
            <div className="flex items-center justify-center gap-4">
               <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80" className="w-16 h-16 rounded-full object-cover border-2 border-amber-500" alt="Customer" />
               <div className="text-left">
                  <p className="font-bold">Nguyen Anh Thu</p>
                  <p className="text-sm text-slate-400">Khách hàng thân thiết</p>
               </div>
            </div>
         </div>
      </section>

      {/* === 11. FINAL CTA === */}
      <section className="py-32 bg-[#F5E6D3] text-center px-6 relative overflow-hidden">
         <div className="absolute top-0 left-0 w-64 h-64 bg-white/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
         <div className="absolute bottom-0 right-0 w-96 h-96 bg-amber-300/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
         
         <div className="max-w-3xl mx-auto relative z-10">
            <Quote className="w-12 h-12 text-slate-900/20 mx-auto mb-8" />
            <h2 className="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-8 leading-tight">
              "Cà phê để tỉnh thức, bánh ngọt để ủi an tâm hồn."
            </h2>
            <p className="text-slate-600 font-bold uppercase tracking-widest mb-12">— RinnSan Philosophy</p>
            <button className="px-10 py-4 bg-slate-900 text-white font-bold rounded-full hover:bg-amber-600 transition-all shadow-xl hover:shadow-slate-900/20 hover:-translate-y-1">
               Ghé thăm RinnSan ngay
            </button>
         </div>
      </section>

    </div>
  );
};

export default Story;