<script setup lang="ts">
import type {Article} from '~/types/content';

const props = withDefaults(
  defineProps<{article: Article, featured?: boolean}>(),
  {featured: false},
)

const id = computed(() => {
  return (import.meta.dev || useContentPreview()?.isEnabled()) ? props.article?._id : undefined
})
</script>

<template>
  <article
    v-if="article._path && article.title"
    :class="['flex flex-col gap-4', featured && 'md:flex-row md:gap-8']"
    :data-content-id="id"
  >
    <div v-if="article.cover" class="flex-1 relative">
      <div v-if="article?.badges" class="absolute flex flex-wrap gap-2 mt-2 ml-2">
        <span
          v-for="(badge, index) in article.badges"
          :key="index"
          class="text-xs font-bold px-1 rounded-sm"
          :style="{
            backgroundColor: badge?.bg || 'rgba(0, 0, 0, 0.3)',
            color: badge?.color || 'white'
          }"
        >
          {{ badge.content }}
        </span>
      </div>
      <NuxtLink :to="article._path">
        <NuxtImg
          :src="article.cover"
          :alt="article.title"
          class="w-full aspect-video object-cover rounded-md"
        />
      </NuxtLink>
    </div>

    <div class="flex flex-col flex-1">
      <NuxtLink
        :to="article._path"
        :class="['text-2xl mb-2 font-semibold line-clamp-2', featured && 'text-4xl line-clamp-3']"
      >
        <h1>
          {{ article.title }}
        </h1>
      </NuxtLink>

      <p :class="['mb-4 line-clamp-2', featured && 'line-clamp-4']">
        {{ article.description }}
      </p>
      <time class="text-sm text-gray-500 dark:text-gray-500">
        {{ formatDate(article.date) }}
      </time>
    </div>
  </article>
</template>
