<script setup>
import Dropdown from "@/Components/Dropdown.vue";

const props = defineProps({
    modelValue: {
        required: false,
    },
    maxWidth: {
        type: Number,
        default: 300,
    },
    filterable: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="flex items-center">
        <div class="flex items-center w-full shadow rounded">
            <Dropdown
                :closeOnClick="false"
                contentClasses="bg-white"
                v-if="filterable"
                class="rounded-l border-r hover:bg-gray-100 hover:text-primaryGray fill-white hover:fill-black focus:border-white focus:ring focus:z-10 bg-primaryGray text-white"
                align="bottom-start"
            >
                <template #trigger>
                    <div
                        class="flex items-center px-4 py-2 md:px-6 cursor-pointer"
                    >
                        <span class="hidden md:inline">Filter</span>
                        <svg
                            class="w-2 h-2 fill-inherit md:ml-2"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 961.243 599.998"
                        >
                            <path
                                d="M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z"
                            />
                        </svg>
                    </div>
                </template>
                <template #content>
                    <div class="mt-2">
                        <slot />
                    </div>
                </template>
            </Dropdown>
            <input
                class="relative w-full dark:bg-primaryGray px-6 py-3 rounded-l focus:ring border-none dark:text-white dark:placeholder-slate-200"
                autocomplete="off"
                type="text"
                name="search"
                placeholder="Search…"
                @input="$emit('update:modelValue', $event.target.value)"
                :value="modelValue"
            />
        </div>
        <button
            class="ml-3 text-sm text-gray-500 hover:text-gray-700 focus:text-indigo-500"
            type="button"
            @click="$emit('reset')"
        >
            Reset
        </button>
    </div>
</template>
