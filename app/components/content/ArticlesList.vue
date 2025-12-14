<script setup lang="ts">
import type {Article} from '~/types/content';
import {withTrailingSlash} from 'ufo'

const props = withDefaults(
  defineProps<{path?: string}>(),
  {path: 'articles'},
)

const {data: articles} = await useAsyncData(
  props.path,
  async () => await queryContent<Article>(withTrailingSlash(props.path)).sort({date: -1}).find()
)
</script>

<template>
  <div v-if="articles?.length" class="articles-list sm:px-12 md:px-0">
    <div class="featured my-12 md:my-8">
      <ArticlesListItem :key="articles[0]!._id" :article="articles[0]!" featured />
    </div>
    <div v-if="articles.length > 1" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 md:gap-8">
      <ArticlesListItem v-for="article in articles.slice(1)" :key="article._id" :article="article" />
    </div>
  </div>
</template>
