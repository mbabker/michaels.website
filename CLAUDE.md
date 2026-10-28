# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Personal website at [michaels.website](https://michaels.website), built with Nuxt 4 and forked from the [Alpine theme](https://github.com/clemcode/alpine-theme). Uses Nuxt Content v3 for markdown-driven pages and blog posts.

## Commands

- `pnpm dev` — start dev server on http://localhost:3000
- `pnpm build` — production build
- `pnpm generate` — static site generation
- `pnpm lint` — ESLint check
- `pnpm lint:fix` — ESLint auto-fix
- `pnpm format` — Prettier format all files
- `pnpm format:check` — Prettier check formatting

No test framework is configured.

## Architecture

**Nuxt 4 with app/ directory structure.** All application code lives under `app/`:

- `app/pages/` — file-based routing (`index.vue` for `/`, `[...slug].vue` for the other top-level pages such as `/work`, `/open-source` and the `/writing` index, `writing/[slug].vue` for posts, `blog/[...slug].vue` for the retired blog's one post)
- `app/components/` — auto-imported Vue components
  - `app/` subfolder: layout shell (`Header`, `Footer`, `Layout`)
  - `content/` subfolder: content rendering (`Hero`, `ProofPoints`, `CapabilityIndex`, `ProseA`, `ProseP`, `WritingList`)
  - Root level: shared UI (`Container`, `MainNav`, `ColorModeSwitch`, `SocialIcons`, `ArticleView` — the post layout and article metadata shared by `writing` and `blog`)
- `app/assets/css/main.css` — Tailwind CSS v4 with custom theme (primary color palette, gray palette, breakpoints)

**Content collections** defined in `content.config.ts`:
- `pages` collection: sources every top-level `*.md` (`1.index.md`, `2.work.md`, `3.open-source.md`, `4.writing.md`); the main nav is built from it
- `writing` collection: sources `writing/*.md`, schema has `date` (required), `cover` and `description` (optional). New posts go here; `4.writing.md` lists them via `::writing-list`, so they need no prerender entry
- `blog` collection: same schema, retired. Holds only the 2020 post, kept reachable at `/blog/a-fresh-start` (named in `prerender.routes`) while `/blog` redirects to `/`. Don't add to it — the site plan rules out relaunching a "blog"

Content files live in `content/` with numeric prefixes for ordering.

**Key modules:** `@nuxt/content`, `@nuxt/eslint`, `@nuxt/fonts`, `@nuxt/icon`, `@nuxt/image`, `@nuxtjs/color-mode`, `@nuxtjs/sitemap`

**SEO and metadata.** `site.url` and `site.name` in `nuxt.config.ts` are the single source of truth, read in app code via `useSiteConfig()`. `app.vue` sets the route-derived canonical, `og:url`, and default `og:image` for every page; individual pages override `og:*` as needed. `app/pages/index.vue` carries the `Person` + `ProfilePage` JSON-LD and the `og:profile:*` tags.

Page titles and descriptions come from Nuxt Content v3 frontmatter — a top-level `description` plus an optional `seo: { title, description }` block, applied by `usePageSeo()` in `app/composables/usePageSeo.ts`. **Not** a `head:` block: that is Content v2 syntax and v3 silently discards it into `meta`.

## Code Style

- Prettier: no semicolons, single quotes, 4-space indent, 120 char line width, trailing commas, no parens on single arrow params
- ESLint extends Nuxt config with `vue/multi-word-component-names` disabled
- Tailwind CSS v4 (uses `@import 'tailwindcss'` and `@theme` syntax, not v3 config file)
- Dark mode via `@nuxtjs/color-mode` with class strategy (no suffix)

## Requirements

Node >= 24.15.0, pnpm >= 12.16.1
