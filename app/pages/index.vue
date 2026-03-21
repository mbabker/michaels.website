<script setup lang="ts">
const { data: page } = await useAsyncData('page-home', async () => await queryCollection('pages').path('/').first())

if (!page.value) {
    throw createError({ statusCode: 404, statusMessage: 'Page not found' })
}

useSeoMeta(page.value?.seo)
</script>

<template>
    <main v-if="page">
        <ContentRenderer :value="page" />
    </main>
</template>
