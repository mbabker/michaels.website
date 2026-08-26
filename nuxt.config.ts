import tailwindcss from '@tailwindcss/vite'

export default defineNuxtConfig({
    modules: ['@nuxt/content', '@nuxt/eslint', '@nuxt/icon', '@nuxt/image', '@nuxtjs/color-mode', '@nuxtjs/sitemap'],

    compatibilityDate: '2025-07-15',

    devtools: {
        enabled: true,
    },

    vite: {
        plugins: [tailwindcss()],
    },

    css: ['~/assets/css/main.css'],

    // Single source of truth for the canonical origin and the site name. Read in app code via
    // useSiteConfig() and used by @nuxtjs/sitemap. Hardcoded rather than driven from an env var
    // because there is no staging deployment — the deploy workflow sets no environment.
    site: {
        url: 'https://michaels.website',
        name: 'Michael Babker',
    },

    app: {
        head: {
            htmlAttrs: {
                lang: 'en',
            },
            titleTemplate: '%s · Michael Babker',
            link: [
                { rel: 'apple-touch-icon', sizes: '180x180', href: '/apple-touch-icon.png' },
                { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicon-32x32.png' },
                { rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicon-16x16.png' },
                { rel: 'manifest', href: '/site.webmanifest' },
            ],
        },
    },

    // The blog is retired. GitHub Pages cannot serve a real 301, so this prerenders to an HTML stub
    // carrying a meta refresh — acceptable for an index page that no longer has content. The one
    // real post stays live at its original path so nothing rots.
    routeRules: {
        '/blog': { redirect: { to: '/', statusCode: 301 } },
    },

    nitro: {
        prerender: {
            // Nothing links to the surviving post any more, so the crawler cannot discover it.
            // Name it explicitly, or retiring the blog index would turn the post into a 404.
            routes: ['/blog/a-fresh-start'],
        },
    },

    // Bundle every icon referenced in the source into the client build. The site is
    // statically hosted, so /api/_nuxt_icon does not exist at runtime and any icon not
    // already in the prerendered markup — the ColorScheme-wrapped ones — cannot load.
    icon: {
        clientBundle: {
            scan: true,
        },

        // Put the generated per-icon CSS in a cascade layer. By default @nuxt/icon injects it
        // unlayered, and unlayered rules beat every layered rule regardless of specificity — so
        // the `width: 1em; height: 1em` it emits silently overrode any Tailwind size utility on an
        // icon, no matter how specific. Sizing only responded to font-size, which is not obvious to
        // anyone reading the markup. Tailwind orders `components` before `utilities`, so naming that
        // layer here lets `size-*` on an icon do what it looks like it does.
        cssLayer: 'components',
    },

    colorMode: {
        classSuffix: '',
    },

    // MDC registers rehype-external-links with no options, so every external link inherits its
    // rel="nofollow" default. This site authors its own links — nothing here is untrusted — and
    // nofollow on the babdev.com links would undercut the whole point of cross-linking the two
    // sites as one identity. Keep the window-safety hints, drop nofollow.
    content: {
        build: {
            markdown: {
                rehypePlugins: {
                    'rehype-external-links': {
                        options: {
                            rel: ['noopener', 'noreferrer'],
                        },
                    },
                },
            },
        },
    },

    // The output is fully static, so there is no runtime to serve the sitemap endpoint;
    // emit it at build time instead.
    sitemap: {
        zeroRuntime: true,
    },
})
