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
        class="hover:text-primary-400 border-b border-dashed border-current pb-0.5 font-medium hover:border-solid has-[img]:border-0"
    >
        <slot />
    </NuxtLink>
</template>
