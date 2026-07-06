import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, Outlet } from 'react-router-dom';

// Layout
import Header from './components/layout/Header/Header.jsx';
import Footer from './components/layout/Footer/Footer.jsx';
import ScrollToTop from './components/ScrollToTop.jsx';

// Public Pages
import Home from './pages/Home.jsx';
import About from './pages/About.jsx';
import Contact from './pages/Contact.jsx';
import MenuPage from './pages/MenuPage.jsx';
import Landing from './pages/Landing.jsx';
import Story from './pages/Story.jsx';
import Workshop from './pages/Workshop.jsx';
import CartPage from './pages/CartPage.jsx';
import Checkout from './pages/Checkout.jsx';
import OrderSuccessPage from './pages/OrderSuccessPage.jsx';
import UIDemo from './pages/UIDemo.jsx';
import LoginPage from './pages/LoginPage.jsx';

// Layout cho Khách (Có Header/Footer)
const PublicLayout = () => {
  return (
    <>
      <Header />
      <main className="min-h-screen">
        <Outlet />
      </main>
      <Footer />
    </>
  );
};

export default function App() {
  return (
    <Router>
      <ScrollToTop />
      <Routes>
        
        {/* --- NHÓM KHÁCH HÀNG --- */}
        <Route element={<PublicLayout />}>
          <Route path="/" element={<Home />} />
          <Route path="/about" element={<About />} />
          <Route path="/menu" element={<MenuPage />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/story" element={<Story />} />
          <Route path="/workshop" element={<Workshop />} />
          <Route path="/landing" element={<Landing />} />
          <Route path="/cart" element={<CartPage />} />
          <Route path="/checkout" element={<Checkout />} />
          <Route path="/order-success" element={<OrderSuccessPage />} />
          <Route path="/ui" element={<UIDemo />} />
        </Route>

        {/* --- TRANG LOGIN (demo UI) --- */}
        <Route path="/admin/login" element={<LoginPage />} />
        <Route path="/admin/*" element={<Navigate to="/admin/login" replace />} />

        {/* --- 404 --- */}
        <Route path="*" element={<Navigate to="/" replace />} />

      </Routes>
    </Router>
  );
}