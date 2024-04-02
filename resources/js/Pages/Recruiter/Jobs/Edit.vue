<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import FormInputText from "@/Components/FormInputText.vue";
import FormInputTextArea from "@/Components/FormInputTextArea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import MdiCancelBold from "~icons/mdi/cancel-bold";

const props = defineProps({
    job_listing: {
        required: true,
    },
});

const form = useForm({
    job_listing: {
        company_profile:
            "Our company is a leading tech startup that is revolutionizing the way people communicate and connect. With a strong focus on innovation and collaboration, we are constantly striving to improve and grow.",
        job_title: "Senior Software Engineer",
        requirements: [
            "Bachelor's degree in Computer Science or related field",
            "5+ years of experience in software development",
            "Proficiency in Java, Python, or C++",
            "Strong problem-solving skills",
        ],
        what_will_you_do: [
            "Design, develop, and maintain high-quality software solutions",
            "Collaborate with cross-functional teams to define, design, and ship new features",
            "Conduct code reviews and provide feedback to team members",
            "Stay current with industry trends and best practices",
        ],
        benefits: [
            "Competitive salary and benefits package",
            "Flexible work hours and remote work options",
            "Professional development opportunities",
            "Fun and inclusive company culture",
        ],
    },
});

const submit = () => {
    form.put(route("recruiter.job.update"));
};
</script>
<template>
    <Head title="Create Job" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Create Job</h2>
    <div class="max-w-7xl space-y-6">
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <div>
                <FormInputText
                    label="Job Title"
                    class="w-1/2"
                    v-model="form.job_listing.job_title"
                />
                <FormInputTextArea
                    label="Company Profile"
                    v-model="form.job_listing.company_profile"
                />
            </div>
            <div class="w-1/2">
                <p class="mb-2">Requirements</p>
                <div
                    class="flex"
                    v-for="(requirement, index) in form.job_listing
                        .requirements"
                    :key="index"
                >
                    <FormInputText
                        v-model="form.job_listing.requirements[index]"
                    />
                    <MdiCancelBold class="mt-4 text-red-700" />
                </div>
            </div>

            <PrimaryButton @click="submit">Generate</PrimaryButton>
        </div>
    </div>
</template>
