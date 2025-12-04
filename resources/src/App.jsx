import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Header from './components/layout/Header/Header.jsx'
import Footer from './components/layout/Footer/Footer.jsx'

// Import các trang
import Home from './pages/Home.jsx'
import About from './pages/About.jsx'
import Contact from './pages/Contact.jsx'; // Đảm bảo đường dẫn đúng
import Landing from './pages/Landing.jsx'
import AdminSuppliers from './pages/AdminSuppliers.jsx'
import UIDemo from './pages/UIDemo.jsx'

export default function App() {
  return (
    <Router>
      {/* Header nằm ở đây sẽ hiển thị cho tất cả các trang */}
      <Header />
      
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/about" element={<About />} />
        
        {/* --- SỬA DÒNG NÀY --- */}
        {/* Gọi trực tiếp Contact, không cần LayoutWrapper vì App đã có Header/Footer rồi */}
        <Route path="/contact" element={<Contact />} />
        {/* ------------------- */}

        <Route path="/landing" element={<Landing />} />
        <Route path="/admin/suppliers" element={<AdminSuppliers />} />
        <Route path="/ui" element={<UIDemo />} />
        <Route path="*" element={<Home />} />
      </Routes>

      {/* Footer nằm ở đây sẽ hiển thị cho tất cả các trang */}
      <Footer />
    </Router>
  )
}