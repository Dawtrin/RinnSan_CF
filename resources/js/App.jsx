import './App.css'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/about" element={<About />} />
      </Routes>
    </Router>
  )
}

function Home() {
  return (
    <div className="container">
      <h1>Đây là Trang chủ!</h1>
      <p>React App đã được khởi chạy thành công.</p>
    </div>
  )
}

function About() {
  return (
    <div className="container">
      <h1>Đây là trang Giới thiệu (About)!</h1>
      <p>Thông tin về dự án của bạn.</p>
    </div>
  )
}

export default App
