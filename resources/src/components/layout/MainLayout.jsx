import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from './Header/Header'; // Đường dẫn tới Header của bạn
import Footer from './Footer/Footer'; // Đường dẫn tới Footer của bạn

const MainLayout = () => {
  return (
    <div className="flex flex-col min-h-screen">
      <Header />
      {/* Outlet là nơi nội dung của các trang con (Home, About...) sẽ hiển thị */}
      <main className="flex-grow">
        <Outlet /> 
      </main>
      <Footer />
    </div>
  );
};

export default MainLayout;