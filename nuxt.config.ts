export default defineNuxtConfig({
  extends: '@nuxt-themes/alpine',
  modules: ['@nuxt/devtools', '@nuxt/eslint'],
  compatibilityDate: '2024-10-30',
  future: {
    compatibilityVersion: 4,
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
