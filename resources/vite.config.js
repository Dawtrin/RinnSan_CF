import react from '@vitejs/plugin-react'
import { resolve } from 'path'

// Vite config for the frontend inside `resources/`.
// Export a config object so `vite` can load it when `cwd` is the `resources` folder.
export default {
  plugins: [react()],
  // project root is `resources` (current working dir) so we do not set `root`
  build: {
    outDir: resolve(__dirname, '..', 'public', 'dist'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: resolve(__dirname, 'index.html')
    }
  },
  server: {
    host: 'localhost',
    port: 5173,
    origin: 'http://localhost:5173',
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true
      }
    }
  }
}
// This configuration is now consolidated to avoid duplication.
// The previous configuration has been removed.