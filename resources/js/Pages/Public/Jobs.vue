<script setup>
import SearchBox from "@/Components/SearchBox.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { watchThrottled } from "@vueuse/core";
import { reactive } from "vue";

const props = defineProps({
    job_listings: {
        required: true,
    },
    filters: {
        type: Object,
    },
});

const form = reactive({
    search: props.filters.search,
});

const reset = () => {
    form.search = null;
};

watchThrottled(
    form,
    () => {
        router.get(route(route().current()), form, {
            preserveState: true,
            preserveScroll: true,
        });
    },
    { throttle: 500, deep: true }
);
</script>
<template>
    <Head title="Apply to jobs" />
    <div class="my-20 text-center">
        <h2 class="title-text text-4xl">Apply to Jobs</h2>
    </div>
    <div
        class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl gap-16 sm:gap-y-24 flex flex-col"
    >
        <SearchBox class="w-full" v-model="form.search" @reset="reset" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Link
                as="div"
                v-for="(job, index) in job_listings.data"
                :href="route('public.jobs.application.create', job.id)"
                class="divide-y divide-gray-800 ring-1 ring-gray-800 shadow bg-black relative group overflow-hidden flex items-center rounded-lg px-2 py-6 cursor-pointer"
            >
                <div class="text-white">
                    <p class="font-semibold">{{ job.job_title }}</p>
                    <p class="text-slate-300">{{ job.short_description }}</p>
                </div>
            </Link>
        </div>
    </div>
</template>
