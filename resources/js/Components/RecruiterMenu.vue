<script setup>
import { onMounted, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

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
                class="m-2"
                :href="route('recruiter.dashboard')"
                >Dashboard</Link
            >
        </div>
        <div class="nav-item">
            <Link
                :class="{ 'text-orange-400': isUrl('profile') }"
                class="m-2"
                :href="route('recruiter.profile.edit')"
                >Profile</Link
            >
        </div>
    </div>
</template>

<style scoped>
.nav-item {
    @apply my-2;
}
</style>
