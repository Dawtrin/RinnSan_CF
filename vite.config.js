import react from '@vitejs/plugin-react'
import { resolve } from 'path'

export default {
  plugins: [react()],

  envDir: resolve(__dirname),
  root: resolve(__dirname, 'resources'),
  // Copy ảnh/video từ public/ vào dist khi build (FE-only deploy)
  publicDir: resolve(__dirname, 'public'),

  build: {
    outDir: resolve(__dirname, 'dist'),
    emptyOutDir: true,
    rollupOptions: {
      input: resolve(__dirname, 'resources/index.html'),
    },
  },
  
  server: {
    origin: 'http://localhost:5173',
    host: 'localhost',
    port: 5173,
    // Thêm proxy cho API
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      '/images': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      '/videos': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      '/dist': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    }
  }
}