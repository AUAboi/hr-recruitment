<script setup>
import { computed } from "vue";
import { SelectIcon, SelectTrigger, useForwardProps } from "radix-vue";
import { ChevronDown } from "lucide-vue-next";
import { cn } from "@/lib/utils";

const props = defineProps({
    disabled: { type: Boolean, required: false },
    asChild: { type: Boolean, required: false },
    as: { type: null, required: false },
    class: { type: null, required: false },
});

const delegatedProps = computed(() => {
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <SelectTrigger
        v-bind="forwardedProps"
        :class="
            cn(
                'flex h-10 w-full items-center justify-between rounded-md border border-stone-200 bg-stone-900-400 px-3 py-2 text-sm ring-offset-white placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-stone-950 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1 dark:border-stone-800 dark:bg-stone-950 dark:ring-offset-stone-950 dark:placeholder:text-stone-400 dark:focus:ring-stone-300',
                props.class
            )
        "
    >
        <slot />
        <SelectIcon as-child>
            <ChevronDown class="w-4 h-4 opacity-50" />
        </SelectIcon>
    </SelectTrigger>
</template>
