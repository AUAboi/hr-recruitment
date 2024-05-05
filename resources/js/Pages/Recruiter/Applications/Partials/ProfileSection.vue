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
import { Progress } from "@/Components/ui/progress";
import { Link } from "@inertiajs/vue3";

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
</script>
<template>
    <div
        class="text-white text-center border-b border-stone-700 py-8 px-4 relative"
    >
        <UserAvatar
            size="lg"
            :user="job_application.user"
            :name="job_application.data.name"
        />
        <h4 class="font-semibold mt-6">
            {{ job_application.data.name }}
        </h4>
        <p class="text-gray-300 text-sm">
            {{ job_application.data.address }}
        </p>
        <Popover>
            <PopoverTrigger class="absolute text-2xl top-5 left-4">
                <MdiPhone
                    class="text-primaryOrange/80 hover:text-primaryOrange"
                />
            </PopoverTrigger>
            <PopoverContent side="top" class="dark">
                <p class="text-sm pb-4">Click number to copy to clipboard</p>
                <div class="flex gap-4 text-sm">
                    <span
                        @click="copyText(job_application.data.phone_no)"
                        class="cursor-pointer hover:text-yellow-600"
                        >{{ job_application.data.phone_no }}</span
                    >
                    <a
                        class="text-blue-600 underline hover:text-blue-700"
                        :href="`tel:${job_application.data.phone_no}`"
                        >Call Now?
                    </a>
                </div>
            </PopoverContent>
        </Popover>
        <Popover>
            <PopoverTrigger class="absolute text-2xl top-5 right-4">
                <MdiDownload
                    class="text-primaryOrange/60 hover:text-primaryOrange"
                />
            </PopoverTrigger>
            <PopoverContent side="top" class="dark">
                <p class="text-sm pb-4">Download PDF CV?</p>
                <div class="flex gap-4 text-sm">
                    <a
                        class="text-blue-600 underline hover:text-blue-700"
                        :href="job_application.download_link"
                        download
                        >Download
                    </a>
                </div>
            </PopoverContent>
        </Popover>
    </div>

    <div class="flex gap-4 justify-evenly px-6 py-6 border-b border-stone-700">
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <Link
                        as="button"
                        method="patch"
                        :data="{ status: 'APPROVED' }"
                        :href="
                            route(
                                'recruiter.job.applications.updateStatus',
                                job_application.slug
                            )
                        "
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                        :class="
                            job_application.application_status === 'APPROVED'
                                ? 'opacity-100'
                                : 'opacity-50'
                        "
                    >
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
                    <Link
                        as="button"
                        method="patch"
                        :data="{ status: 'PENDING' }"
                        :href="
                            route(
                                'recruiter.job.applications.updateStatus',
                                job_application.slug
                            )
                        "
                        :class="
                            job_application.application_status === 'PENDING'
                                ? 'opacity-100'
                                : 'opacity-50'
                        "
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
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
                    <Link
                        as="button"
                        method="patch"
                        :data="{ status: 'REJECTED' }"
                        :href="
                            route(
                                'recruiter.job.applications.updateStatus',
                                job_application.slug
                            )
                        "
                        :class="
                            job_application.application_status === 'REJECTED'
                                ? 'opacity-100'
                                : 'opacity-50'
                        "
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
                        <MdiClose class="mx-auto text-red-500 text-xl" />
                    </Link>
                </TooltipTrigger>
                <TooltipContent>
                    <p>Reject</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    </div>
    <div
        v-if="job_application.data.skills.length"
        class="px-6 py-6 text-gray-200 space-y-4 sm:max-h-[280px] overflow-y-auto main-scroller main-scroller"
    >
        <h4 class="text-center font-semibold pb-4">Skill Evaluation</h4>
        <div
            v-for="skill in job_application.data.skills"
            class="grid grid-cols-2 gap-4 items-center"
        >
            <p class="text-sm">{{ skill }}</p>
            <div class="flex-grow">
                <Progress class="h-1" :model-value="Math.random() * 100" />
            </div>
        </div>
    </div>
</template>
