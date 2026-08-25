<script setup lang="ts">
const siteConfig = useSiteConfig()

const { data: page } = await useAsyncData('page-home', async () => await queryCollection('pages').path('/').first())

if (!page.value) {
    throw createError({ statusCode: 404, statusMessage: 'Page not found' })
}

const siteUrl = siteConfig.url
const personId = `${siteUrl}/#michael`
const organizationId = 'https://happydog.digital/#organization'

// Every profile this identity is reachable through. Keep in sync with SocialIcons.vue, and with the
// matching array on babdev.com so both sites describe the same person.
const sameAs = [
    'https://github.com/mbabker',
    'https://www.linkedin.com/in/mbabker',
    'https://bsky.app/profile/mbabker.bsky.social',
    'https://x.com/mbabker',
    'https://www.instagram.com/michael.babker/',
    'https://www.babdev.com',
    'https://keybase.io/mbabker',
]

const identityGraph = {
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type': 'ProfilePage',
            '@id': `${siteUrl}/#profile-page`,
            url: `${siteUrl}/`,
            name: page.value.seo?.title,
            description: page.value.description,
            inLanguage: 'en-US',
            mainEntity: { '@id': personId },
        },
        {
            '@type': 'Person',
            '@id': personId,
            name: 'Michael Babker',
            givenName: 'Michael',
            familyName: 'Babker',
            jobTitle: 'Lead Engineer',
            description: page.value.description,
            url: `${siteUrl}/`,
            image: new URL('/michael-2021.webp', siteUrl).href,
            worksFor: { '@id': organizationId },
            knowsAbout: [
                'PHP',
                'Symfony',
                'Sylius',
                'Laravel',
                'Go',
                'Kubernetes',
                'PostgreSQL',
                'MySQL',
                'Observability',
                'Open source maintenance',
            ],
            sameAs,
        },
        {
            '@type': 'Organization',
            '@id': organizationId,
            name: 'Happy Dog',
            url: 'https://happydog.digital',
        },
    ],
}

usePageSeo(page.value.seo)

useSeoMeta({
    ogType: 'profile',
})

useHead({
    // The site-wide template appends the name, but this page is the identity page — its own title
    // already carries it.
    titleTemplate: '%s',
    meta: [
        { property: 'og:profile:first_name', content: 'Michael' },
        { property: 'og:profile:last_name', content: 'Babker' },
        { property: 'og:profile:username', content: 'mbabker' },
        { property: 'og:profile:gender', content: 'male' },
    ],
    script: [{ type: 'application/ld+json', textContent: JSON.stringify(identityGraph) }],
})
</script>

<template>
    <main v-if="page">
        <ContentRenderer :value="page" />
    </main>
</template>
