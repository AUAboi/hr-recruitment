<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import JobListingForm from "./Partials/JobListingForm.vue";

const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
    job_details: {
        required: true,
    },
    job_title: {
        required: true,
        type: String,
    },
    tags: {
        required: true,
    },
    status: {
        required: true,
    },
    type: {
        required: true,
    },
});

const form = useForm({
    job_details: props.job_details,
    job_prompt: "",
    tags: props.tags,
    job_title: props.job_title,
    status: props.status,
    type: props.type,
    country: "",
    city: "",
});

const submit = () => {
    form.patch(route("recruiter.job.update", props.id));
};
</script>
<template>
    <Head title="Edit Job" />
    <div class="flex justify-between max-w-7xl">
        <h2 class="font-semibold text-xl text-white pb-6">Edit Job</h2>
        <Link
            class="text-blue-600 underline"
            :href="route('recruiter.job.applications.index', props.id)"
        >
            View Applications
        </Link>
    </div>

    <JobListingForm :form="form" @send="submit" />
</template>
