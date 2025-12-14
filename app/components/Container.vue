/* eslint-disable vue/multi-word-component-names */
<script setup lang="ts">
withDefaults(
  defineProps<{as?: keyof HTMLElementTagNameMap, padded?: boolean, fluid?: boolean}>(),
  {as: 'div', padded: true, fluid: false},
)
</script>

<template>
  <component
    :is="as"
    :class="[
      'w-full mx-auto',
      !fluid && 'max-w-container',
      padded && 'px-6 xs:px-8 sm:px-12 md:px-16'
    ]"
  >
    <slot />
  </component>
</template>
