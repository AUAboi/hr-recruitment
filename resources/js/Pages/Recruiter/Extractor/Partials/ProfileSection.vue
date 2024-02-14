<script setup>
import UserAvatar from "@/Components/UserAvatar.vue";
import MdiTick from "~icons/mdi/tick";
import MdiClose from "~icons/mdi/close";
import MdiHistory from "~icons/mdi/history";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/Components/ui/tooltip";

const props = defineProps({
    user: {
        required: true,
    },
});
</script>
<template>
    <div class="text-white text-center border-b border-stone-700 py-8">
        <UserAvatar size="lg" :user="user" />
        <h4 class="font-semibold">
            {{ user.full_name }}
        </h4>
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
</template>
