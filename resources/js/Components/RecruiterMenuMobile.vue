<script setup>
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

const props = defineProps({
    routes: {
        type: Array,
        required: true,
    },
});
</script>
<template>
    <div>
        <div class="flex flex-col text-white">
            <div class="nav-item">
                <Link
                    :class="{
                        'text-orange-400': isUrl(item.slug),
                    }"
                    v-for="(item, index) in routes"
                    :key="index"
                    class="m-2 flex justify-between gap-4"
                    :href="route(item.route)"
                >
                    {{ item.title }}
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.nav-item {
    @apply my-2;
}
</style>
