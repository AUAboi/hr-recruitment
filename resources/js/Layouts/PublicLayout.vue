<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="page-container bg-black">
        <header>
            <div>
                <Link href="/">
                    <ApplicationLogo
                        class="w-20 h-20 fill-current text-gray-500"
                    />
                </Link>
            </div>
        </header>

        <main class="w-full">
            <slot />
        </main>
    </div>
</template>
