<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="page-container bg-black">
        <header>
            <div class="p-4 flex items-center justify-between">
                <Link class="block w-fit" href="/">
                    <ApplicationLogo
                        class="w-20 h-20 fill-current text-gray-500"
                    />
                </Link>
                <SecondaryButton v-if="$page.props.auth.user" class="mr-20">
                    <Link
                        class=""
                        method="post"
                        as="button"
                        :href="route('logout')"
                    >
                        Logout
                    </Link>
                </SecondaryButton>
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
