<script setup lang="ts">
import { withLeadingSlash, joinURL } from 'ufo'

// Serves every top-level content page from the `pages` collection. `/` has its own component for the
// identity graph it carries, and `/blog/**` is matched by the more specific route, so this handles
// the rest and 404s anything with no matching content.
const route = useRoute()

const slug = computed(() =>
    Array.isArray(route.params.slug) ? (route.params.slug as string[]) : [route.params.slug as string],
)
const path = computed(() => withLeadingSlash(joinURL(...slug.value)))

const { data: page } = await useAsyncData(
    `page-${path.value}`,
    async () => await queryCollection('pages').path(path.value).first(),
)

if (!page.value) {
    throw createError({ statusCode: 404, statusMessage: 'Page not found' })
}

usePageSeo(page.value.seo)
</script>

<template>
    <main v-if="page">
        <div
            :class="[
                'prose prose-lg prose-h2:text-2xl prose-h2:mb-5 prose-a:no-underline dark:prose-invert mx-auto max-w-[68ch] py-4 sm:py-12',
                'content-sections',
                page.meta?.numberedSections && 'content-sections-numbered',
            ]"
        >
            <ContentRenderer :value="page" />
        </div>
    </main>
</template>
