<script setup lang="ts">
const {data: posts} = await useAsyncData(
  'blog-list',
  async () => await queryCollection('blog').order('date', 'DESC').all()
)
</script>

<template>
  <div v-if="posts?.length" class="sm:px-12 md:px-0">
    <div class="my-12 md:my-8">
      <BlogListItem :key="posts[0]!.id" :post="posts[0]!" featured />
    </div>
    <div v-if="posts.length > 1" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 md:gap-8">
      <BlogListItem v-for="post in posts.slice(1)" :key="post.id" :post />
    </div>
  </div>
</template>
