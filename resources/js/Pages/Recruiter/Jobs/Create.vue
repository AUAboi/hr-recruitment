<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import FormInputText from "@/Components/FormInputText.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import axios from "axios";

const props = defineProps({
    job_prompt: {
        type: String,
        required: false,
        default: () => "",
    },
    job_details: {
        type: Object,
        required: false,
        default: () => {},
    },
});

const form = useForm({
    job_prompt: props.job_prompt,
    job_details: props.job_details,
});

const showDialog = ref(false);

const fetchAPIResponse = async () => {
    try {
        let result = await axios.post("/generate_job_description", {
            job_prompt: form.job_prompt,
        });

        form.job_details = result.data;
    } catch (error) {
        console.log(error);
    }
};

const submit = () => {
    form.post(route("recruiter.job.store"), {
        onSuccess: (e) => console.log(e),
    });
};
</script>
<template>
    <Head title="Create Job" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Create Job</h2>
    {{ form.job_details }}
    <div class="max-w-7xl space-y-6">
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <FormInputText
                label="Enter prompt to generate job listing"
                :error="form.errors.job_prompt"
                v-model="form.job_prompt"
            />

            <PrimaryButton @click="fetchAPIResponse">Generate</PrimaryButton>
        </div>
    </div>
    <Dialog :open="showDialog">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Edit profile</DialogTitle>
                <DialogDescription>
                    Make changes to your profile here. Click save when you're
                    done.
                </DialogDescription>
            </DialogHeader>

            <DialogFooter> Save changes </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
