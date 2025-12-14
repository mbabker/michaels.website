<script setup lang="ts">
import type {BlogCollectionItem} from '@nuxt/content'

withDefaults(
  defineProps<{post: BlogCollectionItem, featured?: boolean}>(),
  {featured: false},
)
</script>

<template>
  <article
    v-if="post.path && post.title"
    :class="['flex flex-col gap-4', featured && 'md:flex-row md:gap-8']"
    :data-content-id="post.id"
  >
    <div v-if="post.cover" class="flex-1 relative">
      <NuxtLink :to="post.path">
        <NuxtImg
          :src="post.cover"
          :alt="post.title"
          class="w-full aspect-video object-cover rounded-md"
        />
      </NuxtLink>
    </div>

    <div class="flex flex-col flex-1">
      <NuxtLink
        :to="post.path"
        :class="['text-2xl mb-2 font-semibold line-clamp-2', featured && 'text-4xl line-clamp-3']"
      >
        <h1>
          {{ post.title }}
        </h1>
      </NuxtLink>

      <p :class="['mb-4 line-clamp-2', featured && 'line-clamp-4']">
        {{ post.description }}
      </p>
      <time class="text-sm text-gray-500 dark:text-gray-500">
        {{ formatDate(post.date) }}
      </time>
    </div>
  </article>
</template>
