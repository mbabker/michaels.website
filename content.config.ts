import { defineContentConfig, defineCollection } from '@nuxt/content'
import { z } from 'zod'

export default defineContentConfig({
    collections: {
        // Top-level markdown only — `blog/*.md` and `writing/*.md` belong to their own collections below.
        pages: defineCollection({
            type: 'page',
            source: '*.md',
        }),
        // Retired; holds the one 2020 post, kept reachable so its URL does not rot. New posts go in `writing`.
        blog: defineCollection({
            type: 'page',
            source: 'blog/*.md',
            schema: z.object({
                date: z.string(),
                cover: z.string().optional(),
                description: z.string().optional(),
            }),
        }),
        writing: defineCollection({
            type: 'page',
            source: 'writing/*.md',
            schema: z.object({
                date: z.string(),
                cover: z.string().optional(),
                description: z.string().optional(),
            }),
        }),
    },
})
