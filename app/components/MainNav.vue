<script lang="ts" setup>
const { navigation } = useContent()

const emits = defineEmits(['linkClick'])

function handleClick() {
  emits('linkClick')
}
</script>

<template>
  <nav>
    <ul class="flex flex-col w-full justify-center gap-4 sm:flex-row sm:gap-8">
      <li
        v-for="link of navigation"
        :key="link._path"
      >
        <NuxtLink
          :to="link._path"
          class="relative [&.router-link-active]:text-primary-500"
          @click="handleClick"
        >
          <span class="underline-fx absolute bottom-[-4px] w-0 h-px bg-current transition-all duration-200 ease-in-out"/>
          {{ link.title }}
        </NuxtLink>
      </li>
    </ul>
  </nav>
</template>

<style scoped>
a:hover .underline-fx {
  width: 100%;
}
</style>
