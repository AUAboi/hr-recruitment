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
    tags: props.filters.tags ?? [],
});

const reset = () => {
    form.search = null;
    form.tags = [];
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

const handleTagClick = (tag) => {
    const index = form.tags.indexOf(tag);
    if (index === -1) {
        form.tags.push(tag);
    } else {
        form.tags.splice(index, 1);
    }
};

const tags = [
    { value: "AI", label: "AI" },
    { value: "Web", label: "Web" },
    { value: "ML", label: "ML" },
    { value: "UI/UX", label: "UI/UX" },
    { value: "IOT", label: "IOT" },
    { value: "Game Development", label: "Game Development" },
    { value: "3D Model", label: "3D Model" },
];

function timeAgo(dateString) {
    const createdAt = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - createdAt) / 1000);

    const secondsInMinute = 60;
    const secondsInHour = 60 * secondsInMinute;
    const secondsInDay = 24 * secondsInHour;
    const secondsInWeek = 7 * secondsInDay;
    const secondsInMonth = 4 * secondsInWeek; // Approximate month
    const secondsInYear = 12 * secondsInMonth;

    if (diffInSeconds < secondsInDay) return "Today";
    if (diffInSeconds < secondsInDay * 2) return "Yesterday";
    if (diffInSeconds < secondsInWeek)
        return `${Math.floor(diffInSeconds / secondsInDay)} days ago`;
    if (diffInSeconds < secondsInMonth) {
        const weeks = Math.floor(diffInSeconds / secondsInWeek);
        return weeks === 1 ? "A week ago" : `${weeks} weeks ago`;
    }
    if (diffInSeconds < secondsInYear) {
        const months = Math.floor(diffInSeconds / secondsInMonth);
        return months === 1 ? "A month ago" : `${months} months ago`;
    }
    return "More than a year ago";
}
</script>
<template>
    <Head title="Apply to jobs" />
    <div class="my-20 text-center">
        <h2 class="title-text text-7xl">Apply to Jobs</h2>
        <p class="text-slate-300">
            Find your next opporutintiy from our curated list of job positions
        </p>
        <div
            class="flex justify-center items-start gap-4 max-w-screen-xl mx-auto mt-8"
        >
            <div class="flex flex-col align-center text-center gap-4 w-[150px]">
                <h3 class="font-bold text-3xl text-white">2000+</h3>
                <span class="text-sm text-gray-200">Active Jobs</span>
            </div>
            <div class="flex flex-col align-center text-center gap-4 w-[150px]">
                <h3 class="font-bold text-3xl text-white">5000+</h3>
                <span class="text-sm text-gray-200">Compnaies Listing</span>
            </div>
            <div class="flex flex-col align-center text-center gap-4 w-[150px]">
                <h3 class="font-bold text-3xl text-white">1M+</h3>
                <span class="text-sm text-gray-200">Active Users</span>
            </div>
        </div>
    </div>
    <div
        class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl gap-16 sm:gap-y-24 flex flex-col"
    >
        <SearchBox
            class="w-full max-w-[50%] mx-auto"
            v-model="form.search"
            @reset="reset"
        />
        <div>
            <div class="flex items-start justify-start gap-4 mb-8">
                <div
                    class="flex items-center justify-between gap-4 bg-primaryWhite px-6 py-2 rounded-full cursor-pointer"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                    >
                        <path
                            fill="currentColor"
                            d="M15 19.88c.04.3-.06.62-.29.83a.996.996 0 0 1-1.41 0L9.29 16.7a.99.99 0 0 1-.29-.83v-5.12L4.21 4.62a1 1 0 0 1 .17-1.4c.19-.14.4-.22.62-.22h14c.22 0 .43.08.62.22a1 1 0 0 1 .17 1.4L15 10.75zM7.04 5L11 10.06v5.52l2 2v-7.53L16.96 5z"
                        />
                    </svg>
                    <p class="font-semibold">Filter</p>
                </div>

                <div
                    v-for="(tag, index) in tags"
                    :key="index"
                    @click="handleTagClick(tag.value)"
                    :class="
                        form.tags.includes(tag.value)
                            ? 'bg-orange-600 hover:bg-orange-500'
                            : 'bg-stone-900 hover:bg-primaryGray'
                    "
                    class="flex items-center justify-between gap-4 px-6 py-2 rounded-full cursor-pointer transition-all duration-200"
                >
                    <p class="font-semibold text-primaryWhite">
                        {{ tag.value }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Link
                    as="div"
                    v-for="(job, index) in job_listings.data"
                    :href="route('public.jobs.application.create', job.id)"
                    class="shadow bg-[#111] relative group overflow-hidden flex flex-col items-start rounded-lg p-6 cursor-pointer"
                >
                    <div class="text-white">
                        <p class="font-semibold">{{ job.job_title }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="#858585"
                                    d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5"
                                />
                            </svg>
                            <span class="text-textGray text-sm"
                                >Location, LOC</span
                            >
                        </div>
                        <div class="flex items-start gap-2 mt-4">
                            <div
                                class="px-6 py-2 text-xs text-textGray bg-primaryGray rounded-full hover:brightness-150 transition-all duration-200"
                                v-for="(tag, index) in job.tags"
                                :key="index"
                            >
                                <span>{{ tag }}</span>
                            </div>
                        </div>
                        <p class="text-slate-300 line-clamp-2 my-4">
                            {{ job.job_details.company_profile }}
                        </p>
                    </div>
                    <div class="absolute right-4 top-4">
                        <span class="font-semibold text-sm text-primaryOrange"
                            >Full time</span
                        >
                    </div>

                    <div class="self-end mt-4 flex items-center gap-4">
                        <span
                            class="text-sm font-semibold leading-3 text-textGray"
                        >
                            {{ timeAgo(job.created_at) }}</span
                        >
                        <button
                            class="bg-primaryOrange px-8 py-2 rounded-lg text-sm hover:scale-105 transition-all duration-200 active:scale-95"
                        >
                            Apply Now
                        </button>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>
