# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Personal website at [michaels.website](https://michaels.website), built with Nuxt 4 and forked from the [Alpine theme](https://github.com/clemcode/alpine-theme). Uses Nuxt Content v3 for markdown-driven pages and blog posts.

## Commands

- `npm run dev` — start dev server on http://localhost:3000
- `npm run build` — production build
- `npm run generate` — static site generation
- `npm run lint` — ESLint check
- `npm run lint:fix` — ESLint auto-fix
- `npm run format` — Prettier format all files
- `npm run format:check` — Prettier check formatting

No test framework is configured.

## Architecture

**Nuxt 4 with app/ directory structure.** All application code lives under `app/`:

- `app/pages/` — file-based routing (`index.vue`, `blog/index.vue`, `blog/[...slug].vue`)
- `app/components/` — auto-imported Vue components
  - `app/` subfolder: layout shell (`Header`, `Footer`, `Layout`)
  - `content/` subfolder: content rendering (`Hero`, `BlogList`, `BlogListItem`, `ProseA`, `ProseP`)
  - Root level: shared UI (`Container`, `MainNav`, `ColorModeSwitch`, `SocialIcons`)
- `app/layouts/default.vue` — minimal layout (just a `<slot />`)
- `app/assets/css/main.css` — Tailwind CSS v4 with custom theme (primary color palette, gray palette, breakpoints)

**Content collections** defined in `content.config.ts`:
- `pages` collection: sources `1.index.md` and `2.blog.md`
- `blog` collection: sources `blog/*.md`, schema has `date` (required), `cover` and `description` (optional)

Content files live in `content/` with numeric prefixes for ordering.

**Key modules:** `@nuxt/content`, `@nuxt/eslint`, `@nuxt/icon`, `@nuxt/image`, `@nuxtjs/color-mode`

## Code Style

- Prettier: no semicolons, single quotes, 4-space indent, 120 char line width, trailing commas, no parens on single arrow params
- ESLint extends Nuxt config with `vue/multi-word-component-names` disabled
- Tailwind CSS v4 (uses `@import 'tailwindcss'` and `@theme` syntax, not v3 config file)
- Dark mode via `@nuxtjs/color-mode` with class strategy (no suffix)

## Requirements

Node >= 24.11.0, npm >= 11.6.1
