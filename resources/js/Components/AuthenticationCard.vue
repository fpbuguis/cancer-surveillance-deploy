<template>
    <div class="w-full h-full flex flex-col sm:justify-center items-start pt-6 sm:pt-0 bg-white">
    <!-- <div class="w-full h-full flex flex-col sm:justify-center items-start pl-20 pt-6 sm:pt-0 bg-white"> -->
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md px-6 py-4 overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
