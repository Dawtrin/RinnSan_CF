import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { User, Lock, ArrowRight, Loader2, Coffee, Eye, EyeOff, ShieldCheck, Zap, Activity } from 'lucide-react';
import { assetUrl, USE_MOCK } from '../config/api.js';
import { apiFetch } from '../services/apiClient.js';

const LoginPage = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({ username: '', password: '' });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [showPassword, setShowPassword] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
    setError('');
  };

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    try {
      const json = await apiFetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      if (json.success) {
        localStorage.setItem('token', json.data.token);
        localStorage.setItem('user', JSON.stringify(json.data.user));
        navigate(USE_MOCK ? '/' : '/admin/dashboard');
      } else {
        setError(json.message || 'Thông tin đăng nhập không chính xác');
      }
    } catch (err) {
      setError('Không thể kết nối đến máy chủ');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex font-sans bg-white overflow-hidden">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap');
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Outfit', sans-serif; }
        .animate-fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Hiệu ứng kính mờ cho thẻ thống kê */
        .glass-stat {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
      `}</style>

      {/* --- CỘT TRÁI: VIDEO BRANDING --- */}
      <div className="hidden lg:flex w-7/12 relative bg-black items-center justify-center overflow-hidden">
        {/* Video Background */}
       <video 
        autoPlay 
        loop 
        muted={true} // [FIX] React bắt buộc phải có ={true} mới tự chạy được
        playsInline
        poster="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=1920" // [FIX] Ảnh hiện trong lúc chờ video tải
        className="absolute inset-0 w-full h-full object-cover opacity-60"
    >
        {/* Link Video mới (Ổn định từ Pexels) - Cảnh rót cafe nghệ thuật */}
        <source src={assetUrl('/videos/intro.mp4')} type="video/mp4" />
        
        {/* Link dự phòng nếu link trên lỗi */}
        <source src="https://assets.mixkit.co/videos/preview/mixkit-pouring-milk-in-coffee-close-up-1262-large.mp4" type="video/mp4" />
    </video>
        
        {/* Overlay Gradient */}
        <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/30"></div>

        {/* Content trên Video */}
        <div className="relative z-10 p-16 w-full max-w-3xl flex flex-col justify-between h-full">
            <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/10">
                    <Coffee className="w-5 h-5 text-amber-400" />
                </div>
                <span className="text-white font-bold tracking-[0.2em] text-sm">RINNSAN ADMIN</span>
            </div>

            <div className="space-y-8 animate-fade-up">
                <h1 className="text-6xl font-serif font-bold text-white leading-[1.1]">
                    Kiến tạo <br/> 
                    <span className="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 to-amber-500">
                        Vị ngon hoàn hảo.
                    </span>
                </h1>
                <p className="text-slate-300 text-lg font-light max-w-md leading-relaxed">
                    Hệ thống quản trị tập trung giúp bạn kiểm soát chất lượng từng ly cà phê và tối ưu hóa trải nghiệm khách hàng.
                </p>

                {/* Các thẻ thống kê giả lập (Bento Style nhỏ) */}
                <div className="flex gap-4 pt-4">
                    <div className="glass-stat p-4 rounded-2xl flex items-center gap-4 min-w-[180px]">
                        <div className="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400">
                            <Activity className="w-5 h-5" />
                        </div>
                        <div>
                            <p className="text-2xl font-bold text-white">98%</p>
                            <p className="text-[10px] text-slate-300 uppercase tracking-wider">Hiệu suất</p>
                        </div>
                    </div>
                    <div className="glass-stat p-4 rounded-2xl flex items-center gap-4 min-w-[180px]">
                        <div className="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-400">
                            <Zap className="w-5 h-5" />
                        </div>
                        <div>
                            <p className="text-2xl font-bold text-white">24/7</p>
                            <p className="text-[10px] text-slate-300 uppercase tracking-wider">Hoạt động</p>
                        </div>
                    </div>
                </div>
            </div>

            <div className="text-white/40 text-xs font-medium tracking-wider">
                POWERED BY RINNSAN TECHNOLOGY © 2025
            </div>
        </div>
      </div>

      {/* --- CỘT PHẢI: FORM ĐĂNG NHẬP --- */}
      <div className="w-full lg:w-5/12 flex items-center justify-center p-8 bg-white relative">
        <div className="w-full max-w-[420px] animate-fade-up" style={{ animationDelay: '0.2s' }}>
            
            <div className="mb-10">
                <div className="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-6 shadow-sm">
                    <User className="w-7 h-7" />
                </div>
                <h2 className="text-3xl font-bold text-slate-900 mb-2">Chào mừng trở lại!</h2>
                <p className="text-slate-500">Hãy đăng nhập để bắt đầu ngày làm việc hiệu quả.</p>
            </div>

            <form onSubmit={handleLogin} className="space-y-6">
                
                {/* Username */}
                <div className="group">
                    <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1 group-focus-within:text-amber-600 transition-colors">
                        Tài khoản
                    </label>
                    <div className="relative">
                        <input 
                            type="text" 
                            name="username" 
                            placeholder="Nhập tên đăng nhập"
                            value={formData.username} 
                            onChange={handleChange}
                            className="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block w-full p-4 pl-12 transition-all font-medium placeholder:text-slate-400 focus:bg-white focus:shadow-lg focus:shadow-amber-500/10 outline-none"
                        />
                        <div className="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                            <User className="w-5 h-5" />
                        </div>
                    </div>
                </div>

                {/* Password */}
                <div className="group">
                    <div className="flex justify-between items-center mb-2 px-1">
                        <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider group-focus-within:text-amber-600 transition-colors">
                            Mật khẩu
                        </label>
                        <a href="#" className="text-xs font-semibold text-amber-600 hover:text-amber-700 hover:underline">
                            Quên mật khẩu?
                        </a>
                    </div>
                    <div className="relative">
                        <input 
                            type={showPassword ? "text" : "password"} 
                            name="password" 
                            placeholder="••••••••"
                            value={formData.password} 
                            onChange={handleChange}
                            className="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block w-full p-4 pl-12 pr-12 transition-all font-medium placeholder:text-slate-400 focus:bg-white focus:shadow-lg focus:shadow-amber-500/10 outline-none"
                        />
                        <div className="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                            <Lock className="w-5 h-5" />
                        </div>
                        <button 
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 cursor-pointer transition-colors"
                        >
                            {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                        </button>
                    </div>
                </div>

                {/* Error */}
                {error && (
                    <div className="flex items-center p-4 text-sm text-red-800 border border-red-100 rounded-xl bg-red-50" role="alert">
                        <ShieldCheck className="flex-shrink-0 inline w-5 h-5 mr-3" />
                        <span className="font-medium">{error}</span>
                    </div>
                )}

                {/* Button */}
                <button 
                    type="submit" 
                    disabled={loading}
                    className="w-full text-white bg-slate-900 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-amber-300 font-bold rounded-xl text-base px-5 py-4 text-center transition-all duration-300 transform active:scale-[0.98] shadow-xl hover:shadow-amber-600/30 flex items-center justify-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    {loading ? (
                        <>
                            <Loader2 className="w-5 h-5 animate-spin" />
                            <span>Đang xử lý...</span>
                        </>
                    ) : (
                        <>
                            <span>Đăng nhập hệ thống</span>
                            <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                        </>
                    )}
                </button>
            </form>

            <div className="mt-8 text-center">
                <p className="text-xs text-slate-400">
                    Bạn chưa có tài khoản nhân viên? <br/>
                    <a href="#" className="text-amber-600 font-bold hover:underline">Liên hệ quản lý</a> để được cấp quyền.
                </p>
            </div>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;