<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import JobListingForm from "./Partials/JobListingForm.vue";

const form = useForm({
    job_details: {
        job_title: "",
        company_profile: "",
        requirements: [""],
        what_will_you_do: [""],
        benefits: [""],
    },
});

const submit = () => {
    form.post(route("recruiter.job.store"));
};
</script>
<template>
    <Head title="Create Job" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Create Job</h2>
    <JobListingForm :form="form" @send="submit" />
</template>
