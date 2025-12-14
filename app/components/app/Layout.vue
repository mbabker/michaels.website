<script setup lang="ts">
withDefaults(
  defineProps<{padded?: boolean}>(),
  {padded: true},
)

useHead({
  meta: [
    { name: 'twitter:card', content: 'summary_large_image' }
  ]
})

useContentHead({
  title: 'Michael\'s Website',
  description: 'Michael Babker\'s Personal Website',
})
</script>

<template>
  <Container class="min-w-[20rem]">
    <NuxtLoadingIndicator />
    <AppHeader />
    <slot />
    <AppFooter />
  </Container>
</template>
