<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import UserAvatar from "@/Components/UserAvatar.vue";
import EvaluationCard from "./Partials/EvaluationCard.vue";
const props = defineProps(["job_applications", "filters", "job_listing"]);
</script>

<template>
    <Head title="Jobs" />
    <h2 class="font-semibold text-xl text-white pb-6">Job Listings</h2>

    <div class="pb-12">
        <div class="flex gap-10">
            <div class="flex flex-col flex-grow">
                <div
                    v-for="application in job_applications.data"
                    :key="application.id"
                    class="bg-primaryGray text-white overflow-hidden shadow-sm mx-2 md:mx-0 max-w-sm"
                >
                    <div class="border-l-4 border-primaryOrange px-4 py-4">
                        <div class="flex">
                            <UserAvatar :user="application.user" />
                            <div class="pl-3 pt-1">
                                <h5 class="font-semibold">
                                    {{ application.user.full_name }}
                                </h5>
                                <p class="text-sm text-gray-200">
                                    Applied {{ application.created_at }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <EvaluationCard :job_application="job_applications.data[0]" />
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
