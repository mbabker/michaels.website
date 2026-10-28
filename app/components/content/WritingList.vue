<script setup lang="ts">
const { data: posts } = await useAsyncData(
    'writing-list',
    async () => await queryCollection('writing').order('date', 'DESC').all(),
)
</script>

<template>
    <div v-if="posts?.length">
        <!-- Each h2 is its section's first child, so the content-sections divider rule skips it; the
             article draws its own. -->
        <article
            v-for="post in posts"
            :key="post.id"
            class="mt-12 border-t border-gray-200 pt-10 dark:border-gray-700"
            :data-content-id="post.id"
        >
            <h2>
                <NuxtLink :to="post.path">{{ post.title }}</NuxtLink>
            </h2>
            <NuxtTime
                :datetime="post.date"
                year="numeric"
                month="long"
                day="numeric"
                locale="en"
                class="font-mono text-sm text-gray-500 dark:text-gray-400"
            />
            <p v-if="post.description">{{ post.description }}</p>
        </article>
    </div>
</template>
