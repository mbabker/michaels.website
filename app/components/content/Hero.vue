<script setup lang="ts">
withDefaults(defineProps<{ image?: string; imageAlt?: string; imagePosition?: string }>(), {
    image: undefined,
    imageAlt: 'Hero Image',
    imagePosition: 'right',
})

defineSlots<{
    eyebrow?: (props: Record<string, never>) => any // eslint-disable-line @typescript-eslint/no-explicit-any
    title?: (props: Record<string, never>) => any // eslint-disable-line @typescript-eslint/no-explicit-any
    description?: (props: Record<string, never>) => any // eslint-disable-line @typescript-eslint/no-explicit-any
}>()
</script>

<template>
    <section class="hero">
        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2">
            <div class="content flex flex-col gap-5">
                <div
                    v-if="$slots.eyebrow"
                    class="text-primary-700 dark:text-primary-500 font-mono text-xs tracking-[0.14em] uppercase"
                >
                    <slot name="eyebrow" mdc-unwrap="p" />
                </div>
                <h1 class="text-4xl leading-tight font-semibold tracking-tight sm:text-5xl">
                    <slot name="title" mdc-unwrap="p"> Hero title </slot>
                </h1>
                <div class="max-w-[34ch] text-xl leading-relaxed text-gray-600 dark:text-gray-400">
                    <slot name="description" mdc-unwrap="p"> Hero description </slot>
                </div>
            </div>
            <NuxtImg
                v-if="image"
                :class="['aspect-video w-full rounded-md object-cover', imagePosition === 'left' && 'order-first']"
                :src="image"
                :alt="imageAlt"
            />
        </div>
    </section>
</template>
