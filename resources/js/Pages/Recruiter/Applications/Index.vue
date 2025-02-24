<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import UserAvatar from "@/Components/UserAvatar.vue";
import Paginator from "@/Components/Paginator.vue";
import Dropdown from "@/Components/Dropdown.vue";
import EvaluationCard from "./Partials/EvaluationCard.vue";
import { computed, reactive, ref } from "vue";
import IconChevronDown from "~icons/mdi/chevron-down";
import { reactivePick, watchThrottled } from "@vueuse/core";
import MdiClose from "~icons/mdi/close";
import MdiSortDescending from "~icons/mdi/sort-descending";

const props = defineProps(["job_applications", "filters", "job_listing"]);

const form = reactive({
    status: props.filters.status,
    sort: props.filters.sort,
});

const activeApplicationIndex = ref(0);

const activeApplication = computed(() => {
    return props.job_applications.data[activeApplicationIndex.value];
});

const statuses = ["APPROVED", "PENDING", "REJECTED"];
const sorts = ["average", "relavancy", "skill", "experience"];

const statusColorClass = (status) => {
    return {
        APPROVED: "text-green-600 dark:text-green-400",
        PENDING: "text-gray-600 dark:text-gray-300",
        REJECTED: "text-red-600 dark:text-red-400",
    }[status];
};

const reset = () => {
    form.status = null;
    form.sort = null;
};

