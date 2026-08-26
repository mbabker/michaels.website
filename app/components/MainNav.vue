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
                    class="group relative pb-1 text-gray-500 transition-colors hover:text-gray-800 dark:hover:text-gray-200 [&.router-link-active]:text-gray-800 dark:[&.router-link-active]:text-gray-200"
                    @click="emit('link-click')"
                >
                    <span
                        class="bg-primary-700 dark:bg-primary-500 absolute bottom-0 left-0 h-0.5 w-0 transition-all duration-200 ease-in-out group-hover:w-full group-[.router-link-active]:w-full"
                    />
                    {{ link.title }}
                </NuxtLink>
            </li>
        </ul>
    </nav>
</template>
