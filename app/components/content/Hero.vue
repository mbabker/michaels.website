<script setup lang="ts">
withDefaults(defineProps<{ image?: string; imageAlt?: string; imagePosition?: string }>(), {
    image: undefined,
    imageAlt: 'Hero Image',
    imagePosition: 'right',
})

defineSlots<{
    title?: (props: Record<string, never>) => any // eslint-disable-line @typescript-eslint/no-explicit-any
    description?: (props: Record<string, never>) => any // eslint-disable-line @typescript-eslint/no-explicit-any
}>()
</script>

<template>
    <section class="hero">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="content">
                <h1 class="text-4xl leading-tight font-bold">
                    <slot name="title" mdc-unwrap="p"> Hero title </slot>
                </h1>
                <div class="mt-3 text-xl leading-7">
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
