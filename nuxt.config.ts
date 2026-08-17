import tailwindcss from '@tailwindcss/vite'

export default defineNuxtConfig({
    modules: ['@nuxt/content', '@nuxt/eslint', '@nuxt/icon', '@nuxt/image', '@nuxtjs/color-mode'],

    compatibilityDate: '2025-07-15',

    devtools: {
        enabled: true,
    },

    vite: {
        plugins: [tailwindcss()],
    },

    css: ['~/assets/css/main.css'],

    app: {
        head: {
            link: [
                { rel: 'apple-touch-icon', sizes: '180x180', href: '/apple-touch-icon.png' },
                { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicon-32x32.png' },
                { rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicon-16x16.png' },
                { rel: 'manifest', href: '/site.webmanifest' },
            ],
        },
    },

    // Bundle every icon referenced in the source into the client build. The site is
    // statically hosted, so /api/_nuxt_icon does not exist at runtime and any icon not
    // already in the prerendered markup — the ColorScheme-wrapped ones — cannot load.
    icon: {
        clientBundle: {
            scan: true,
        },
    },

    colorMode: {
        classSuffix: '',
    },
})
