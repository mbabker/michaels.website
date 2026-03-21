<template>
    <footer class="mt-24 flex flex-col items-center py-8 md:min-h-36">
        <div class="mb-8 hidden text-base font-medium sm:flex">
            <MainNav />
        </div>

        <p class="mb-4 text-center">Follow me on</p>

        <div class="grid w-full grid-cols-12 gap-4">
            <div class="xs:col-span-4 xs:col-start-5 col-span-12 flex justify-center gap-4">
                <SocialIcons />
            </div>
            <div class="xs:col-span-2 xs:col-start-11 xs:justify-end col-span-12 flex justify-center">
                <ColorModeSwitch />
            </div>
        </div>
    </footer>
</template>
