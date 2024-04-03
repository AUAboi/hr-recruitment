<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import FormInputText from "@/Components/FormInputText.vue";
import FormInputTextArea from "@/Components/FormInputTextArea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import MdiCancelBold from "~icons/mdi/cancel-bold";
import MdiMagic from "~icons/mdi/magic";
import Loader from "@/Components/ui/Loader.vue";
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
import { ref } from "vue";
import { useAxios } from "@vueuse/integrations/useAxios";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import useSweetAlert from "@/Composables/useSweetAlert.js";

const props = defineProps({
    job_details: {
        required: true,
    },
});

const form = useForm({
    job_details: props.job_details,
    job_prompt: "",
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
                        job_prompt: form.job_prompt,
                    },
                });

                form.job_details = result.data;
            } else {
                showDialog.value = true;
            }
        },
        {
            title: `This will reset current form data. Proceed?`,
        }
    );
};

const submit = () => {
    form.patch(route("recruiter.job.update"));
};
</script>
<template>
    <Head title="Edit Job" />
    <h2 class="font-semibold text-xl text-white pb-6">Edit Job</h2>
    <Loader v-if="isLoading" />
    <div class="max-w-7xl space-y-6">
        <div class="relative">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <MdiMagic
                            @click="showDialog = true"
                            :class="showDialog ? 'text-violet-500' : ''"
                            class="mb-6 text-2xl ml-auto cursor-pointer hover:text-purple-400 transition-all duration-150 text-white"
                        />
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>Generate via A.I</p>
                    </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>

        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <div>
                <FormInputText
                    label="Job Title"
                    class="w-1/2"
                    v-model="form.job_details.job_title"
                />
                <FormInputTextArea
                    label="Company Profile"
                    v-model="form.job_details.company_profile"
                />
            </div>

            <PrimaryButton @click="submit">Generate</PrimaryButton>
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
