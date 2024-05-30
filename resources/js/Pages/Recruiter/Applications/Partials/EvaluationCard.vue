<script setup>
import { Head } from "@inertiajs/vue3";
import { Badge } from "@/Components/ui/badge";
import ProfileSection from "./ProfileSection.vue";
import { ref } from "vue";
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/Components/ui/collapsible";
import Toaster from "@/Components/ui/toast/Toaster.vue";

const isOpen = ref(false);

const props = defineProps({
    job_application: {
        required: true,
    },
});

const MAX_SHOWN_PROJECTS = 3;
</script>
<template>
    <section
        class="dark:bg-primaryGray bg-white shadow max-w-6xl w-full mx-auto rounded-md"
    >
        <div class="flex flex-col sm:flex-row">
            <div class="sm:w-1/2 sm:border-r dark:border-stone-700">
                <ProfileSection :job_application="job_application" />
            </div>

            <div class="w-full max-h-[75vh] overflow-y-auto main-scroller">
                <div
                    v-if="job_application.data.skills?.length"
                    class="eval-row"
                >
                    <div
                        class="text-stone-600 font-semibold dark:font-normal dark:text-stone-400 uppercase pl-4 max-w-28 w-28 sm:pl-0"
                    >
                        <h5>Skills</h5>
                    </div>
                    <div
                        class="flex-grow border-b dark:border-stone-700 pb-10 pr-4 pl-4 sm:pl-0"
                    >
                        <div class="max-w-lg flex flex-wrap gap-x-4 gap-y-2">
                            <Badge
                                v-for="skill in job_application.data.skills"
                                :key="skill"
                            >
                                {{ skill }}
                            </Badge>
                        </div>
                    </div>
                </div>
                <div
                    v-if="job_application.data.projects?.length"
                    class="eval-row"
                >
                    <div
                        class="text-stone-600 font-semibold dark:font-normal dark:text-stone-400 uppercase pl-4 max-w-28 w-28 sm:pl-0"
                    >
                        <h5>Projects</h5>
                    </div>
                    <div
                        class="flex-grow border-b border-stone-700 pb-10 pr-4 pl-4 sm:pl-0"
                    >
                        <div class="max-w-lg space-y-4">
                            <Collapsible
                                v-model:open="isOpen"
                                class="space-y-4"
                            >
                                <div
                                    class="text-gray-200"
                                    v-for="(project, index) in job_application
                                        .data.projects"
                                >
                                    <p v-if="index < MAX_SHOWN_PROJECTS">
                                        {{ project }}
                                    </p>
                                    <CollapsibleContent
                                        v-else
                                        class="space-y-2"
                                    >
                                        <p>
                                            {{ project }}
                                        </p>
                                    </CollapsibleContent>
                                </div>
                                <CollapsibleTrigger
                                    v-if="
                                        job_application.data.projects.length >
                                        MAX_SHOWN_PROJECTS
                                    "
                                    as-child
                                >
                                    <p
                                        v-if="!isOpen"
                                        class="text-blue-500 text-sm hover:underline cursor-pointer"
                                    >
                                        {{
                                            job_application.data.projects
                                                .length - MAX_SHOWN_PROJECTS
                                        }}
                                        More
                                    </p>
                                    <p
                                        class="text-blue-500 text-sm hover:underline cursor-pointer"
                                        v-else
                                    >
                                        Show less
                                    </p>
                                </CollapsibleTrigger>
                            </Collapsible>
                        </div>
                    </div>
                </div>
                <div
                    v-if="job_application.data.programming_language?.length"
                    class="eval-row"
                >
                    <div
                        class="text-stone-600 font-semibold dark:font-normal dark:text-stone-400 uppercase pl-4 max-w-28 w-28 sm:pl-0"
                    >
                        <h5>Programming Language</h5>
                    </div>
                    <div
                        class="flex-grow border-b border-stone-700 pb-10 pr-4 pl-4 sm:pl-0"
                    >
                        <div class="max-w-lg flex flex-wrap gap-x-4 gap-y-2">
                            <Badge
                                v-for="lang in job_application.data
                                    .programming_language"
                                :key="lang"
                            >
                                {{ lang }}
                            </Badge>
                        </div>
                    </div>
                </div>
                <div
                    v-if="job_application.data.education_history?.length"
                    class="eval-row"
                >
                    <div
                        class="text-stone-600 font-semibold dark:font-normal dark:text-stone-400 uppercase pl-4 max-w-28 w-28 sm:pl-0"
                    >
                        <h5>Education</h5>
                    </div>
                    <div
                        class="flex-grow border-b border-stone-700 pb-10 pr-4 pl-4 sm:pl-0"
                    >
                        <div class="max-w-lg flex flex-wrap gap-x-4 gap-y-4">
                            <div
                                v-for="education in job_application.data
                                    .education_history"
                                class="text-black dark:text-gray-200"
                            >
                                <p>
                                    {{ education }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="job_application.data?.summary" class="eval-row">
                    <div
                        class="text-stone-600 font-semibold dark:font-normal dark:text-stone-400 uppercase pl-4 max-w-28 w-28 sm:pl-0"
                    >
                        <h5>Summary</h5>
                    </div>
                    <div class="flex-grow pb-10 pr-4 pl-4 sm:pl-0">
                        <div class="max-w-lg flex flex-wrap gap-x-4 gap-y-2">
                            <p class="text-black dark:text-gray-200 leading-7">
                                {{ job_application.data.summary }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dark">
            <Toaster />
        </div>
    </section>
</template>
<style scoped>
.eval-row {
    @apply pl-0 sm:pl-6 lg:pl-10 py-10 flex gap-6 sm:gap-10 lg:gap-20 flex-col sm:flex-row;
}
</style>
