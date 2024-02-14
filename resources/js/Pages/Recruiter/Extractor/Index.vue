<script setup>
import UserAvatar from "@/Components/UserAvatar.vue";
import { Head } from "@inertiajs/vue3";
import MdiTick from "~icons/mdi/tick";
import MdiClose from "~icons/mdi/close";
import MdiHistory from "~icons/mdi/history";
import { Badge } from "@/components/ui/badge";

const rawJSON = {
    name: "Basim Bashir",
    father_name: "Haseeb Bashir",
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
    father_name: "Haseeb Bashir",
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
    project:
        "Developed and implemented a chatbot using GPT-2 as a Language Model, enhancing customer support and engagement. Created a voice bot capable of processing voice input and generating voice-based responses, improving user interaction. Built a call bot for automated phone calls, improving efficiency and customer experience in call centers. Person Detection and Recognition, Fall Detection using OpenCV and MediaPipe, Online Live Transmission Attendance using OpenCV and VGGNet16, Person Detection using YOLO, Chatbot Development using GPT-2, Voice Bot Development, Call Bot Development",
    programming_language: "",
    education_history:
        "Bachelor Degree of Software Engineering from Riphah International University",
    summary:
        "Accomplished data scientist with expertise in machine learning, computer vision, and natural language processing. Proficient in developing end-to-end market-ready solutions and leveraging advanced technologies to deliver impactful results. Experienced in person detection and recognition, fall detection, chatbot development, and voice bot implementation. Strong background in software engineering with a Bachelor of Science degree in Software Engineering.",
};

const props = defineProps({
    user: {
        required: true,
    },
});
</script>

<template>
    <Head title="Extract details" />
    <section class="bg-primaryGray max-w-6xl mx-auto rounded-md">
        <div class="flex">
            <div class="w-1/2 border-r border-stone-700">
                <div
                    class="text-white text-center border-b border-stone-700 py-8"
                >
                    <UserAvatar size="lg" :user="user" />
                    <h4 class="font-semibold">
                        {{ user.full_name }}
                    </h4>
                </div>

                <div
                    class="flex gap-4 justify-evenly px-6 py-4 border-b border-stone-700"
                >
                    <button
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
                        <MdiTick class="mx-auto text-green-600 text-xl" />
                    </button>
                    <button
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
                        <MdiHistory class="mx-auto text-yellow-600 text-xl" />
                    </button>
                    <button
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
                        <MdiClose class="mx-auto text-red-500 text-xl" />
                    </button>
                </div>
            </div>

            <div class="w-full">
                <div class="px-10 py-6 flex gap-10">
                    <div class="text-stone-400 uppercase">
                        <h5>Skills</h5>
                    </div>
                    <div
                        class="max-w-md flex flex-wrap gap-x-4 gap-y-2 border-b border-stone-700 pb-6"
                    >
                        <Badge v-for="skill in data.skills">
                            {{ skill }}
                        </Badge>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
