import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.jsx'
import './styles/globals.css'
import { ConfigProvider } from 'antd'
import 'antd/dist/reset.css'

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <ConfigProvider
      theme={{
        token: {
          colorPrimary: '#b45309'
        }
      }}
    >
      <App />
    </ConfigProvider>
  </React.StrictMode>
)
