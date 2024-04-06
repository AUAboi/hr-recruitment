<template>
    <div class="fixed top-0 left-0 right-0 bottom-0 w-full h-full">
        <div class="bg-black bg-opacity-70 w-full h-full absolute"></div>
        <div class="flex justify-center items-center relative h-full">
            <slot v-if="$slots.loader" name="loader"></slot>
            <img v-else src="/images/loader.svg" alt="" />
        </div>
    </div>
</template>
