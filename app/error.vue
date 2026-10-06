<script setup lang="ts">
import type { NuxtError } from '#app'

const props = defineProps<{ error: NuxtError }>()

// Prerendered to 404.html, which GitHub Pages serves for every missing path, so the 404 case is the
// one most visitors will see. Anything else gets a generic message — the error's own text can carry
// internals and never belongs on the page.
const status = computed(() => props.error.status ?? 500)
const notFound = computed(() => status.value === 404)

const title = computed(() => (notFound.value ? 'Page not found' : 'Something went wrong'))
const description = computed(() =>
    notFound.value
        ? 'There is nothing at this address. It may have moved, or the link that brought you here may be out of date.'
        : 'Something broke on this end while loading the page. Try again in a moment.',
)

// error.vue replaces app.vue rather than rendering inside it, so nothing from there applies here —
// there is no canonical, which is right for a page that has no address of its own.
useSeoMeta({
    title,
    robots: 'noindex',
})
</script>

<template>
    <AppLayout>
        <main class="py-4 sm:py-12">
            <section class="flex max-w-[48ch] flex-col gap-5">
                <div class="text-primary-700 dark:text-primary-500 font-mono text-xs tracking-[0.14em] uppercase">
                    Error {{ status }}
                </div>
                <h1 class="text-4xl leading-tight font-semibold tracking-tight sm:text-5xl">{{ title }}</h1>
                <p class="text-xl leading-relaxed text-gray-600 dark:text-gray-400">{{ description }}</p>
                <p class="mt-4 text-lg font-medium">
                    <NuxtLink
                        to="/"
                        class="hover:text-primary-700 dark:hover:text-primary-500 underline decoration-gray-300 underline-offset-4 dark:decoration-gray-600"
                    >
                        Back to the homepage
                    </NuxtLink>
                </p>
            </section>
        </main>
    </AppLayout>
</template>
