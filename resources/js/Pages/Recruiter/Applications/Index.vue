<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import UserAvatar from "@/Components/UserAvatar.vue";
import EvaluationCard from "./Partials/EvaluationCard.vue";
import { computed, ref } from "vue";

const props = defineProps(["job_applications", "filters", "job_listing"]);

const activeApplicationIndex = ref(0);

const activeApplication = computed(() => {
    return props.job_applications.data[activeApplicationIndex.value];
});

const statusColorClass = (status) => {
    return {
        APPROVED: "text-green-400",
        PENDING: "text-gray-300",
        REJECTED: "text-red-400",
    }[status];
};
</script>

<template>
    <Head title="Jobs" />
    <h2 class="font-semibold text-xl text-white pb-6">Job Listings</h2>

    <div class="pb-12">
        <div class="flex gap-10">
            <div class="flex flex-col max-w-sm">
                <div
                    v-for="(application, index) in job_applications.data"
                    :key="application.id"
                    :class="
                        application.application_status === 'PENDING'
                            ? 'bg-stone-700'
                            : 'bg-primaryGray'
                    "
                    class="text-white overflow-hidden shadow-sm mx-2 md:mx-0 hover:cursor-pointer border border-neutral-600"
                    @click="activeApplicationIndex = index"
                >
                    <div
                        :class="
                            index === activeApplicationIndex
                                ? 'border-l-4 border-primaryOrange'
                                : ''
                        "
                        class="px-4 py-4"
                    >
                        <div class="flex">
                            <UserAvatar :user="application.user" />
                            <div class="pl-3 pt-1">
                                <h5 class="font-semibold">
                                    {{ application.user.full_name }}
                                </h5>
                                <div class="flex gap-1 items-center">
                                    <p
                                        :class="
                                            statusColorClass(
                                                application.application_status
                                            )
                                        "
                                        class="font-semibold text-xs"
                                    >
                                        {{ application.application_status }}
                                    </p>

                                    &middot;

                                    <p class="text-sm text-gray-200">
                                        Applied {{ application.created_at }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="text-white flex-grow"
                v-if="job_applications.data.length"
            >
                <EvaluationCard
                    class="sticky -top-5"
                    :job_application="activeApplication"
                />
            </div>
        </div>

        <PrimaryButton class="mt-4">
            <a
                :href="
                    route('recruiter.job.applications.export', job_listing.id)
                "
                >Export all to CSV</a
            >
        </PrimaryButton>
    </div>
</template>