watchThrottled(
    form,
    () => {
        router.get(
            route("recruiter.job.applications.index", props.job_listing.id),
            reactivePick(form, (x) => x),
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    },
    { throttle: 500, deep: true }
);
</script>

<template>
    <Head title="Jobs" />
    <h2 class="font-semibold text-xl text-darkBlue-500 dark:text-white pb-6">
        Applications
    </h2>
    <div class="flex items-center dark:text-white mb-5 gap-4">
        <Dropdown align="top" :contentClasses="['mt-1']">
            <template v-slot:trigger>
                <div
                    :class="
                        form.status
                            ? 'bg-darkBlue-800 dark:bg-orange-500 text-white'
                            : 'text-darkBlue-500 dark:text-orange-500'
                    "
                    class="flex items-center cursor-pointer select-none group py-1 px-4 rounded-xl text-center font-semibold dark:border-orange-500 border-darkBlue-800 border-2"
                >
                    <div class="-500 mr-1 whitespace-nowrap">
                        <span v-if="!form.status">Status</span>
                        <span v-else>{{ form.status }}</span>
                    </div>

                    <IconChevronDown class="w-5 h-5 flex align-middle" />
                </div>
            </template>
            <template v-slot:content>
                <div
                    class="mt-2 py-2 shadow-xl bg-white dark:bg-primaryGray rounded text-sm dark:text-white"
                >
                    <div>
                        <p
                            @click="form.status = status"
                            v-for="(status, index) in statuses"
                            :key="index"
                            :class="{
                                'text-darkBlue-600 dark:text-orange-500':
                                    form.status === status,
                            }"
                            class="block capitalize px-6 py-2 hover:text-darkBlue-600 dark:hover:text-orange-500 transition-colors duration-150 cursor-pointer"
                        >
                            {{ status.toLowerCase() }}
                        </p>
                    </div>
                </div>
            </template>
        </Dropdown>
        <Dropdown align="top" :contentClasses="['mt-1']">
            <template v-slot:trigger>
                <div
                    :class="
                        form.sort
                            ? 'bg-darkBlue-800 dark:bg-orange-500 text-white'
                            : 'text-darkBlue-500 dark:text-orange-500'
                    "
                    class="flex items-center cursor-pointer select-none group py-1 px-4 rounded-xl text-center font-semibold dark:border-orange-500 border-darkBlue-800 border-2"
                >
                    <div class="-500 mr-1 whitespace-nowrap">
                        <span v-if="!form.sort">Sort By</span>
                        <span class="uppercase" v-else>{{ form.sort }}</span>
                    </div>

                    <IconChevronDown class="w-5 h-5 flex align-middle" />
                </div>
            </template>
            <template v-slot:content>
                <div
                    class="mt-2 py-2 shadow-xl bg-white dark:bg-primaryGray rounded text-sm dark:text-white"
                >
                    <div>
                        <p
                            @click="form.sort = sort"
                            v-for="(sort, index) in sorts"
                            :key="index"
                            :class="{
                                'text-darkBlue-600 dark:text-orange-500':
                                    form.sort === sort,
                            }"
                            class="block capitalize px-6 py-2 hover:text-darkBlue-600 dark:hover:text-orange-500 transition-colors duration-150 cursor-pointer"
                        >
                            {{ sort.toLowerCase() }}
                        </p>
                    </div>
                </div>
            </template>
        </Dropdown>
        <Link
            :href="route('recruiter.job.edit', job_listing.id)"
            class="button-action"
            >&nbsp;<span class="hidden md:inline">Edit</span></Link
        >

        <MdiClose
            v-if="form.status || form.sort"
            @click.prevent="reset"
            class="w-5 h-5 flex align-middle cursor-pointer"
        />
    </div>
    <div class="pb-12">
        <div class="flex flex-col md:flex-row gap-10">
            <div
                class="flex flex-col md:max-w-sm max-h-[400px] md:max-h-[unset] overflow-y-scroll md:overflow-auto"
            >
                <div
                    v-for="(application, index) in job_applications.data"
                    :key="application.id"
                    :class="
                        application.application_status === 'PENDING'
                            ? 'bg-blue-100 dark:bg-stone-700'
                            : 'bg-white dark:bg-primaryGray'
                    "
                    class="dark:text-white md:overflow-hidden shadow-sm md:mx-0 hover:cursor-pointer border border-neutral-100 dark:border-neutral-600"
                    @click="activeApplicationIndex = index"
                >
                    <div
                        :class="
                            index === activeApplicationIndex
                                ? 'border-l-4 border-darkBlue-600 dark:border-primaryOrange'
                                : ''
                        "
                        class="px-4 py-4"
                    >
                        <div class="flex">
                            <UserAvatar :user="application.user" />
                            <div class="pl-3 pt-1">
                                <h5 class="font-semibold">
                                    {{
                                        application.data.name
                                            ? application.data.name
                                            : application.user.full_name
                                    }}
                                </h5>
                                <div class="flex gap-1 items-center">
                                    <p
                                        :class="
                                            statusColorClass(
                                                application.application_status
                                            )
                                        "
                                        class="font-semibold text-xs"
                                    >
                                        {{ application.application_status }}
                                    </p>

                                    &middot;

                                    <p class="text-sm dark:text-gray-200">
                                        Applied {{ application.created_at }}
                                    </p>
                                </div>
                                <div class="pt-2 w-fit">
                                    <p
                                        :class="
                                            application.average_score > 70
                                                ? 'text-green-600'
                                                : application.average_score < 40
                                                ? 'text-red-500'
                                                : 'text-yellow-400'
                                        "
                                        class="font-semibold"
                                        v-if="application.average_score != null"
                                    >
                                        {{
                                            Math.round(
                                                application.average_score
                                            )
                                        }}
                                    </p>
                                    <p v-else>Unscored</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="text-white flex-grow"
                v-if="job_applications.data.length"
            >
                <EvaluationCard
                    class="sticky -top-5"
                    :job_application="activeApplication"
                />
            </div>
        </div>
        <div class="mt-14">
            <Paginator :links="job_applications.meta.links" />
        </div>

        <PrimaryButton class="mt-4">
            <a
                :href="
                    route('recruiter.job.applications.export', job_listing.id)
                "
                >Export all to CSV</a
            >
        </PrimaryButton>
    </div>
</template>
