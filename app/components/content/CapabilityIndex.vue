<script setup lang="ts">
type Capability = { title: string; description: string; href: string }

withDefaults(defineProps<{ title?: string; items: Capability[] }>(), {
    title: undefined,
})
</script>

<template>
    <section v-if="items?.length" class="my-14">
        <h2 v-if="title" class="mb-6 text-2xl font-semibold tracking-tight">{{ title }}</h2>
        <ol class="border-t border-gray-200 dark:border-gray-700">
            <li v-for="(item, index) in items" :key="item.href" class="border-b border-gray-200 dark:border-gray-700">
                <NuxtLink :to="item.href" class="group flex gap-5 py-5 sm:gap-8">
                    <span class="pt-1 font-mono text-xs tracking-[0.1em] text-gray-400 tabular-nums">
                        {{ String(index + 1).padStart(2, '0') }}
                    </span>
                    <span class="flex flex-col gap-1">
                        <span
                            class="group-hover:text-primary-700 dark:group-hover:text-primary-500 text-lg font-medium transition-colors"
                        >
                            {{ item.title }}
                        </span>
                        <span class="text-gray-600 dark:text-gray-400">{{ item.description }}</span>
                    </span>
                </NuxtLink>
            </li>
        </ol>
    </section>
</template>
