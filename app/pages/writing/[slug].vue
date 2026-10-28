<script setup lang="ts">
// A single segment, not a catch-all: a catch-all here would also match `/writing` itself and steal the
// index page from the `pages` collection.
const route = useRoute()

const path = computed(() => `/writing/${route.params.slug}`)

const { data: page } = await useAsyncData(
    path.value,
    async () => await queryCollection('writing').path(path.value).first(),
)

if (!page.value) {
    throw createError({ status: 404, statusText: 'Page not found', fatal: true })
}
</script>

<template>
    <ArticleView v-if="page" :page />
</template>
