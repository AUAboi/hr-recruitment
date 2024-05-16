<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>
<template>
    <Head title="Welcome" />
    <div class="flex items-center justify-evenly text-white">
        <div class="max-w-xl">
            <h2 class="text-5xl mb-3">
                Meet QLU - your recruitment assistant from the future
            </h2>
            <p class="text-gray-200 text-xl pb-10">
                Completely automate research, sourcing, and all your other
                recruiting bottlenecks. Focus on your client and high touch
                candidates.
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
