import { defineContentConfig, defineCollection } from '@nuxt/content'
import { z } from 'zod'

export default defineContentConfig({
    collections: {
        // Top-level markdown only — `blog/*.md` belongs to the blog collection below.
        pages: defineCollection({
            type: 'page',
            source: '*.md',
        }),
        blog: defineCollection({
            type: 'page',
            source: 'blog/*.md',
            schema: z.object({
                date: z.string(),
                cover: z.string().optional(),
                description: z.string().optional(),
            }),
        }),
    },
})
