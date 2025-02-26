<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>
<template>
    <Head title="Welcome" />
    <div class="flex items-center justify-evenly text-white">
        <div class="max-w-xl">
            <h2 class="text-5xl mb-3">
                Meet Job Nexus - your career assistant from the future
            </h2>
            <p class="text-gray-200 text-xl pb-10">
                Completely automate your job search and recruitment needs with
                Job Nexus. We are a global recruitment company that provides a
                unique service to job seekers and employers. We are a team of
                professionals who are passionate about helping you find the
                right job or the right candidate.
            </p>
            <PrimaryButton
                ><Link :href="route('public.jobs')"
                    >Apply Now</Link
                ></PrimaryButton
            >
        </div>

        <div>
            <img src="/images/qluBotUpperBody.webp" alt="" />
        </div>
    </div>
</template>
