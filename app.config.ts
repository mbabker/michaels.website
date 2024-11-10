// https://github.com/nuxt-themes/alpine/blob/main/nuxt.schema.ts
export default defineAppConfig({
  alpine: {
    title: 'Michael\'s Website',
    description: 'Michael Babker\'s Personal Website',
    image: false,
    header: {
      position: 'right',
      logo: false,
    },
    footer: {
      credits: {
        enabled: false,
      },
      navigation: true,
      alignment: 'center',
      message: 'Follow me on'
    },
    socials: {
      bluesky: {
        icon: 'fa6-brands:bluesky',
        label: 'Bluesky',
        href: 'https://bsky.app/profile/mbabker.bsky.social',
      },
      github: {
        icon: 'fa6-brands:github',
        label: 'GitHub',
        href: 'https://github.com/mbabker',
      },
      instagram: {
        icon: 'fa6-brands:instagram',
        label: 'Instagram',
        href: 'https://www.instagram.com/michael.babker/',
      },
      linkedin: {
        icon: 'fa6-brands:linkedin',
        label: 'LinkedIn',
        href: 'https://www.linkedin.com/in/mbabker',
      },
      mastodon: {
        icon: 'fa6-brands:mastodon',
        label: 'Mastodon',
        href: 'https://mastodon.social/@mbabker',
      },
      twitter: {
        icon: 'fa6-brands:x-twitter',
        label: 'X (Formerly Twitter)',
        href: 'https://x.com/mbabker',
      },
    },
  }
})
