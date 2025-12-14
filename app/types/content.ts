import type {ParsedContent} from '@nuxt/content';

export interface Article extends ParsedContent {
    date: string
    description: string
    cover?: string
    badges?: { bg: string, text: string, content: string, color?: string }[]
}
