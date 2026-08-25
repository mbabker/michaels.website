<script setup lang="ts">
const route = useRoute()
const siteConfig = useSiteConfig()

// Canonical and og:url are purely route-derived, so they belong here once rather than in every page.
// Pages that need to override og:* (the homepage's profile type, a post's cover image) can still do
// so — Unhead dedupes by property and child setup runs after this one.
const canonicalUrl = computed(() => new URL(route.path, siteConfig.url).href)
const defaultOgImage = computed(() => new URL('/og-default.jpg', siteConfig.url).href)

useHead({
    link: [{ rel: 'canonical', href: canonicalUrl }],
})

useSeoMeta({
    ogSiteName: siteConfig.name,
    ogType: 'website',
    ogUrl: canonicalUrl,
    ogImage: defaultOgImage,
    twitterCard: 'summary_large_image',
    twitterImage: defaultOgImage,
})
</script>

<template>
    <AppLayout>
        <NuxtPage />
    </AppLayout>
</template>
