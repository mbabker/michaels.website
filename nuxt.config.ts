export default defineNuxtConfig({
  modules: [
    '@nuxt/content',
    '@nuxt/eslint',
    '@nuxt/icon',
    '@nuxt/image',
    '@nuxtjs/color-mode',
    '@nuxtjs/tailwindcss',
  ],

  compatibilityDate: '2024-10-30',

  devtools: {
    enabled: true,
  },

  future: {
    compatibilityVersion: 4,
  },

  css: [
    './app/assets/css/main.css'
  ],

  colorMode: {
    classSuffix: '',
  },

  tailwindcss: {
    cssPath: './app/assets/css/main.css',
  },

  app: {
    head: {
      link: [
        { rel: 'apple-touch-icon', sizes: '180x180', href: '/apple-touch-icon.png' },
        { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicon-32x32.png' },
        { rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicon-16x16.png' },
        { rel: 'manifest', href: '/site.webmanifest' },
      ]
    }
  }
})
