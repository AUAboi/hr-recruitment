<script setup>
import { Head, Link } from "@inertiajs/vue3";

import { router } from "@inertiajs/vue3";

import IconChevronDown from "~icons/mdi/chevron-down";
import MdiClose from "~icons/mdi/close";
import { computed, reactive } from "vue";
import DataTable from "@/Components/DataTable.vue";
import Dropdown from "@/Components/Dropdown.vue";
import SearchBox from "@/Components/SearchBox.vue";
import Paginator from "@/Components/Paginator.vue";
import { reactivePick, watchThrottled } from "@vueuse/core";

const props = defineProps({
    filters: Object,
    users: Object,
    permissions: Object,
    roles: Array,
});

const form = reactive({
    search: props.filters.search,
    role: props.filters.role,
});

const labels = [
    {
        value: "First Name",
        key: "first_name",
    },
    {
        value: "Last Name",
        key: "last_name",
    },
    {
        value: "Username",
        key: "username",
    },
    {
        value: "Email",
        key: "email",
    },
    {
        value: "Role",
        key: "roles",
    },
];

watchThrottled(
    form,
    () => {
        router.get(
            route("recruiter.users.index"),
            reactivePick(form, (x) => x),
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    },
    { throttle: 500, deep: true }
);

const reset = () => {
    form.search = null;
    form.role = null;
};

const modifiedUsers = computed(() => {
    return props.users.data.map((user) => ({
        ...user,
        roles: user.roles.join(", "),
    }));
});
</script>

<template>
    <div>
        <Head title="Users" />
        <h2 class="font-semibold text-xl text-white pb-6">Users</h2>
        <div class="mb-6 flex justify-between items-center">
            <div class="flex gap-4 items-center">
                <Dropdown align="top" :contentClasses="['mt-1']">
                    <template v-slot:trigger>
                        <div
                            :class="
                                form.role
                                    ? 'bg-orange-500 text-white'
                                    : 'text-orange-500'
                            "
                            class="flex items-center cursor-pointer select-none group py-1 px-4 rounded-xl text-center font-semibold border-orange-500 border-2"
                        >
                            <div class="-500 mr-1 whitespace-nowrap">
                                <span v-if="!form.role">Role</span>
                                <span class="capitalize" v-else>{{
                                    form.role
                                }}</span>
                            </div>

                            <IconChevronDown
                                class="w-5 h-5 flex align-middle"
                            />
                        </div>
                    </template>
                    <template v-slot:content>
                        <div
                            class="mt-2 py-2 shadow-xl bg-primaryGray rounded text-sm text-white"
                        >
                            <div>
                                <p
                                    @click="form.role = role.name"
                                    v-for="role in roles"
                                    :key="role.id"
                                    :class="{
                                        'text-orange-500':
                                            form.role === role.name,
                                    }"
                                    class="block capitalize px-6 py-2 hover:text-orange-500 transition-colors duration-150 cursor-pointer"
                                >
                                    {{ role.name.toLowerCase() }}
                                </p>
                            </div>
                        </div>
                    </template>
                </Dropdown>
                <MdiClose
                    v-if="form.role"
                    @click.prevent="reset"
                    class="w-5 h-5 flex align-middle text-orange-500 cursor-pointer"
                />
            </div>

            <Link
                :href="route('recruiter.users.create')"
                as="button"
                class="button-action"
                >Create&nbsp;<span class="hidden md:inline"
                    >Recruiter</span
                ></Link
            >
        </div>
        <div
            class="bg-primaryGray overflow-hidden shadow-sm rounded-lg mx-2 text-white md:mx-0"
        >
            <DataTable :tableData="modifiedUsers" :labels="labels" />
        </div>
        <Paginator class="mt-6" :links="users.meta.links" />
    </div>
</template>
