<script setup lang="ts">
type SocialItem = {icon: string, label: string, href: string}

const socials: Record<string, SocialItem> = {
  bluesky: {
    icon: 'fa7-brands:bluesky',
    label: 'Bluesky',
    href: 'https://bsky.app/profile/mbabker.bsky.social',
  },
  github: {
    icon: 'fa7-brands:github',
    label: 'GitHub',
    href: 'https://github.com/mbabker',
  },
  instagram: {
    icon: 'fa7-brands:instagram',
    label: 'Instagram',
    href: 'https://www.instagram.com/michael.babker/',
  },
  linkedin: {
    icon: 'fa7-brands:linkedin',
    label: 'LinkedIn',
    href: 'https://www.linkedin.com/in/mbabker',
  },
  mastodon: {
    icon: 'fa7-brands:mastodon',
    label: 'Mastodon',
    href: 'https://mastodon.social/@mbabker',
  },
  twitter: {
    icon: 'fa7-brands:x-twitter',
    label: 'X (Formerly Twitter)',
    href: 'https://x.com/mbabker',
  },
}
</script>

<template>
  <NuxtLink
    v-for="social in socials"
    :key="social.label"
    :title="social.label"
    :aria-label="social.label"
    :href="social.href"
    rel="noopener noreferrer"
    target="_blank"
    class="flex text-2xl w-6 h-6 hover:text-primary-500 transition-colors"
  >
    <Icon v-if="social.icon" :name="social.icon" class="w-full h-full" />
  </NuxtLink>
</template>
