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
const rawJSON = {
    name: "Basim Bashir",
    last_name: "Haseeb Bashir",
    phone_no: "+92 30171 444 99",
    address: "P37, New Haseeb Shaheed Colony, Faislabad, Pakistan",
    skills: "Machine Learning, Deep Learning, Computer Vision, Natural Language Processing, Database Management, Data Analysis",
    project:
        "Developed and implemented a chatbot using GPT-2 as a Language Model, enhancing customer support and engagement. Created a voice bot capable of processing voice input and generating voice-based responses, improving user interaction. Built a call bot for automated phone calls, improving efficiency and customer experience in call centers. Person Detection and Recognition, Fall Detection using OpenCV and MediaPipe, Online Live Transmission Attendance using OpenCV and VGGNet16, Person Detection using YOLO, Chatbot Development using GPT-2, Voice Bot Development, Call Bot Development",
    programming_language: "",
    education_history:
        "Bachelor Degree of Software Engineering from Riphah International University",
    summary:
        "Accomplished data scientist with expertise in machine learning, computer vision, and natural language processing. Proficient in developing end-to-end market-ready solutions and leveraging advanced technologies to deliver impactful results. Experienced in person detection and recognition, fall detection, chatbot development, and voice bot implementation. Strong background in software engineering with a Bachelor of Science degree in Software Engineering.",
};

const data = {
    name: "Basim Bashir",
    last_name: "Haseeb Bashir",
    phone_no: "+92 30171 444 99",
    address: "P37, New Haseeb Shaheed Colony, Faislabad, Pakistan",
    skills: [
        "Machine Learning",
        "Deep Learning",
        "Computer Vision",
        "Natural Language Processing",
        "Database Management",
        "Data Analysis",
    ],
    projects: [
        "Developed and implemented a chatbot using GPT-2 as a Language Model, enhancing customer support and engagement",
        "Created a voice bot capable of processing voice input and generating voice-based responses, improving user interaction",
        "Built a call bot for automated phone calls, improving efficiency and customer experience in call centers",
        "Person Detection and Recognition",
        "Fall Detection using OpenCV and MediaPipe",
        "Online Live Transmission Attendance using OpenCV and VGGNet16",
        "Person Detection using YOLO",
        "Chatbot Development using GPT-2",
        "Voice Bot Development",
        "Call Bot Development",
    ],
    programming_language: "",
    education_history: [
        "Bachelor Degree of Software Engineering from Riphah International University",
    ],
    summary:
        "Accomplished data scientist with expertise in machine learning, computer vision, and natural language processing. Proficient in developing end-to-end market-ready solutions and leveraging advanced technologies to deliver impactful results. Experienced in person detection and recognition, fall detection, chatbot development, and voice bot implementation. Strong background in software engineering with a Bachelor of Science degree in Software Engineering.",
};

const props = defineProps({
    user: {
        required: true,
    },
});

const MAX_SHOWN_PROJECTS = 3;
</script>

<template>
    <Head title="Extract details" />
    <section class="bg-primaryGray max-w-6xl mx-auto rounded-md">
        <div class="flex flex-col sm:flex-row">
            <div class="sm:w-1/2 sm:border-r border-stone-700">
                <ProfileSection :data="data" :user="user" />
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
                            <Badge v-for="skill in data.skills" :key="skill">
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
                                    v-for="(project, index) in data.projects"
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
                                        data.projects.length >
                                        MAX_SHOWN_PROJECTS
                                    "
                                    as-child
                                >
                                    <p
                                        v-if="!isOpen"
                                        class="text-blue-500 text-sm hover:underline cursor-pointer"
                                    >
                                        {{
                                            data.projects.length -
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
                                v-for="education in data.education_history"
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
                                {{ data.summary }}
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
