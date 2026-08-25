import { defineContentConfig, defineCollection } from '@nuxt/content'
import { z } from 'zod'

export default defineContentConfig({
    collections: {
        pages: defineCollection({
            type: 'page',
            source: [{ include: '1.index.md' }],
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
