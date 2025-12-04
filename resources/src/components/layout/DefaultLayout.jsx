import Header from './Header/Header.jsx'
import Footer from './Footer/Footer.jsx'

export default function DefaultLayout({ children }) {
  return (
    <div className="min-h-screen flex flex-col bg-white">
      <Header />
      <main className="flex-1">{children}</main>
      <Footer />
    </div>
  )
}

