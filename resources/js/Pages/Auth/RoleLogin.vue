<script setup>
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <div
        class="flex flex-col items-center justify-center h-screen bg-orange-800"
    >
        <div class="mb-6 text-white text-xl">
            <h3>Redirect to</h3>
        </div>
        <div class="flex gap-5">
            <Link
                class="shadow-md bg-white px-14 py-16 hover:bg-gray-200 transition-all duration-250 cursor-pointer"
            >
                Applicant
            </Link>
            <Link
                :href="route('recruiter.dashboard')"
                class="shadow-md bg-white px-14 py-16 hover:bg-gray-200 transition-all duration-250 cursor-pointer"
            >
                Recruiter
            </Link>
        </div>
    </div>
</template>
