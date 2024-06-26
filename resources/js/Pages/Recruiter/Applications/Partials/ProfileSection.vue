<script setup>
import UserAvatar from "@/Components/UserAvatar.vue";
import MdiTick from "~icons/mdi/tick";
import MdiClose from "~icons/mdi/close";
import MdiHistory from "~icons/mdi/history";
import MdiPhone from "~icons/mdi/phone";
import MdiDownload from "~icons/mdi/download";
import { useClipboard } from "@vueuse/core";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/Components/ui/tooltip";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";
import { ref } from "vue";
import { useToast } from "@/Components/ui/toast/use-toast";
import { Link } from "@inertiajs/vue3";
import "vue3-circle-progress/dist/circle-progress.css";
import MdiReload from "~icons/mdi/reload";
import Progress from "@/Components/ui/progress/Progress.vue";
import MdiAttachFile from "~icons/mdi/attach-file";
import Modal from "@/Components/Modal.vue";
import AttachmentsCarousel from "./AttachmentsCarousel.vue";
const { toast } = useToast();

const props = defineProps({
    job_application: {
        required: true,
    },
});

const source = ref("Hello");
const { copy, copied, isSupported } = useClipboard({ source });

const copyText = (text) => {
    copy(text);
    toast({
        title: "Text copied to clipboard!",
    });
};

const open = ref(false);

const checkURL = (urlString) => {
    return urlString.match(/\.(jpeg|jpg|gif|png)$/) != null;
};

const openPopup = () => {
    open.value = true;
};
</script>
<template>
    <Modal max-width="full" :show="open" @close="open = !open">
        <div v-if="job_application.attachments.length">
            <AttachmentsCarousel :attachments="job_application.attachments" />
        </div>
        <div class="p-5" v-else>Nothing to show</div>
    </Modal>
    <div class="dark:text-white text-black text-center border-b border-stone-700 py-8 px-4 relative">
        <UserAvatar size="lg" :user="job_application.user" />
        <h4 class="font-semibold mt-6">
            {{ job_application.data.name }}
        </h4>
        <p class="dark:text-gray-300 text-sm">
            {{ job_application.data.address }}
        </p>
        <Popover>
            <PopoverTrigger class="absolute text-2xl top-5 left-4">
                <MdiPhone
                    class="text-darkBlue-600 dark:text-primaryOrange/80 hover:text-darkBlue-800 dark:hover:text-primaryOrange" />
            </PopoverTrigger>
            <PopoverContent side="top" class="dark">
                <p class="text-sm pb-4">Click number to copy to clipboard</p>
                <div class="flex gap-4 text-sm">
                    <span @click="copyText(job_application.data.phone_no)"
                        class="cursor-pointer hover:text-darkBlue-400 dark:hover:text-yellow-600">{{
                            job_application.data.phone_no }}</span>
                    <a class="text-blue-600 underline hover:text-blue-700"
                        :href="`tel:${job_application.data.phone_no}`">Call Now?
                    </a>
                </div>
            </PopoverContent>
        </Popover>
        <Popover>
            <PopoverTrigger class="absolute text-2xl top-5 right-4">
                <MdiDownload
                    class="text-darkBlue-400 dark:text-primaryOrange/60 hover:text-darkBlue-600 dark:hover:text-primaryOrange" />
            </PopoverTrigger>
            <PopoverContent side="top" class="dark">
                <p class="text-sm pb-4">Download PDF CV?</p>
                <div class="flex gap-4 text-sm">
                    <a class="text-blue-600 underline hover:text-blue-700" :href="job_application.download_link"
                        download>Download
                    </a>
                </div>
            </PopoverContent>
        </Popover>
        <MdiAttachFile @click="openPopup"
            class="absolute text-2xl bottom-5 right-4 rotate-12 text-darkBlue-400 dark:text-primaryOrange/60 hover:text-darkBlue-600 dark:hover:text-primaryOrange" />
    </div>

    <div class="flex gap-4 justify-evenly px-6 py-3 border-b border-stone-700">
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <Link as="button" method="patch" :data="{ status: 'APPROVED' }" :href="route(
                        'recruiter.job.applications.updateStatus',
                        job_application.slug
                    )
                        " class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700" :class="job_application.application_status === 'APPROVED'
                            ? 'opacity-100'
                            : 'opacity-50'
                            ">
                    <MdiTick class="mx-auto text-green-600 text-xl" />
                    </Link>
                </TooltipTrigger>
                <TooltipContent>
                    <p>Approve</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <Link as="button" method="patch" :data="{ status: 'PENDING' }" :href="route(
                        'recruiter.job.applications.updateStatus',
                        job_application.slug
                    )
                        " :class="job_application.application_status === 'PENDING'
                            ? 'opacity-100'
                            : 'opacity-50'
                            " class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700">
                    <MdiHistory class="mx-auto text-yellow-600 text-xl" />
                    </Link>
                </TooltipTrigger>
                <TooltipContent side="bottom">
                    <p>Decide Later</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <Link as="button" method="patch" :data="{ status: 'REJECTED' }" :href="route(
                        'recruiter.job.applications.updateStatus',
                        job_application.slug
                    )
                        " :class="job_application.application_status === 'REJECTED'
                            ? 'opacity-100'
                            : 'opacity-50'
                            " class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700">
                    <MdiClose class="mx-auto text-red-500 text-xl" />
                    </Link>
                </TooltipTrigger>
                <TooltipContent>
                    <p>Reject</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    </div>
    <div class="relative px-6 py-6 text-black dark:text-gray-200 sm:max-h-[280px] overflow-y-hidden">
        <h4 class="text-center font-semibold pb-4">Skill Evaluation</h4>

        <div v-if="job_application.score" class="grid grid-rows-2 gap-4 items-center">
            <Link class="absolute right-5 top-6" :href="route(
                'recruiter.job.applications.revaluate',
                job_application.slug
            )
                " preserve-state preserve-scroll>
            <MdiReload class="text-2xl text-center  text-darkBlue-600 dark:text-primaryOrange cursor-pointer" />
            </Link>
            <div class="w-full">
                <p class="mb-2">Relevancy:</p>
                <Progress class="h-2" :model-value="job_application.score.relavancy_score" />
            </div>
            <div class="w-full">
                <p class="my-2">Skill:</p>
                <Progress class="h-2" :model-value="job_application.score.skill_score" />
            </div>
            <div class="w-full">
                <p class="my-2">Experience:</p>

                <Progress class="h-2" :model-value="job_application.score.exprience_score" />
            </div>
        </div>
        <div class="text-center flex flex-col items-center gap-4" v-else>
            <p>Could not calculate</p>
            <Link :href="route(
                'recruiter.job.applications.revaluate',
                job_application.slug
            )
                " preserve-state preserve-scroll>
            <MdiReload class="text-2xl text-darkBlue-600 dark:text-primaryOrange cursor-pointer" />
            </Link>
        </div>
    </div>
</template>
