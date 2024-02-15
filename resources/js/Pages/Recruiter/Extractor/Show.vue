<script setup>
import { Head } from "@inertiajs/vue3";
import { Badge } from "@/Components/ui/badge";
import ProfileSection from "./Partials/ProfileSection.vue";
import { ref } from "vue";
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/Components/ui/collapsible";
import Toaster from "@/Components/ui/toast/Toaster.vue";

const isOpen = ref(false);

const props = defineProps({
    evaluation: {
        required: true,
    },
});

const MAX_SHOWN_PROJECTS = 3;
console.log(props.evaluation);
</script>

<template>
    <Head title="Extract details" />
    <section class="bg-primaryGray max-w-6xl mx-auto rounded-md">
        <div class="flex flex-col sm:flex-row">
            <div class="sm:w-1/2 sm:border-r border-stone-700">
                <ProfileSection
                    :data="evaluation.data"
                    :user="evaluation.user"
                />
            </div>

            <div class="w-full max-h-[75vh] overflow-y-auto main-scroller">
                <div class="eval-row">
                    <div
                        class="text-stone-400 uppercase flex-grow pl-4 sm:pl-0"
                    >
                        <h5>Skills</h5>
                    </div>
                    <div
                        class="flex-grow border-b border-stone-700 pb-10 pr-4 pl-4 sm:pl-0"
                    >
                        <div class="max-w-lg flex flex-wrap gap-x-4 gap-y-2">
                            <Badge
                                v-for="skill in evaluation.data.skills"
                                :key="skill"
                            >
                                {{ skill }}
                            </Badge>
                        </div>
                    </div>
                </div>
                <div class="eval-row">
                    <div
                        class="text-stone-400 uppercase flex-grow pl-4 sm:pl-0"
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
                                    v-for="(project, index) in evaluation.data
                                        .projects"
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
                                        evaluation.data.projects.length >
                                        MAX_SHOWN_PROJECTS
                                    "
                                    as-child
                                >
                                    <p
                                        v-if="!isOpen"
                                        class="text-blue-500 text-sm hover:underline cursor-pointer"
                                    >
                                        {{
                                            evaluation.data.projects.length -
                                            MAX_SHOWN_PROJECTS
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
                <div class="eval-row">
                    <div
                        class="text-stone-400 uppercase flex-grow pl-4 sm:pl-0"
                    >
                        <h5>Education</h5>
                    </div>
                    <div
                        class="flex-grow border-b border-stone-700 pb-10 pr-4 pl-4 sm:pl-0"
                    >
                        <div class="max-w-lg flex flex-wrap gap-x-4 gap-y-2">
                            <p
                                class="text-gray-200"
                                v-for="education in evaluation.data
                                    .education_history"
                            >
                                {{ education }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="eval-row">
                    <div
                        class="text-stone-400 uppercase flex-grow pl-4 sm:pl-0"
                    >
                        <h5>Summary</h5>
                    </div>
                    <div class="flex-grow pb-10 pr-4 pl-4 sm:pl-0">
                        <div class="max-w-lg flex flex-wrap gap-x-4 gap-y-2">
                            <p class="text-gray-200 leading-7">
                                {{ evaluation.data.summary }}
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
