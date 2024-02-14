<script setup>
import UserAvatar from "@/Components/UserAvatar.vue";
import MdiTick from "~icons/mdi/tick";
import MdiClose from "~icons/mdi/close";
import MdiHistory from "~icons/mdi/history";
import MdiPhone from "~icons/mdi/phone";
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

const { toast } = useToast();

const props = defineProps({
    user: {
        required: true,
    },
    data: {
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
        <UserAvatar size="lg" :user="user" />
        <h4 class="font-semibold mt-6">
            {{ data.name }}
        </h4>
        <p class="text-gray-300 text-sm">
            {{ data.address }}
        </p>
        <Popover>
            <PopoverTrigger class="absolute text-2xl top-5 left-4">
                <MdiPhone class="text-primaryOrange/80" />
            </PopoverTrigger>
            <PopoverContent side="top" class="dark">
                <p class="text-sm pb-4">Click number to copy to clipboard</p>
                <div class="flex gap-4 text-sm">
                    <span
                        @click="copyText(data.phone_no)"
                        class="cursor-pointer hover:text-yellow-600"
                        >{{ data.phone_no }}</span
                    >
                    <a
                        class="text-blue-600 underline hover:text-blue-700"
                        :href="`tel:${data.phone_no}`"
                        >Call Now?
                    </a>
                </div>
            </PopoverContent>
        </Popover>
    </div>

    <div class="flex gap-4 justify-evenly px-6 py-4 border-b border-stone-700">
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <button
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
                        <MdiTick class="mx-auto text-green-600 text-xl" />
                    </button>
                </TooltipTrigger>
                <TooltipContent>
                    <p>Approve</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <button
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
                        <MdiHistory class="mx-auto text-yellow-600 text-xl" />
                    </button>
                </TooltipTrigger>
                <TooltipContent side="bottom">
                    <p>Decide Later</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <button
                        class="bg-stone-600 w-full rounded-full p-2 transition-colors hover:bg-stone-700"
                    >
                        <MdiClose class="mx-auto text-red-500 text-xl" />
                    </button>
                </TooltipTrigger>
                <TooltipContent>
                    <p>Reject</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    </div>
    <div class="px-6 py-4 text-gray-200">
        <p>Name: {{ data.name }}</p>
        <p>Father Name: {{ data.father_name }}</p>
        <p>Phone No: {{ data.phone_no }}</p>
    </div>
</template>
