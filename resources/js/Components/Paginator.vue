<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    links: {
        type: Array,
    },
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap my-2 mx-4 items-center">
            <template v-for="(link, key) in links">
                <div
                    :key="key"
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label"
                />
                <Link
                    :key="key + 1"
                    v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white dark:text-white dark:hover:bg-gray-900"
                    :class="{ 'bg-white dark:bg-gray-900': link.active }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
