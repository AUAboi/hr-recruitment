<script setup>
import IconChevronDown from "~icons/mdi/chevron-down";
import MdiUser from "~icons/mdi/user";
import RecruiterMenu from "@/Components/RecruiterMenu.vue";
import Dropdown from "@/Components/Dropdown.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import MdiMenuClose from "~icons/mdi/menu-close";
import MdiMenuOpen from "~icons/mdi/menu-open";
import { useToggle } from "@vueuse/core";

const user = computed(() => {
    return usePage().props.auth.user;
});

const appName = computed(() => {
    return usePage().props.appName;
});

const [menuOpen, toggle] = useToggle();
</script>
<template>
    <div class="md:h-screen md:flex md:flex-col">
        <header>
            <nav class="border-b border-b-neutral-700">
                <div class="nav-title">
                    <Link
                        :href="route('recruiter.dashboard')"
                        class="mt-1 text-2xl"
                    >
                        <ApplicationLogo />
                    </Link>
                    <Dropdown
                        class="md:hidden"
                        :contentClasses="[
                            'mt-1',
                            'mt-2 px-8 py-4 shadow-lg bg-primaryGray rounded',
                        ]"
                    >
                        <template v-slot:trigger>
                            <svg
                                class="fill-white w-6 h-6"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"
                                />
                            </svg>
                        </template>

                        <template v-slot:content>
                            <div>
                                <RecruiterMenu />
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div class="nav-header">
                    <div class="mt-1 mr-4">{{ appName }}</div>
                    <Dropdown :contentClasses="['mt-1']">
                        <template v-slot:trigger>
                            <div
                                class="flex items-center cursor-pointer select-none group"
                            >
                                <div
                                    class="group-hover:text-orange-600 text-orange-400 focus:text-orange-500 mr-1 whitespace-nowrap"
                                >
                                    <span>{{ user.username }}</span>
                                </div>

                                <IconChevronDown
                                    class="w-5 h-5 group-hover:text-orange-500 text-orange-400 focus:text-orange-500 flex align-middle"
                                />
                            </div>
                        </template>
                        <template v-slot:content>
                            <div
                                class="mt-2 py-2 shadow-xl bg-primaryGray rounded text-sm"
                            >
                                <div>
                                    <Link
                                        class="block px-6 py-2 hover:text-orange-500 transition-colors duration-150"
                                        :href="route('recruiter.profile.edit')"
                                        >My Profile</Link
                                    >

                                    <Link
                                        class="block px-6 py-2 hover:text-orange-500 transition-colors duration-150 w-full text-left"
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        >Logout</Link
                                    >
                                </div>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </nav>
        </header>
        <div class="md:flex md:flex-grow md:overflow-hidden">
            <nav
                class="hidden md:block flex-col bg-primaryGray text-white flex-shrink-0 pb-12 pt-6 overflow-y-auto transition-all"
                :class="menuOpen ? 'w-36' : 'w-10'"
            >
                <button class="pb-4 text-2xl mx-auto" @click.prevent="toggle()">
                    <MdiMenuClose v-if="!menuOpen" />
                    <MdiMenuOpen v-else />
                </button>
                <RecruiterMenu :menu-open="menuOpen" />
            </nav>
            <main
                class="px-4 py-8 overflow-x-hidden md:flex-1 md:p-12 md:overflow-y-auto bg-neutral-900"
            >
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
    </div>
</template>

<style scoped>
nav {
    @apply md:flex md:flex-shrink-0 text-white bg-primaryGray;
}
.nav-title {
    @apply md:flex-shrink-0 px-6 py-4 flex items-center justify-between md:justify-center;
}
.nav-header {
    @apply w-full p-4 md:py-0 md:px-12 text-sm md:text-base flex justify-between items-center;
}
</style>
