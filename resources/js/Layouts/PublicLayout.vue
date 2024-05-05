<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="page-container bg-black">
        <header>
            <div class="p-4">
                <Link class="block w-fit" href="/">
                    <ApplicationLogo
                        class="w-20 h-20 fill-current text-gray-500"
                    />
                </Link>
            </div>
        </header>

        <main class="w-full">
            <FlashMessage
                v-if="
                    $page.props.flash.success ||
                    $page.props.flash.error ||
                    Object.keys($page.props.errors).length > 0
                "
            />
            <slot />
        </main>
    </div>
</template>
<style>
.page-container {
    min-height: 100vh;
}
</style>
