import react from '@vitejs/plugin-react'
import { resolve } from 'path'

export default {
  plugins: [react()],
  
  // QUAN TRỌNG: Chỉ định root directory
  root: resolve(__dirname, 'resources'),
  
  build: {
    outDir: '../public/dist',  // ← Ra ngoài resources
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: resolve(__dirname, 'resources/src/main.jsx'), // ← Full path
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
      }
    }
  }
}