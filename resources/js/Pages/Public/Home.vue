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
                right job or the right candidate. We have a proven track record
                of success in the recruitment industry and we are confident that
                we can help you achieve your goals. Our mission is to make the
                recruitment process as simple and efficient as possible for both
                job seekers and employers. We are committed to providing you
                with the best possible service and we look forward to working
                with you.
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
