<script setup>
import DataTable from "@/Components/DataTable.vue";
import SearchBox from "@/Components/SearchBox.vue";
import Paginator from "@/Components/Paginator.vue";
import { Head, Link } from "@inertiajs/vue3";
import { reactive } from "@vue/reactivity";
import { watchThrottled } from "@vueuse/core";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(["job_applications", "filters", "job_listing"]);

const labels = [
    {
        key: "user.username",
        value: "Username",
    },
    {
        key: "user.first_name",
        value: "First Name",
    },
    {
        key: "user.last_name",
        value: "Last Name",
    },
    {
        key: "application_status",
        value: "Status",
    },
];

const form = reactive({
    search: props.filters.search,
});

const reset = () => {
    form.search = null;
};
</script>

<template>
    <Head title="Jobs" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Job Listings</h2>

    <div class="pb-12">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div
                class="bg-primaryGray overflow-hidden shadow-sm rounded-lg mx-2 md:mx-0"
            >
                <div class="text-white">
                    <DataTable
                        resource-route="recruiter.job.applications.show"
                        :table-data="job_applications.data"
                        :labels="labels"
                    />
                </div>
                <Paginator :links="job_applications.links" />
            </div>
        </div>
        <PrimaryButton class="mt-4 mx-8">
            <a
                :href="
                    route('recruiter.job.applications.export', job_listing.id)
                "
                >Export to CSV</a
            >
        </PrimaryButton>
    </div>
</template>
