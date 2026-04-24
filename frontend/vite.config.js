import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx' // Add support for Vue JSX

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueJsx() // Enable Vue JSX support
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  server: {
    host: '0.0.0.0', // Allow external access for development
    port: 5173 // Default Vite port
  },
  build: {
    sourcemap: true // Enable source maps for easier debugging
  }
})
