<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    job_listings: {
        required: true,
    },
});

const open = ref(false);

const activeJobIndex = ref(0);

const openDrawer = (index) => {
    open.value = true;
    activeJobIndex.value = index;
};

const close = () => {
    console.log("closeing");
};
</script>
<template>
    <Head title="Apply to jobs" />
    <div class="my-20">
        <h2 class="title-text text-4xl">Apply to Jobs</h2>
    </div>
    <div
        class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl gap-16 sm:gap-y-24 flex flex-col"
    >
        <div class="grid grid-cols-2 gap-4">
            <div
                v-for="(job, index) in job_listings"
                @click="openDrawer(index)"
                class="divide-y divide-gray-800 ring-1 ring-gray-800 shadow bg-black relative group overflow-hidden flex items-center rounded-lg px-2 py-6 cursor-pointer"
            >
                <div class="text-white">
                    <p class="font-semibold">{{ job.job_title }}</p>
                    <p class="text-slate-300">{{ job.short_description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
html,
body,
#app,
.page-container {
    height: 100%;
}
</style>
