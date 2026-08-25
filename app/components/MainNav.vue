<script setup lang="ts">
// AppHeader listens for this to dismiss the mobile menu overlay after a navigation.
const emit = defineEmits<{ 'link-click': [] }>()

const { data: navigation } = await useAsyncData('navigation', () => queryCollectionNavigation('pages'))
</script>

<template>
    <nav>
        <ul class="flex w-full flex-col justify-center gap-4 sm:flex-row sm:gap-8">
            <li v-for="link of navigation" :key="link.path">
                <NuxtLink
                    :to="link.path"
                    class="[&.router-link-active]:text-primary-500 relative"
                    @click="emit('link-click')"
                >
                    <span
                        class="absolute -bottom-1 h-px w-0 bg-current transition-all duration-200 ease-in-out hover:w-full"
                    />
                    {{ link.title }}
                </NuxtLink>
            </li>
        </ul>
    </nav>
</template>
