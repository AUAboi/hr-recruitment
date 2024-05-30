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
        <div class="flex flex-wrap my-2 items-center dark:text-white">
            <template v-for="(link, key) in links">
                <div
                    :key="key"
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded"
                    v-html="link.label"
                />
                <Link
                    :key="key + 1"
                    v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-darkBlue-600 hover:text-white dark:hover:bg-white dark:hover:text-gray-500"
                    :class="{
                        'bg-darkBlue-600 text-white dark:bg-white dark:text-gray-500':
                            link.active,
                    }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
