<script setup lang="ts">
const {data: page} = await useAsyncData(
  'page-blog-list',
  async () => await queryCollection('pages').path('/blog').first(),
)

if (!page.value) {
  throw createError({statusCode: 404, statusMessage: 'Page not found'})
}

useSeoMeta(page.value?.seo)
</script>

<template>
  <main v-if="page">
    <ContentRenderer :value="page" />
  </main>
</template>
