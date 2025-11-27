import react from '@vitejs/plugin-react'

// Minimal ESM Vite config that exports a plain object.
export default {
  plugins: [react()],
  build: {
    outDir: 'public/dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: 'resources/js/main.jsx',
    },
  },
  server: {
    origin: 'http://localhost:5173',
    host: 'localhost',
    port: 5173
  }
}