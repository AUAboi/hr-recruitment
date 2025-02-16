<script setup>
import FormInputText from "@/Components/FormInputText.vue";
import FormInputTextArea from "@/Components/FormInputTextArea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import MdiCancelBold from "~icons/mdi/cancel-bold";
import MdiMagic from "~icons/mdi/magic";
import Loader from "@/Components/Loader.vue";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/Components/ui/tooltip";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import { ref, computed, watch } from "vue";
import { useAxios } from "@vueuse/integrations/useAxios";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import useSweetAlert from "@/Composables/useSweetAlert.js";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import {
    CommandEmpty,
    CommandGroup,
    CommandItem,
    CommandList,
} from "@/Components/ui/command";
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from "@/Components/ui/tags-input";
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxInput,
    ComboboxPortal,
    ComboboxRoot,
} from "radix-vue";

const frameworks = [
    { value: "next.js", label: "Next.js" },
    { value: "sveltekit", label: "SvelteKit" },
    { value: "nuxt", label: "Nuxt" },
    { value: "remix", label: "Remix" },
    { value: "astro", label: "Astro" },
];

const open = ref(false);
const searchTerm = ref("");

const filteredFrameworks = computed(() =>
    frameworks.filter((i) => !props.form.tags.includes(i.label))
);

const props = defineProps({
    form: {
        required: true,
    },
});

const { alertConfirm } = useSweetAlert();

const showDialog = ref(false);

const { isLoading, isFinished, execute } = useAxios(
    "/generate_job_description",
    {
        method: "POST",
    },
    {
        immediate: false,
    }
);

const fetchAIData = async () => {
    showDialog.value = false;
    alertConfirm(
        async (result) => {
            if (result.isConfirmed) {
                let result = await execute({
                    params: {
                        job_prompt: props.form.job_prompt,
                    },
                });

                console.log(result.data.value);

                props.form.job_details = result.data.value.job_details;
                props.form.job_title = result.data.value.job_title;
            } else {
                showDialog.value = true;
            }
        },
        {
            title: "This will reset current form data. Proceed?",
            text: "Data will not be saved to database until you press save button",
        }
    );
};

const emits = defineEmits(["send"]);

const showSuccess = ref(false);

