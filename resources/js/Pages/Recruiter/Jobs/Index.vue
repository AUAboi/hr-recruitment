<script setup>
import DataTable from "@/Components/DataTable.vue";
import SearchBox from "@/Components/SearchBox.vue";
import Paginator from "@/Components/Paginator.vue";
import { Head, Link } from "@inertiajs/vue3";
import { reactive } from "@vue/reactivity";
import { watchThrottled } from "@vueuse/core";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    job_listings: {
        required: true,
        type: Object,
    },
    filters: {
        type: Object,
    },
});

const labels = [
    {
        key: "id",
        value: "ID",
    },
    {
        key: "job_title",
        value: "Job Title",
    },
];

const form = reactive({
    search: props.filters.search,
});

const reset = () => {
    form.search = null;
};

watchThrottled(
    form,
    () => {
        router.get(route("recruiter.job.index"), form, {
            preserveState: true,
            preserveScroll: true,
        });
    },
    { throttle: 500, deep: true }
);
</script>
<template>
    <Head title="Posts" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Job Listings</h2>
    <div class="pb-12">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 md:gap-0 justify-between">
                <SearchBox
                    class="w-full max-w-md my-4 mx-2 md:mx-0"
                    v-model="form.search"
                    @reset="reset"
                />
                <Link
                    :href="route('recruiter.job.create')"
                    as="button"
                    class="button-action"
                    >Create&nbsp;<span class="hidden md:inline"
                        >Listing</span
                    ></Link
                >
            </div>

            <div
                class="bg-primaryGray overflow-hidden shadow-sm rounded-lg mx-2 md:mx-0"
            >
                <div class="text-white">
                    <DataTable
                        resource-route="recruiter.job.edit"
                        :table-data="job_listings.data"
                        :labels="labels"
                    />
                </div>
                <Paginator :links="job_listings.links" />
            </div>
        </div>
    </div>
</template>
