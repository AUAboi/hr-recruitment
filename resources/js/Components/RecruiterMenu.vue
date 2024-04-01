<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import MdiUserMultiple from "~icons/mdi/user-multiple";
import MdiHome from "~icons/mdi/home";
import MdiBriefcase from "~icons/mdi/briefcase";

const props = defineProps({
    menuOpen: {
        type: Boolean,
        required: true,
    },
    routes: {
        type: Array,
        required: true,
    },
});

const page = usePage();

const isUrl = (...urls) => {
    let currentUrl = page.url.substring(1);
    currentUrl = currentUrl.replace("recruiter/", "");
    if (urls[0] === "") {
        return currentUrl === "";
    }
    return urls.filter((url) => currentUrl.startsWith(url)).length;
};
</script>
<template>
    <div class="flex flex-col text-white">
        <div v-for="item in routes" class="nav-item">
            <Link
                :class="{ 'text-orange-400': isUrl(item.slug) }"
                class="m-2 flex justify-between gap-4"
                :href="route(item.route)"
            >
                <span v-if="menuOpen">{{ item.title }}</span>
                <component class="text-xl" :is="item.icon"></component>
            </Link>
        </div>
    </div>
</template>

<style scoped>
.nav-item {
    @apply my-2;
}
</style>
