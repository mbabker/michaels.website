import type { Config } from 'tailwindcss'

export default <Partial<Config>>{
  darkMode: 'class',
  content: [
    './app/components/**/*.{vue,js,ts}',
    './app/layouts/**/*.vue',
    './app/pages/**/*.vue',
    './app/app.vue',
    './content/**/*.md',
  ],
  theme: {
    extend: {
      screens: {
        'xs': '475px',  // Alpine custom breakpoint
      },
      colors: {
        primary: {
          50: '#d9f8ff',
          100: '#b3f1ff',
          200: '#8deaff',
          300: '#66e4ff',
          400: '#40ddff',
          500: '#1ad6ff',  // Alpine lightblue primary
          600: '#00b9e1',
          700: '#008aa9',
          800: '#005c70',
          900: '#002e38',
        },
        gray: {
          50: '#fafafa',
          100: '#f4f4f5',
          200: '#e4e4e7',
          300: '#d4d4d8',
          400: '#a1a1aa',
          500: '#71717a',
          600: '#52525b',
          700: '#3f3f46',
          800: '#27272a',
          900: '#18181b',
        }
      },
      maxWidth: {
        'container': '64rem',  // Alpine container.maxWidth
      },
      typography: {
        DEFAULT: {
          css: {
            maxWidth: '68ch',  // Alpine readableLine
          },
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'), // eslint-disable-line @typescript-eslint/no-require-imports
  ],
}
