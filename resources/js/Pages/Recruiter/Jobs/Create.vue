<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import FormInputText from "@/Components/FormInputText.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    job_prompt: "",
});

const submit = () => {
    form.post(route("recruiter.job.store"));
};
</script>
<template>
    <Head title="Create Job" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Create Job</h2>
    <div class="max-w-7xl space-y-6">
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <FormInputText
                label="Enter prompt to generate job listing"
                :error="form.errors.job_prompt"
                v-model="form.job_prompt"
            />

            <PrimaryButton @click="submit">Generate</PrimaryButton>
        </div>
    </div>
</template>
