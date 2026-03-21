<script setup lang="ts">
import type { BlogCollectionItem } from '@nuxt/content'

withDefaults(defineProps<{ post: BlogCollectionItem; featured?: boolean }>(), { featured: false })
</script>

<template>
    <article
        v-if="post.path && post.title"
        :class="['flex flex-col gap-4', featured && 'md:flex-row md:gap-8']"
        :data-content-id="post.id"
    >
        <div v-if="post.cover" class="relative flex-1">
            <NuxtLink :to="post.path">
                <NuxtImg :src="post.cover" :alt="post.title" class="aspect-video w-full rounded-md object-cover" />
            </NuxtLink>
        </div>

        <div class="flex flex-1 flex-col">
            <NuxtLink
                :to="post.path"
                :class="['mb-2 line-clamp-2 text-2xl font-semibold', featured && 'line-clamp-3 text-4xl']"
            >
                <h1>
                    {{ post.title }}
                </h1>
            </NuxtLink>

            <p :class="['mb-4 line-clamp-2', featured && 'line-clamp-4']">
                {{ post.description }}
            </p>
            <NuxtTime
                :datetime="post.date"
                year="numeric"
                month="long"
                day="numeric"
                locale="en"
                class="text-sm text-gray-500 dark:text-gray-500"
            />
        </div>
    </article>
</template>
