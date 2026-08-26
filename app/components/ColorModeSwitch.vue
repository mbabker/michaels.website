<script setup lang="ts">
const colorMode = useColorMode()

const onClick = () => {
    const values = ['system', 'light', 'dark']
    const index = values.indexOf(colorMode.preference)
    const next = (index + 1) % values.length

    colorMode.preference = values[next]!
}
</script>

<template>
    <button
        aria-label="Color Mode"
        class="hover:text-primary-700 dark:hover:text-primary-500 flex size-6 transition-colors"
        @click="onClick"
    >
        <ColorScheme>
            <template v-if="colorMode.preference === 'dark'">
                <Icon name="uil:moon" class="size-6 shrink-0" />
                <span class="sr-only">Dark mode</span>
            </template>
            <template v-else-if="colorMode.preference === 'light'">
                <Icon name="uil:sun" class="size-6 shrink-0" />
                <span class="sr-only">Light mode</span>
            </template>
            <template v-else>
                <Icon name="uil:desktop" class="size-6 shrink-0" />
                <span class="sr-only">System mode</span>
            </template>
        </ColorScheme>
    </button>
</template>
