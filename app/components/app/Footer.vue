<template>
  <footer class="flex flex-col items-center mt-24 py-8 md:min-h-36">
    <div class="hidden sm:flex mb-8 text-base font-medium">
      <MainNav />
    </div>

    <p class="text-center mb-4">Follow me on</p>

    <div class="grid w-full grid-cols-12 gap-4">
      <div class="col-span-12 flex justify-center gap-4 xs:col-span-4 xs:col-start-5">
        <SocialIcons />
      </div>
      <div class="col-span-12 flex justify-center xs:col-span-2 xs:col-start-11 xs:justify-end">
        <ColorModeSwitch />
      </div>
    </div>
  </footer>
</template>
