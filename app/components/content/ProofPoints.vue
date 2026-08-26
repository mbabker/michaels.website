<script setup lang="ts">
type ProofPoint = { value: string; label: string }

// Figures are sourced and dated in the evidence inventory; they are content, not decoration, so
// they live in the markdown rather than here.
defineProps<{ items: ProofPoint[] }>()
</script>

<template>
    <dl
        v-if="items?.length"
        class="my-12 grid grid-cols-2 gap-x-8 gap-y-7 border-y border-gray-200 py-7 sm:grid-cols-4 dark:border-gray-700"
    >
        <div v-for="item in items" :key="item.label" class="flex flex-col gap-1.5">
            <dd class="font-mono text-2xl font-medium tracking-tight">{{ item.value }}</dd>
            <dt class="font-mono text-[0.6875rem] leading-relaxed tracking-[0.11em] text-gray-500 uppercase">
                {{ item.label }}
            </dt>
        </div>
    </dl>
</template>
