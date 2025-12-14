<script setup lang="ts">
const {data: navigation} = await useAsyncData(
  'navigation',
  () => queryCollectionNavigation('pages'),
);

</script>

<template>
  <nav>
    <ul class="flex flex-col w-full justify-center gap-4 sm:flex-row sm:gap-8">
      <li
        v-for="link of navigation"
        :key="link.path"
      >
        <NuxtLink
          :to="link.path"
          class="relative [&.router-link-active]:text-primary-500"
        >
          <span class="absolute -bottom-1 w-0 hover:w-full h-px bg-current transition-all duration-200 ease-in-out"/>
          {{ link.title }}
        </NuxtLink>
      </li>
    </ul>
  </nav>
</template>
