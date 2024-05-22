<script setup>
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps(["recent_applications_count", "recent_listing"]);
</script>

<template>
    <Head title="Dashboard" />

    <h2 class="font-semibold text-xl text-white leading-tight">Dashboard</h2>

    <div class="py-12">
        <div class="mb-14">
            <Link
                as="div"
                :href="route('recruiter.job.create')"
                class="bg-primaryOrange/85 h-14 bg text-white px-4 max-w-xs flex items-center justify-center rounded-lg cursor-pointer transition-all duration-100 hover:bg-primaryOrange"
            >
                Create Job
            </Link>
        </div>

        <Link
            as="div"
            v-if="recent_listing?.id"
            :href="route('recruiter.job.applications.index', recent_listing.id)"
            :class="recent_applications_count ? 'border-2' : ''"
            class="bg-primaryGray/85 h-60 text-white max-w-sm flex items-center justify-center hover:rounded-lg hover:border-2 cursor-pointer border-primaryOrange transition-all duration-100"
        >
            {{ recent_applications_count }} Applicants applied to your most
            recent job
        </Link>
    </div>
</template>