watch(
    () => isFinished.value,
    () => {
        if (isFinished) {
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
            }, 4000);
        }
    }
);
</script>
<template>
    <Loader v-if="isLoading">
        <template #loader>
            <MdiMagic class="loading-stick" />
        </template>
    </Loader>
    <div class="max-w-7xl space-y-6 flex-grow">
        <div class="relative">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <MdiMagic
                            @click="showDialog = true"
                            :class="
                                showDialog || isLoading
                                    ? 'text-violet-500 '
                                    : ''
                            "
                            class="mb-6 text-2xl ml-auto cursor-pointer hover:text-purple-400 transition-all duration-150 text-white"
                        />
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>Generate via A.I</p>
                    </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
        <div class="text-red-600" v-if="form.hasErrors">
            Atleast one of each field is required
        </div>
        <div class="text-green-500" v-if="showSuccess">
            Job details generated successfully!
        </div>
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <div>
                <FormInputText
                    label="Job Title"
                    class="w-1/2"
                    v-model="form.job_title"
                />
                <FormInputTextArea
                    label="Company Profile"
                    class="pb-0"
                    v-model="form.job_details.company_profile"
                />
            </div>
            <TagsInput class="px-0 gap-0 w-80" :model-value="form.tags">
                <div class="flex gap-2 flex-wrap items-center px-3">
                    <TagsInputItem
                        v-for="item in form.tags"
                        :key="item"
                        :value="item"
                    >
                        <TagsInputItemText />
                        <TagsInputItemDelete />
                    </TagsInputItem>
                </div>

                <ComboboxRoot
                    v-model="form.tags"
                    v-model:open="open"
                    v-model:search-term="searchTerm"
                    class="w-full"
                >
                    <ComboboxAnchor as-child>
                        <ComboboxInput placeholder="Framework..." as-child>
                            <TagsInputInput
                                class="w-full px-3"
                                :class="form.tags.length > 0 ? 'mt-2' : ''"
                                @keydown.enter.prevent
                            />
                        </ComboboxInput>
                    </ComboboxAnchor>

                    <ComboboxPortal>
                        <ComboboxContent>
                            <CommandList
                                position="popper"
                                class="w-[--radix-popper-anchor-width] rounded-md mt-2 border bg-popover text-popover-foreground shadow-md outline-none data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2"
                            >
                                <CommandEmpty />
                                <CommandGroup>
                                    <CommandItem
                                        v-for="framework in filteredFrameworks"
                                        :key="framework.value"
                                        :value="framework.label"
                                        @select.prevent="
                                            (ev) => {
                                                if (
                                                    typeof ev.detail.value ===
                                                    'string'
                                                ) {
                                                    searchTerm = '';
                                                    form.tags.push(
                                                        ev.detail.value
                                                    );
                                                }

                                                if (
                                                    filteredFrameworks.length ===
                                                    0
                                                ) {
                                                    open = false;
                                                }
                                            }
                                        "
                                    >
                                        {{ framework.label }}
                                    </CommandItem>
                                </CommandGroup>
                            </CommandList>
                        </ComboboxContent>
                    </ComboboxPortal>
                </ComboboxRoot>
            </TagsInput>
            <Select v-model="form.status">
                <SelectTrigger>
                    <SelectValue placeholder="Set Job Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Status</SelectLabel>
                        <SelectItem value="DRAFT"> Draft </SelectItem>
                        <SelectItem value="PUBLISHED"> Published </SelectItem>
                        <SelectItem value="FULLFILLED"> Fullfilled </SelectItem>
                        <SelectItem value="DEACTIVATED">
                            Deactivated
                        </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <div class="flex flex-col lg:flex-row gap-0 lg:gap-20">
                <div class="w-full lg:w-1/2">
                    <p class="mb-2">Requirements</p>
                    <div
                        class="flex"
                        v-for="(requirement, index) in form.job_details
                            .requirements"
                        :key="index"
                    >
                        <FormInputText
                            v-model="form.job_details.requirements[index]"
                        />
                        <MdiCancelBold
                            v-if="form.job_details.requirements.length > 1"
                            @click="
                                form.job_details.requirements.splice(index, 1)
                            "
                            class="mt-4 text-red-700 cursor-pointer"
                        />
                    </div>
                    <button
                        @click.prevent="form.job_details.requirements.push('')"
                        class="mb-10 text-yellow-500 underline"
                    >
                        Add another
                    </button>
                </div>
                <div class="w-full lg:w-1/2">
                    <p class="mb-2">Expected Tasks</p>
                    <div
                        class="flex"
                        v-for="(task, index) in form.job_details
                            .what_will_you_do"
                        :key="index"
                    >
                        <FormInputText
                            required
                            v-model="form.job_details.what_will_you_do[index]"
                        />
                        <MdiCancelBold
                            v-if="form.job_details.what_will_you_do.length > 1"
                            @click="
                                form.job_details.what_will_you_do.splice(
                                    index,
                                    1
                                )
                            "
                            class="mt-4 text-red-700 cursor-pointer"
                        />
                    </div>
                    <button
                        @click.prevent="
                            form.job_details.what_will_you_do.push('')
                        "
                        class="mb-10 text-yellow-500 underline"
                    >
                        Add another
                    </button>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                <p class="mb-2">Benefits</p>
                <div
                    class="flex"
                    v-for="(benefit, index) in form.job_details.benefits"
                    :key="index"
                >
                    <FormInputText v-model="form.job_details.benefits[index]" />
                    <MdiCancelBold
                        v-if="form.job_details.benefits.length > 1"
                        @click="form.job_details.benefits.splice(index, 1)"
                        class="mt-4 text-red-700 cursor-pointer"
                    />
                </div>
                <button
                    @click.prevent="form.job_details.benefits.push('')"
                    class="mb-10 text-yellow-500 underline"
                >
                    Add another
                </button>
            </div>
        </div>

        <PrimaryButton class="h-fit" @click.prevent="$emit('send')"
            >Save</PrimaryButton
        >
    </div>

    <Dialog v-model:open="showDialog">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Type Prompt</DialogTitle>
                <DialogDescription>
                    <FormInputText
                        v-model="form.job_prompt"
                        class="pt-4"
                        type="text"
                    />
                </DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <SecondaryButton @click.prevent="fetchAIData" class="mr-6">
                    Generate
                </SecondaryButton>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
<style scoped>
.loading-stick {
    @apply absolute top-0 left-0 right-0 bottom-0 m-auto text-7xl text-violet-700;

    animation: magic 4s ease infinite;
}

@keyframes magic {
    0% {
        transform: rotate(0deg) translateX(0px) rotate(0deg) scale(1);
        transform-origin: 50% 50%;
    }

    15% {
        transform: rotate(0deg) translateX(50px) rotate(-70deg) scale(1);
        transform-origin: 50% 50%;
    }

    70% {
        transform: rotate(360deg) translateX(0px) rotate(-360deg) scale(1);
        transform-origin: 50% 50%;
    }

    77% {
        transform: rotate(360deg) translateX(0px) rotate(-360deg) scale(0.9);
        transform-origin: 50% 50%;
    }

    80% {
        transform: rotate(360deg) translateX(0px) rotate(-360deg) scale(1);
        transform-origin: 50% 50%;
    }

    100% {
        transform: rotate(360deg) translateX(0px) rotate(-360deg) scale(1);
        transform-origin: 50% 50%;
    }
}
</style>
