<script setup lang="ts">
import {withLeadingSlash, joinURL} from 'ufo'

const route = useRoute()

const article = useTemplateRef<HTMLElement>('article')

const slug = computed(() => Array.isArray(route.params.slug) ? route.params.slug as string[] : [route.params.slug as string])
const path = computed(() => withLeadingSlash(joinURL('blog', ...slug.value)))

const parentPath = computed(() => {
  const pathTabl = route.path.split('/')
  pathTabl.pop()
  return pathTabl.join('/')
})

const {data: page} = await useAsyncData(
  path.value,
  async () => await queryCollection('blog').path(path.value).first(),
)

if (!page.value) {
  throw createError({statusCode: 404, statusMessage: 'Page not found'})
}

useSeoMeta(page.value?.seo)

if (page.value?.cover) {
  useSeoMeta({
    ogImage: page.value.cover,
  })
}

const onBackToTop = () => {
  article.value?.scrollIntoView({
    behavior: 'smooth'
  })
}
</script>

<template>
  <article v-if="page" ref="article" class="max-w-[68ch] mx-auto py-4 sm:py-12">
    <NuxtLink
      :to="parentPath"
      class="inline-flex items-center text-lg border-b border-gray-200 dark:border-gray-800"
    >
      <Icon name="ph:arrow-left" class="w-4 h-4 mr-2" />
      <span>Back</span>
    </NuxtLink>

    <header class="mt-16 mb-12">
      <h1
        v-if="page?.title"
        class="text-5xl font-semibold mb-4"
      >
        {{ page.title }}
      </h1>
      <time
        v-if="page?.date"
        :datetime="page.date"
        class="text-gray-500 dark:text-gray-400"
      >
        {{ formatDate(page.date) }}
      </time>
    </header>

    <div class="blog-post prose prose-lg prose-h2:text-4xl prose-h2:mt-12 prose-h2:mb-8 prose-a:no-underline dark:prose-invert max-w-none">
      <ContentRenderer :value="page" />
      <div class="flex justify-end items-center w-full">
        <ProseA class="cursor-pointer text-lg" @click.prevent.stop="onBackToTop">
          Back to top
          <Icon name="material-symbols:arrow-upward" />
        </ProseA>
      </div>
    </div>
  </article>
</template>

<style scoped>
/* Hide h1 in prose since we render it in header */
.prose :deep(h1) {
  display: none;
}
</style>
