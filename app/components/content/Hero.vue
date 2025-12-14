<script setup lang="ts">
withDefaults(
  defineProps<{image?: string, imageAlt?: string, imagePosition?: string}>(),
  {image: undefined, imageAlt: 'Hero Image', imagePosition: 'right'},
)

defineSlots<{
  title?: (props: Record<string, never>) => any // eslint-disable-line @typescript-eslint/no-explicit-any
  description?: (props: Record<string, never>) => any // eslint-disable-line @typescript-eslint/no-explicit-any
}>();
</script>

<template>
  <section class="hero">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <div class="content">
        <div class="text-4xl font-bold leading-tight">
          <ContentSlot :use="$slots.title" unwrap="p">
            Hero title
          </ContentSlot>
        </div>
        <div class="mt-3 text-xl leading-7">
          <ContentSlot :use="$slots.description" unwrap="p">
            Hero description
          </ContentSlot>
        </div>
      </div>
      <NuxtImg
        v-if="image"
        :class="['w-full aspect-video object-cover rounded-md', imagePosition === 'left' && 'order-first']"
        :src="image"
        :alt="imageAlt"
      />
    </div>
  </section>
</template>
