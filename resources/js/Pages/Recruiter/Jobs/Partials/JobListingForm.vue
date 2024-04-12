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
} from "@/components/ui/tooltip";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { ref, watch } from "vue";
import { useAxios } from "@vueuse/integrations/useAxios";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import useSweetAlert from "@/Composables/useSweetAlert.js";

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

                props.form.job_details = result.data;
                props.form.job_title = props.form.job_details.job_title;
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
    <div class="max-w-7xl space-y-6">
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
