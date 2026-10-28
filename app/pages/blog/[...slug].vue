<script setup lang="ts">
import { joinURL, withLeadingSlash } from 'ufo'

const route = useRoute()

const slug = computed(() =>
    Array.isArray(route.params.slug) ? (route.params.slug as string[]) : [route.params.slug as string],
)
const path = computed(() => withLeadingSlash(joinURL('blog', ...slug.value)))

const { data: page } = await useAsyncData(
    path.value,
    async () => await queryCollection('blog').path(path.value).first(),
)

if (!page.value) {
    throw createError({ status: 404, statusText: 'Page not found', fatal: true })
}
</script>

<template>
    <ArticleView v-if="page" :page />
</template>
