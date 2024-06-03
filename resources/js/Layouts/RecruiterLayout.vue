<script setup>
import IconChevronDown from "~icons/mdi/chevron-down";
import RecruiterMenu from "@/Components/RecruiterMenu.vue";
import RecruiterMenuMobile from "@/Components/RecruiterMenuMobile.vue";
import Dropdown from "@/Components/Dropdown.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import MdiMenuClose from "~icons/mdi/menu-close";
import MdiMenuOpen from "~icons/mdi/menu-open";
import { useStorage, useToggle } from "@vueuse/core";
import MdiUserMultiple from "~icons/mdi/user-multiple";
import MdiHome from "~icons/mdi/home";
import MdiBriefcase from "~icons/mdi/briefcase";
import MdiAlphaJCircle from "~icons/mdi/alpha-j-circle";
import MdiMoonAndStars from "~icons/mdi/moon-and-stars";

const user = computed(() => {
    return usePage().props.auth.user;
});

const appName = computed(() => {
    return usePage().props.appName;
});

const [menuOpen, toggle] = useToggle();

const routes = [
    {
        title: "Dashboard",
        route: "recruiter.dashboard",
        icon: MdiHome,
        slug: "dashboard",
    },
    {
        title: "Jobs",
        route: "recruiter.job.index",
        icon: MdiAlphaJCircle,
        slug: "job",
    },
    {
        title: "Users",
        route: "recruiter.users.index",
        slug: "user",
        icon: MdiUserMultiple,
    },
];

const state = useStorage("my-store", { mode: "dark" });
</script>
<template>
    <div class="md:h-screen md:flex md:flex-col" :class="state.mode">
        <header>
            <nav class="dark:border-b dark:border-b-neutral-700">
                <div class="nav-title">
                    <Link :href="route('recruiter.dashboard')" class="mt-1 text-2xl">
                    <ApplicationLogo />
                    </Link>
                    <Dropdown class="md:hidden" :contentClasses="[
                        'mt-1',
                        'mt-2 px-8 py-4 shadow-lg bg-primaryGray rounded',
                    ]">
                        <template v-slot:trigger>
                            <svg class="fill-white w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                        </template>

                        <template v-slot:content>
                            <div>
                                <RecruiterMenuMobile :routes="routes" />
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div class="nav-header">
                    <div class="mt-1 mr-4">{{ appName }}</div>
                    <div class="flex items-center gap-3">
                        <MdiMoonAndStars @click="
                            state.mode === 'dark'
                                ? (state.mode = 'light')
                                : (state.mode = 'dark')
                            " />
                        <Dropdown :contentClasses="['mt-1']">
                            <template v-slot:trigger>
                                <div class="flex items-center cursor-pointer select-none group">
                                    <div
                                        class="dark:group-hover:text-orange-600 dark:text-orange-400 dark:focus:text-orange-500 mr-1 whitespace-nowrap text-darkBlue-700 group-hover:text-darkBlue-600 focus:text-darkBlue-600">
                                        <span>{{ user.username }}</span>
                                    </div>

                                    <IconChevronDown
                                        class="w-5 h-5 dark:group-hover:text-orange-500 dark:text-orange-400 dark:focus:text-orange-500 group-hover:text-darkBlue-600 text-gray-700 focus:text-darkBlue-600 flex align-middle" />
                                </div>
                            </template>
                            <template v-slot:content>
                                <div class="mt-2 py-2 shadow-xl bg-white dark:bg-primaryGray rounded text-sm">
                                    <div>
                                        <Link
                                            class="block px-6 py-2 hover:bg-darkBlue-500 hover:text-white dark:hover:text-orange-500 transition-colors duration-150"
                                            :href="route('recruiter.profile.edit')
                                                ">My Profile</Link>

                                        <Link
                                            class="block px-6 py-2 hover:bg-darkBlue-500 hover:text-white dark:hover:text-orange-500 transition-colors duration-150 w-full text-left"
                                            :href="route('logout')" method="post" as="button">Logout</Link>
                                    </div>
                                </div>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </nav>
        </header>
        <div class="md:flex md:flex-grow md:overflow-hidden">
            <nav class="hidden md:block flex-col bg-darkBlue-800 dark:bg-primaryGray text-white flex-shrink-0 pb-12 pt-6 overflow-y-auto transition-all items-center"
                :class="menuOpen ? 'w-36' : 'w-[5.5rem] dark:w-12'">
                <button class="pb-4 text-2xl mx-auto" @click.prevent="toggle()">
                    <MdiMenuClose class="bg-gray-500 rounded-md" v-if="!menuOpen" />
                    <MdiMenuOpen class="bg-gray-500 rounded-md" v-else />
                </button>
                <RecruiterMenu :routes="routes" :menu-open="menuOpen" />
            </nav>

            <main
                class="px-4 py-8 overflow-x-hidden md:flex-1 md:p-12 md:overflow-y-auto bg-gray-100 dark:bg-neutral-900">
                <FlashMessage v-if="
                    $page.props.flash.success ||
                    $page.props.flash.error ||
                    Object.keys($page.props.errors).length > 0
                " />
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
nav {
    @apply md:flex md:flex-shrink-0 dark:text-white dark:bg-primaryGray;
}

.nav-title {
    @apply bg-darkBlue-900 text-white dark:bg-[unset] dark:text-[unset] md:flex-shrink-0 px-6 py-4 flex items-center justify-between md:justify-center;
}

.nav-header {
    @apply bg-white dark:bg-[unset] border-b w-full p-4 md:py-0 md:px-12 text-sm md:text-base flex justify-between items-center;
}
</style>
