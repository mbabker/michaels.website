<script setup lang="ts">
import { joinURL, withLeadingSlash } from 'ufo'

const route = useRoute()
const siteConfig = useSiteConfig()

const article = useTemplateRef<HTMLElement>('article')

const slug = computed(() =>
    Array.isArray(route.params.slug) ? (route.params.slug as string[]) : [route.params.slug as string],
)
const path = computed(() => withLeadingSlash(joinURL('blog', ...slug.value)))

const parentPath = computed(() => {
    const pathTabl = route.path.split('/')
    pathTabl.pop()
    return pathTabl.join('/')
})

const { data: page } = await useAsyncData(
    path.value,
    async () => await queryCollection('blog').path(path.value).first(),
)

if (!page.value) {
    throw createError({ statusCode: 404, statusMessage: 'Page not found' })
}

usePageSeo(page.value?.seo)

// A post is an article, not the site itself.
useSeoMeta({
    ogType: 'article',
    articlePublishedTime: page.value?.date,
})

if (page.value?.cover) {
    // Cover paths are site-relative; Open Graph and Twitter both need an absolute URL. Set both, or
    // the card image differs by platform — og:image here but the site-wide default on Twitter.
    const coverUrl = new URL(page.value.cover, siteConfig.url).href

    useSeoMeta({
        ogImage: coverUrl,
        twitterImage: coverUrl,
    })
}

const onBackToTop = () => {
    article.value?.scrollIntoView({
        behavior: 'smooth',
    })
}
</script>

<template>
    <main>
        <article v-if="page" ref="article" class="mx-auto max-w-[68ch] py-4 sm:py-12">
            <NuxtLink
                :to="parentPath"
                class="inline-flex items-center border-b border-gray-200 text-lg dark:border-gray-800"
            >
                <Icon name="ph:arrow-left" class="mr-2" />
                <span>Back</span>
            </NuxtLink>

            <header class="mt-16 mb-12">
                <h1 v-if="page?.title" class="mb-4 text-5xl font-semibold">
                    {{ page.title }}
                </h1>
                <NuxtTime
                    v-if="page?.date"
                    :datetime="page.date"
                    year="numeric"
                    month="long"
                    day="numeric"
                    locale="en"
                    class="text-gray-500 dark:text-gray-400"
                />
            </header>

            <div
                class="blog-post prose prose-lg prose-h2:text-4xl prose-h2:mt-12 prose-h2:mb-8 prose-a:no-underline dark:prose-invert max-w-none"
            >
                <ContentRenderer :value="page" />
                <div class="flex w-full items-center justify-end">
                    <ProseA class="cursor-pointer text-lg" @click.prevent.stop="onBackToTop">
                        Back to top
                        <Icon name="material-symbols:arrow-upward" />
                    </ProseA>
                </div>
            </div>
        </article>
    </main>
</template>
