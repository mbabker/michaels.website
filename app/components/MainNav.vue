<script setup lang="ts">
const { data: navigation } = await useAsyncData('navigation', () => queryCollectionNavigation('pages'))
</script>

<template>
    <nav>
        <ul class="flex w-full flex-col justify-center gap-4 sm:flex-row sm:gap-8">
            <li v-for="link of navigation" :key="link.path">
                <NuxtLink :to="link.path" class="[&.router-link-active]:text-primary-500 relative">
                    <span
                        class="absolute -bottom-1 h-px w-0 bg-current transition-all duration-200 ease-in-out hover:w-full"
                    />
                    {{ link.title }}
                </NuxtLink>
            </li>
        </ul>
    </nav>
</template>
