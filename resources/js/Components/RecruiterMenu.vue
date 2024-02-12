<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import MdiUserMultiple from "~icons/mdi/user-multiple";
import MdiHome from "~icons/mdi/home";
const props = defineProps({
    menuOpen: {
        type: Boolean,
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
        <div class="nav-item">
            <Link
                :class="{ 'text-orange-400': isUrl('dashboard') }"
                class="m-2 flex justify-between gap-4"
                :href="route('recruiter.dashboard')"
            >
                <span v-if="menuOpen">Dashboard</span>
                <MdiHome class="text-xl" />
            </Link>
        </div>
        <div class="nav-item">
            <Link
                :class="{ 'text-orange-400': isUrl('profile') }"
                class="m-2 flex justify-between gap-4"
                :href="route('recruiter.profile.edit')"
            >
                <span v-if="menuOpen"> Profile </span>
                <MdiUserMultiple class="text-xl" />
            </Link>
        </div>
    </div>
</template>

<style scoped>
.nav-item {
    @apply my-2;
}
</style>
