export default defineNuxtConfig({
  modules: ['@vite-pwa/nuxt'],

  pwa: {
    manifest: {
      name: 'Meu App',
      short_name: 'MeuApp',
      theme_color: '#ffffff',
      background_color: '#ffffff',
      display: 'standalone'
    }
  }
})