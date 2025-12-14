<script setup lang="ts">
import type { PropType } from 'vue'

const props = defineProps({
  href: {
    type: String,
    default: ''
  },
  target: {
    type: String as PropType<'_blank' | '_parent' | '_self' | '_top' | (string & object) | null | undefined>,
    default: undefined,
    required: false
  }
})
</script>

<template>
  <NuxtLink
    :href="props.href"
    :target="props.target"
    class="font-medium border-b border-dashed border-current pb-0.5 hover:border-solid hover:text-primary-400 has-[img]:border-0"
  >
    <slot />
  </NuxtLink>
</template>
