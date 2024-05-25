<script setup>
import { Head, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import UserAvatar from "@/Components/UserAvatar.vue";
import Paginator from "@/Components/Paginator.vue";
import Dropdown from "@/Components/Dropdown.vue";
import EvaluationCard from "./Partials/EvaluationCard.vue";
import { computed, reactive, ref } from "vue";
import IconChevronDown from "~icons/mdi/chevron-down";
import { reactivePick, watchThrottled } from "@vueuse/core";
import MdiClose from "~icons/mdi/close";

const props = defineProps(["job_applications", "filters", "job_listing"]);

const form = reactive({
    status: props.filters.status,
});

const activeApplicationIndex = ref(0);

const activeApplication = computed(() => {
    return props.job_applications.data[activeApplicationIndex.value];
});

const statuses = ["APPROVED", "PENDING", "REJECTED"];

const statusColorClass = (status) => {
    return {
        APPROVED: "text-green-400",
        PENDING: "text-gray-300",
        REJECTED: "text-red-400",
    }[status];
};

const reset = () => {
    form.status = null;
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
    <h2 class="font-semibold text-xl text-white pb-6">Job Listings</h2>
    <div class="flex items-center text-white mb-5 gap-4">
        <Dropdown align="top" :contentClasses="['mt-1']">
            <template v-slot:trigger>
                <div
                    :class="
                        form.status
                            ? 'bg-orange-500 text-white'
                            : 'text-orange-500'
                    "
                    class="flex items-center cursor-pointer select-none group py-1 px-4 rounded-xl text-center font-semibold border-orange-500 border-2"
                >
                    <div class="-500 mr-1 whitespace-nowrap">
                        <span v-if="!form.status">Status</span>
                        <span v-else>{{ form.status }}</span>
                    </div>

                    <IconChevronDown class="w-5 h-5 flex align-middle" />
                </div>
            </template>
            <template v-slot:content>
                <div class="mt-2 py-2 shadow-xl bg-primaryGray rounded text-sm">
                    <div>
                        <p
                            @click="form.status = status"
                            v-for="(status, index) in statuses"
                            :key="index"
                            :class="{
                                'text-orange-500': form.status === status,
                            }"
                            class="block capitalize px-6 py-2 hover:text-orange-500 transition-colors duration-150 cursor-pointer"
                        >
                            {{ status.toLowerCase() }}
                        </p>
                    </div>
                </div>
            </template>
        </Dropdown>
        <MdiClose
            v-if="form.status"
            @click.prevent="reset"
            class="w-5 h-5 flex align-middle"
        />
    </div>
    <div class="pb-12">
        <div class="flex gap-10">
            <div class="flex flex-col max-w-sm">
                <div
                    v-for="(application, index) in job_applications.data"
                    :key="application.id"
                    :class="
                        application.application_status === 'PENDING'
                            ? 'bg-stone-700'
                            : 'bg-primaryGray'
                    "
                    class="text-white overflow-hidden shadow-sm mx-2 md:mx-0 hover:cursor-pointer border border-neutral-600"
                    @click="activeApplicationIndex = index"
                >
                    <div
                        :class="
                            index === activeApplicationIndex
                                ? 'border-l-4 border-primaryOrange'
                                : ''
                        "
                        class="px-4 py-4"
                    >
                        <div class="flex">
                            <UserAvatar :user="application.user" />
                            <div class="pl-3 pt-1">
                                <h5 class="font-semibold">
                                    {{ application.user.full_name }}
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

                                    <p class="text-sm text-gray-200">
                                        Applied {{ application.created_at }}
                                    </p>
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
