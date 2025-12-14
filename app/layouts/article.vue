<script setup lang="ts">
const {page} = useContent()
const route = useRoute()

const article = ref<HTMLElement | null>(null)

if (page.value?.cover) {
  useHead({
    meta: [
      { property: 'og:image', content: page.value.cover }
    ]
  })
}

const parentPath = computed(() => {
  const pathTabl = route.path.split('/')
  pathTabl.pop()
  return pathTabl.join('/')
})

const onBackToTop = () => {
  article.value?.scrollIntoView({
    behavior: 'smooth'
  })
}
</script>

<template>
  <article ref="article" class="max-w-[68ch] mx-auto py-4 sm:py-12">
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

    <div class="prose prose-lg dark:prose-invert max-w-none">
      <slot />
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
