<script setup lang="ts">
import type { NuxtLinkProps } from '#app'

withDefaults(defineProps<{ href?: NuxtLinkProps['to']; target?: NuxtLinkProps['target'] }>(), {
    href: '',
    target: undefined,
})
</script>

<template>
    <NuxtLink
        :href="href"
        :target="target"
        class="text-primary-700 hover:text-primary-800 dark:text-primary-500 dark:hover:text-primary-400 border-primary-200 dark:border-primary-800 border-b border-dashed pb-0.5 font-medium hover:border-solid has-[img]:border-0"
    >
        <slot />
    </NuxtLink>
</template>
