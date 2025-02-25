<script setup>
import SearchBox from "@/Components/SearchBox.vue";
import Select from "@/Components/ui/select/Select.vue";
import SelectContent from "@/Components/ui/select/SelectContent.vue";
import SelectGroup from "@/Components/ui/select/SelectGroup.vue";
import SelectItem from "@/Components/ui/select/SelectItem.vue";
import SelectLabel from "@/Components/ui/select/SelectLabel.vue";
import SelectTrigger from "@/Components/ui/select/SelectTrigger.vue";
import SelectValue from "@/Components/ui/select/SelectValue.vue";
import { Head, Link, router, usePoll } from "@inertiajs/vue3";
import { watchThrottled } from "@vueuse/core";
import axios from "axios";
import { onMounted, reactive, ref, watch } from "vue";

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
    country: props.filters.country,
    city: props.filters.city,
    type: props.filters.type,
});

const reset = () => {
    form.search = null;
    form.tags = [];
    form.country = null;
    form.city = null;
    form.type = null;
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
    form.tags = form.tags.includes(tag)
        ? form.tags.filter((t) => t !== tag) // Remove if already in the array
        : [...form.tags, tag]; // Add if not in the array
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

usePoll(2000);

const countryData = ref([]);
const fetchCountries = async () => {
    try {
        const response = await axios.get("https://restcountries.com/v3.1/all");
        countryData.value = response.data
            .map((country) => ({
                name: country.name.common,
                code: country.cca2,
            }))
            .sort((a, b) => a.name.localeCompare(b.name)); // Sort alphabetically
    } catch (error) {
        console.error("Error fetching countries:", error);
    }
};

const cities = ref([]);
const fetchCities = async () => {
    if (!form.country) {
        console.log("failed");
        return;
    }

    try {
        const response = await axios.get(`/${form.country}/cities`);

        cities.value = response.data.geonames.map((city) => ({
            id: city.geonameId,
            name: city.name,
        }));

        console.log(cities.value);
    } catch (error) {
        console.error("Error fetching cities:", error);
    }
};

onMounted(async () => {
    await fetchCountries();
    await fetchCities();
});
watch(
    () => form.country,
    () => {
        if (form.country) {
            form.city = null;
            fetchCities();
        }
    }
);
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
            <div class="flex items-start justify-start gap-4 mb-4">
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
                    class="flex items-center justify-between bg-stone-900 hover:bg-primaryGray gap-4 px-6 py-2 rounded-full cursor-pointer transition-all duration-200"
                >
                    <p class="font-semibold text-primaryWhite">
                        {{ tag.value }}
                    </p>
                </div>
            </div>
            <div class="flex items-start justify-start gap-4 mb-8">
                <div
                    v-for="(tag, index) in form.tags"
                    :key="index"
                    @click="handleTagClick(tag)"
                    :class="
                        form.tags.includes(tag)
                            ? 'bg-primaryWhite'
                            : 'bg-stone-900 hover:bg-primaryGray'
                    "
                    class="flex items-center w-fit justify-between gap-4 px-6 py-2 rounded-full cursor-pointer transition-all duration-200"
                >
                    <span class="font-semibold">{{ tag }}</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="#e00b32"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"
                        ></path>
                    </svg>
                </div>
            </div>
            <div class="flex gap-4 text-white mb-8">
                <div class="w-1/2">
                    <Select v-model="form.country">
                        <SelectTrigger>
                            <SelectValue placeholder="Country" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Country</SelectLabel>
                                <SelectItem :value="null"
                                    >Filter by Country</SelectItem
                                >
                                <SelectItem
                                    v-for="country in countryData"
                                    :key="country.code"
                                    :value="country.code"
                                >
                                    {{ country.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div class="w-1/2">
                    <Select v-if="form.country" v-model="form.city">
                        <SelectTrigger>
                            <SelectValue placeholder="City" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>City</SelectLabel
                                ><SelectItem :value="null"
                                    >Filter by City</SelectItem
                                >
                                <SelectItem
                                    v-for="city in cities"
                                    :key="city.id"
                                    :value="city.name"
                                >
                                    {{ city.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div class="flex gap-4 text-white mb-8">
                <div class="w-1/2">
                    <Select v-model="form.type">
                        <SelectTrigger>
                            <SelectValue placeholder="Set Job Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Type</SelectLabel>
                                <SelectItem :value="null"
                                    >Filter by Type</SelectItem
                                >
                                <SelectItem value="Full Time">
                                    Full Time
                                </SelectItem>
                                <SelectItem value="Part Time">
                                    Part Time
                                </SelectItem>
                                <SelectItem value="Remote"> Remote </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="(job, index) in job_listings.data"
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
                                >{{ job.city }}, {{ job.country }}</span
                            >
                        </div>
                        <div class="flex items-start gap-2 mt-4">
                            <div
                                class="px-6 py-2 text-xs text-textGray bg-primaryGray rounded-full hover:brightness-150 transition-all duration-200"
                                v-for="(tag, index) in job.tags"
                                @click="handleTagClick(tag)"
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
                        <span
                            class="font-semibold text-sm text-primaryOrange"
                            >{{ job.type }}</span
                        >
                    </div>

                    <div class="self-end mt-4 flex items-center gap-4">
                        <span
                            class="text-sm font-semibold leading-3 text-textGray"
                        >
                            {{ job.created_human }}</span
                        >
                        <Link
                            as="button"
                            :href="
                                route('public.jobs.application.create', job.id)
                            "
                            class="bg-primaryOrange px-8 py-2 rounded-lg text-sm hover:scale-105 transition-all duration-200 active:scale-95"
                        >
                            Apply Now
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
