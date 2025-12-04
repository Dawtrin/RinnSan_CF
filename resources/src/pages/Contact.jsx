import React, { useState } from 'react';
import { MapPin, Phone, Mail, Clock, Send, MessageSquare } from 'lucide-react';

const Contact = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
    privacy: false
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Form submitted:', formData);
    alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');
    setFormData({
      name: '',
      email: '',
      phone: '',
      subject: '',
      message: '',
      privacy: false
    });
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  return (
    <div className="bg-white min-h-screen font-sans text-slate-800">
      
      {/* === 1. HERO SECTION === */}
      <section className="relative h-[50vh] w-full overflow-hidden">
        <div className="absolute inset-0">
          <div className="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70 z-10"></div>
          <img 
            src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=2000&auto=format&fit=crop" 
            alt="Contact Hero" 
            className="w-full h-full object-cover"
          />
        </div>
        <div className="relative z-20 h-full flex flex-col justify-center items-center text-center px-6">
          <span className="text-amber-400 text-xs font-bold tracking-widest uppercase mb-3">
            Get in Touch
          </span>
          <h1 className="text-4xl md:text-6xl font-serif text-white tracking-tight mb-4">
            Liên Hệ Với Chúng Tôi
          </h1>
          <p className="text-white/90 text-base md:text-lg font-light max-w-2xl leading-relaxed">
            Chúng tôi luôn sẵn sàng lắng nghe và phục vụ bạn.
            <br className="hidden md:block"/> 
            Hãy ghé thăm cửa hàng hoặc liên hệ qua các kênh bên dưới.
          </p>
        </div>
      </section>

      {/* === 2. MAIN CONTENT - FORM & INFO === */}
      <section className="max-w-7xl mx-auto px-6 py-20">
        <div className="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">
          <div className="flex flex-col lg:flex-row">
          
            {/* CỘT TRÁI: THÔNG TIN LIÊN HỆ */}
            <div className="w-full lg:w-[45%] bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-10 lg:p-14 flex flex-col justify-between relative overflow-hidden">
              <div className="absolute -bottom-10 -right-10 opacity-5 pointer-events-none">
                <MessageSquare className="w-80 h-80 rotate-12 text-white" />
              </div>

              <div className="relative z-10">
                <h3 className="text-3xl lg:text-4xl font-serif mb-4 text-white">Thông Tin Liên Hệ</h3>
                <p className="text-slate-300 mb-12 text-sm leading-relaxed">
                  Bạn có câu hỏi về sản phẩm, đặt bánh sinh nhật hay muốn hợp tác kinh doanh? 
                  Đừng ngần ngại liên hệ với đội ngũ Rinn's Bakery.
                </p>

                <div className="space-y-8">
                  <div className="flex items-start gap-5 group">
                    <div className="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center shrink-0 group-hover:bg-amber-500 transition-colors">
                      <MapPin className="w-6 h-6 text-amber-400 group-hover:text-white transition-colors" />
                    </div>
                    <div>
                      <h4 className="text-xs font-bold uppercase tracking-wider text-amber-400 mb-2">Địa Chỉ Cửa Hàng</h4>
                      <p className="text-white text-base leading-relaxed">123 Đường Bạch Đằng, Quận Hải Châu,<br/>Thành phố Đà Nẵng, Việt Nam</p>
                    </div>
                  </div>

                  <div className="flex items-start gap-5 group">
                    <div className="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center shrink-0 group-hover:bg-amber-500 transition-colors">
                      <Phone className="w-6 h-6 text-amber-400 group-hover:text-white transition-colors" />
                    </div>
                    <div>
                      <h4 className="text-xs font-bold uppercase tracking-wider text-amber-400 mb-2">Số Điện Thoại</h4>
                      <p className="text-white text-lg font-medium">1900 636 999</p>
                      <p className="text-slate-400 text-sm mt-1">Hỗ trợ khách hàng 24/7</p>
                    </div>
                  </div>

                  <div className="flex items-start gap-5 group">
                    <div className="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center shrink-0 group-hover:bg-amber-500 transition-colors">
                      <Mail className="w-6 h-6 text-amber-400 group-hover:text-white transition-colors" />
                    </div>
                    <div>
                      <h4 className="text-xs font-bold uppercase tracking-wider text-amber-400 mb-2">Email</h4>
                      <p className="text-white text-base">contact@rinnsbakery.com</p>
                      <p className="text-slate-400 text-sm mt-1">Phản hồi trong 24h</p>
                    </div>
                  </div>

                  <div className="flex items-start gap-5 group">
                    <div className="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center shrink-0 group-hover:bg-amber-500 transition-colors">
                      <Clock className="w-6 h-6 text-amber-400 group-hover:text-white transition-colors" />
                    </div>
                    <div>
                      <h4 className="text-xs font-bold uppercase tracking-wider text-amber-400 mb-2">Giờ Mở Cửa</h4>
                      <p className="text-white text-base font-medium">07:00 - 22:00</p>
                      <p className="text-slate-400 text-sm mt-1">Mở cửa tất cả các ngày trong tuần</p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Social Links */}
              <div className="mt-12 pt-8 border-t border-white/10 relative z-10">
                <p className="text-xs font-bold uppercase tracking-wider text-amber-400 mb-5">Kết Nối Với Chúng Tôi</p>
                <div className="flex flex-wrap gap-3">
                  {[
                    { name: 'Facebook', icon: '📘' },
                    { name: 'Instagram', icon: '📷' },
                    { name: 'TikTok', icon: '🎵' },
                    { name: 'Zalo', icon: '💬' }
                  ].map(social => (
                    <a 
                      key={social.name} 
                      href="#" 
                      className="px-4 py-2 bg-white/10 hover:bg-amber-500 text-white rounded-lg text-sm font-medium transition-all hover:scale-105 flex items-center gap-2"
                    >
                      <span>{social.icon}</span>
                      {social.name}
                    </a>
                  ))}
                </div>
              </div>
            </div>

            {/* CỘT PHẢI: FORM LIÊN HỆ */}
            <div className="w-full lg:w-[55%] p-10 lg:p-14 bg-white">
              <div className="mb-10">
                <h3 className="text-3xl lg:text-4xl font-serif text-slate-900 mb-3">Gửi Tin Nhắn</h3>
                <p className="text-slate-500 text-base leading-relaxed">
                  Vui lòng điền thông tin bên dưới, chúng tôi sẽ phản hồi sớm nhất có thể. 
                  Mọi thông tin của bạn đều được bảo mật.
                </p>
              </div>

              <div className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="relative">
                    <input 
                      type="text" 
                      name="name"
                      value={formData.name}
                      onChange={handleChange}
                      className="peer w-full border-0 border-b-2 border-slate-300 py-3 text-slate-900 placeholder-transparent focus:border-amber-500 focus:outline-none transition-colors bg-transparent"
                      placeholder="Họ và tên"
                      required
                    />
                    <label className="absolute left-0 -top-3.5 text-xs font-medium text-slate-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-amber-600">
                      Họ và tên *
                    </label>
                  </div>
                  <div className="relative">
                    <input 
                      type="email" 
                      name="email"
                      value={formData.email}
                      onChange={handleChange}
                      className="peer w-full border-0 border-b-2 border-slate-300 py-3 text-slate-900 placeholder-transparent focus:border-amber-500 focus:outline-none transition-colors bg-transparent"
                      placeholder="Email"
                      required
                    />
                    <label className="absolute left-0 -top-3.5 text-xs font-medium text-slate-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-amber-600">
                      Địa chỉ Email *
                    </label>
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="relative">
                    <input 
                      type="tel" 
                      name="phone"
                      value={formData.phone}
                      onChange={handleChange}
                      className="peer w-full border-0 border-b-2 border-slate-300 py-3 text-slate-900 placeholder-transparent focus:border-amber-500 focus:outline-none transition-colors bg-transparent"
                      placeholder="Số điện thoại"
                    />
                    <label className="absolute left-0 -top-3.5 text-xs font-medium text-slate-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-amber-600">
                      Số điện thoại
                    </label>
                  </div>
                  <div className="relative">
                    <select 
                      name="subject"
                      value={formData.subject}
                      onChange={handleChange}
                      className="peer w-full border-0 border-b-2 border-slate-300 py-3 text-slate-900 focus:border-amber-500 focus:outline-none transition-colors bg-transparent appearance-none cursor-pointer"
                      required
                    >
                      <option value="">Chọn chủ đề</option>
                      <option value="order">Vấn đề đơn hàng</option>
                      <option value="product">Thông tin sản phẩm</option>
                      <option value="custom">Đặt bánh theo yêu cầu</option>
                      <option value="collab">Hợp tác kinh doanh</option>
                      <option value="feedback">Góp ý chất lượng</option>
                      <option value="other">Khác</option>
                    </select>
                    <label className="absolute left-0 -top-3.5 text-xs font-medium text-slate-600">
                      Chủ đề liên hệ *
                    </label>
                  </div>
                </div>

                <div className="relative">
                  <textarea 
                    name="message"
                    value={formData.message}
                    onChange={handleChange}
                    rows="5"
                    className="peer w-full border-0 border-b-2 border-slate-300 py-3 text-slate-900 placeholder-transparent focus:border-amber-500 focus:outline-none transition-colors resize-none bg-transparent"
                    placeholder="Nội dung tin nhắn"
                    required
                  ></textarea>
                  <label className="absolute left-0 -top-3.5 text-xs font-medium text-slate-600 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-amber-600">
                    Nội dung tin nhắn *
                  </label>
                </div>

                <div className="flex items-start gap-3 pt-2">
                  <input 
                    type="checkbox" 
                    name="privacy"
                    checked={formData.privacy}
                    onChange={handleChange}
                    className="mt-1 w-4 h-4 rounded border-slate-300 text-amber-600"
                    required
                  />
                  <label className="text-sm text-slate-600 leading-relaxed">
                    Tôi đồng ý với <a href="#" className="text-amber-600 hover:underline font-medium">chính sách bảo mật</a> và 
                    cho phép Rinn's Bakery liên hệ với tôi qua email hoặc điện thoại.
                  </label>
                </div>

                <div className="pt-4">
                  <button 
                    onClick={handleSubmit}
                    className="w-full md:w-auto px-12 py-4 bg-gradient-to-r from-slate-900 to-slate-800 hover:from-amber-600 hover:to-amber-500 text-white text-sm font-bold uppercase tracking-wider rounded-full transition-all shadow-lg hover:shadow-2xl hover:scale-105 flex items-center justify-center gap-3 group"
                  >
                    <span>Gửi Tin Nhắn</span>
                    <Send className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      {/* === 3. FEATURES SECTION === */}
      <section className="bg-gradient-to-b from-slate-50 via-white to-amber-50/30 py-24">
        <div className="max-w-7xl mx-auto px-6">
          <div className="text-center mb-20">
            <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-4 block">
              Why Choose Us
            </span>
            <h2 className="text-4xl md:text-6xl font-serif text-slate-900 mb-6">
              Cam Kết Của Chúng Tôi
            </h2>
            <p className="text-slate-600 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
              Rinn's Bakery luôn đặt chất lượng và sự hài lòng của khách hàng lên hàng đầu
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {[
              {
                image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                title: 'Sản Phẩm Tươi Mới',
                desc: 'Làm mới mỗi ngày với nguyên liệu chọn lọc',
                color: 'from-amber-500 to-orange-500'
              },
              {
                image: 'https://images.unsplash.com/photo-1464454709131-ffd692591ee5?w=800&auto=format&fit=crop',
                title: 'Giao Hàng Nhanh',
                desc: 'Giao hàng trong vòng 2h tại nội thành Đà Nẵng',
                color: 'from-orange-500 to-red-500'
              },
              {
                image: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&auto=format&fit=crop',
                title: 'Tùy Chỉnh Theo Yêu Cầu',
                desc: 'Thiết kế bánh theo ý tưởng riêng của bạn',
                color: 'from-amber-500 to-yellow-500'
              },
              {
                image: 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&auto=format&fit=crop',
                title: 'Hỗ Trợ 24/7',
                desc: 'Đội ngũ tư vấn sẵn sàng hỗ trợ mọi lúc',
                color: 'from-orange-500 to-amber-500'
              }
            ].map((item, idx) => (
              <div key={idx} className="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 overflow-hidden">
                <div className="relative h-48 overflow-hidden">
                  <img 
                    src={item.image} 
                    alt={item.title}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                  />
                  <div className={`absolute inset-0 bg-gradient-to-br ${item.color} opacity-40 group-hover:opacity-60 transition-opacity duration-500`}></div>
                </div>
                
                <div className="p-8">
                  <h3 className="text-xl font-serif text-slate-900 mb-4 group-hover:text-amber-600 transition-colors">
                    {item.title}
                  </h3>
                  <p className="text-slate-600 text-sm leading-relaxed">
                    {item.desc}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* === 4. FAQ SECTION === */}
      <section className="bg-white py-20">
        <div className="max-w-4xl mx-auto px-6">
          <div className="text-center mb-16">
            <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">
              FAQs
            </span>
            <h2 className="text-3xl md:text-5xl font-serif text-slate-900 mb-4">
              Câu Hỏi Thường Gặp
            </h2>
          </div>

          <div className="space-y-4">
            {[
              {
                q: 'Làm thế nào để đặt bánh sinh nhật?',
                a: 'Bạn có thể đặt bánh qua hotline 1900 636 999, qua form liên hệ trên website hoặc trực tiếp tại cửa hàng. Chúng tôi khuyến nghị đặt trước ít nhất 24-48h.'
              },
              {
                q: 'Rinn\'s có giao hàng tận nơi không?',
                a: 'Có, chúng tôi cung cấp dịch vụ giao hàng tận nơi trong vòng bán kính 10km từ cửa hàng. Phí giao hàng từ 20,000đ tùy khu vực.'
              },
              {
                q: 'Tôi có thể tùy chỉnh thiết kế bánh không?',
                a: 'Hoàn toàn được! Hãy mô tả ý tưởng của bạn và gửi hình ảnh tham khảo. Đội ngũ của chúng tôi sẽ tư vấn và thiết kế theo yêu cầu.'
              },
              {
                q: 'Các sản phẩm có phù hợp với người ăn chay không?',
                a: 'Chúng tôi có nhiều lựa chọn cho khách hàng ăn chay, không gluten và các nhu cầu dinh dưỡng đặc biệt. Vui lòng thông báo khi đặt hàng.'
              },
              {
                q: 'Chính sách hoàn trả như thế nào?',
                a: 'Nếu có vấn đề về chất lượng sản phẩm, vui lòng liên hệ trong vòng 2h sau khi nhận hàng. Chúng tôi sẽ đổi sản phẩm mới hoặc hoàn tiền 100%.'
              }
            ].map((faq, idx) => (
              <details key={idx} className="group bg-slate-50 rounded-xl overflow-hidden border border-slate-200 hover:border-amber-400 transition-colors">
                <summary className="cursor-pointer p-6 font-medium text-slate-900 text-lg flex items-center justify-between hover:text-amber-600 transition-colors">
                  <span>{faq.q}</span>
                  <span className="text-amber-600 text-2xl group-open:rotate-45 transition-transform">+</span>
                </summary>
                <div className="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-200 pt-4">
                  {faq.a}
                </div>
              </details>
            ))}
          </div>
        </div>
      </section>

      {/* === 5. MAP SECTION === */}
      <section className="relative">
        <div className="max-w-7xl mx-auto px-6 py-16 text-center">
          <span className="text-amber-600 text-xs font-bold tracking-widest uppercase mb-3 block">
            Visit Us
          </span>
          <h2 className="text-3xl md:text-5xl font-serif text-slate-900 mb-4">
            Ghé Thăm Cửa Hàng
          </h2>
          <p className="text-slate-600 text-lg max-w-2xl mx-auto mb-8">
            Đến trực tiếp cửa hàng để trải nghiệm không gian ấm cúng và thưởng thức các sản phẩm mới nhất
          </p>
        </div>
        
        <div className="w-full h-[500px] grayscale hover:grayscale-0 transition-all duration-700 relative">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.8236374823!2d108.2207!3d16.0697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421833d7907f7f%3A0x6336c2676054e96!2sB%E1%BA%A1ch%20%C4%90%E1%BA%B1ng%2C%20H%E1%BA%A3i%20Ch%C3%A2u%2C%20%C4%90%C3%A0%20N%E1%BA%B5ng!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
            width="100%" 
            height="100%" 
            style={{ border: 0 }} 
            allowFullScreen="" 
            loading="lazy" 
            referrerPolicy="no-referrer-when-downgrade"
            title="Rinn's Bakery Map"
          ></iframe>
          
          <div className="absolute bottom-8 left-1/2 -translate-x-1/2 z-10">
            <a 
              href="https://maps.google.com" 
              target="_blank" 
              rel="noopener noreferrer"
              className="px-8 py-4 bg-white text-slate-900 font-bold text-sm uppercase tracking-wider rounded-full shadow-2xl hover:bg-amber-500 hover:text-white transition-all hover:scale-105 inline-flex items-center gap-3"
            >
              <MapPin className="w-5 h-5" />
              Xem Chỉ Đường
            </a>
          </div>
        </div>
      </section>

      {/* === 6. CTA SECTION === */}
      <section className="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 py-24 relative overflow-hidden">
        <div className="absolute inset-0 opacity-30">
          <div className="absolute top-20 left-20 w-64 h-64 bg-amber-300 rounded-full blur-3xl animate-pulse"></div>
          <div className="absolute bottom-20 right-20 w-80 h-80 bg-orange-300 rounded-full blur-3xl animate-pulse"></div>
        </div>
        
        <div className="max-w-5xl mx-auto px-6 text-center relative z-10">
          <div className="inline-block mb-6">
            <span className="text-amber-600 text-xs font-bold tracking-widest uppercase px-4 py-2 bg-white/80 rounded-full shadow-sm">
              Ready to Order?
            </span>
          </div>
          
          <h2 className="text-4xl md:text-6xl font-serif text-slate-900 mb-6 leading-tight">
            Hãy Trải Nghiệm<br/>
            <span className="text-amber-600">Hương Vị Đặc Biệt</span>
          </h2>
          
          <p className="text-slate-600 text-lg md:text-xl mb-12 leading-relaxed max-w-2xl mx-auto">
            Ghé thăm Rinn's Bakery hôm nay để khám phá những món bánh tươi mới được làm từ tình yêu và đam mê.
          </p>
          
          <div className="flex flex-col sm:flex-row gap-5 justify-center items-center">
            <a 
              href="tel:1900636999"
              className="group px-10 py-5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-sm uppercase tracking-wider rounded-full transition-all shadow-xl hover:shadow-2xl hover:scale-110 inline-flex items-center justify-center gap-3 min-w-[220px]"
            >
              <Phone className="w-5 h-5 group-hover:rotate-12 transition-transform" />
              <span>Gọi Đặt Hàng</span>
            </a>
            
            <a 
              href="#"
              className="group px-10 py-5 bg-white hover:bg-slate-900 text-slate-900 hover:text-white font-bold text-sm uppercase tracking-wider rounded-full transition-all shadow-lg hover:shadow-xl border-2 border-slate-200 hover:border-slate-900 inline-flex items-center justify-center gap-3 min-w-[220px]"
            >
              <span>Xem Menu</span>
              <svg className="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </a>
          </div>
          
          <div className="mt-12 flex items-center justify-center gap-8 text-slate-600">
            <div className="flex items-center gap-2">
              <div className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
              <span className="text-sm font-medium">Đang Mở Cửa</span>
            </div>
            <div className="w-px h-6 bg-slate-300"></div>
            <div className="flex items-center gap-2">
              <Clock className="w-4 h-4 text-amber-600" />
              <span className="text-sm font-medium">07:00 - 22:00</span>
            </div>
          </div>
        </div>
      </section>

    </div>
  );
};

export default Contact;