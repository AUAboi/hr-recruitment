<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import JobListingForm from "./Partials/JobListingForm.vue";

const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
    job_details: {
        required: true,
    },
});

const form = useForm({
    job_details: props.job_details,
    job_prompt: "",
});

const submit = () => {
    form.patch(route("recruiter.job.update", props.id));
};
</script>
<template>
    <Head title="Edit Job" />
    <h2 class="font-semibold text-xl text-white pb-6">Edit Job</h2>
    <JobListingForm :form="form" @send="submit" />
</template>
