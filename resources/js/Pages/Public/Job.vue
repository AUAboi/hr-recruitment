<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import {
    createReusableTemplate,
    useDropZone,
    useFileDialog,
    useMediaQuery,
} from "@vueuse/core";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import {
    Drawer,
    DrawerClose,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
    DrawerTrigger,
} from "@/components/ui/drawer";
import useSweetAlert from "@/Composables/useSweetAlert";

const props = defineProps(["job_listing", "has_applied"]);

const form = useForm({
    pdf: null,
    attachments: [],
});

const [UseTemplate, GridForm] = createReusableTemplate();
const isDesktop = useMediaQuery("(min-width: 768px)");

const isOpen = ref(false);

// PDF Handling
const onFilePDFChange = (files) => {
    form.pdf = files[0];
};

const { open: openPDF, onChange: onPDFFileChange } = useFileDialog({
    accept: "application/pdf",
    multiple: false,
});

const pdfDropZoneRef = ref(null);

const { isOverDropZone: isOverPDFDropZone } = useDropZone(pdfDropZoneRef, {
    dataTypes: ["application/pdf"],
    onDrop: onFilePDFChange,
});

onPDFFileChange(onFilePDFChange);

//Attachments Handling

const onFileAttachmentsChange = (files) => {
    form.attachments = files;
};

const { open: openAttachments, onChange: onAttachmentsFileChange } =
    useFileDialog({
        accept: [
            "application/pdf",
            "image/png",
            "image/jpg",
            "image/jpeg",
            "application/msword",
        ],
        multiple: true,
    });

const attachmentsDropZoneRef = ref(null);

const { isOverDropZone: isOverAttachmentsDropZone } = useDropZone(
    attachmentsDropZoneRef,
    {
        dataTypes: [
            "application/pdf",
            "image/png",
            "image/jpg",
            "image/jpeg",
            "application/msword",
        ],
        onDrop: onFileAttachmentsChange,
    }
);

onAttachmentsFileChange(onFileAttachmentsChange);

const upload = () => {
    form.post(route("public.jobs.application.store", props.job_listing.id), {
        preserveState: false,
    });
};

const { alertError } = useSweetAlert();

// onMounted(() => {
//     if (props.has_applied) {
//         alertError(
//             () => {
//                 router.visit(route("public.jobs"));
//             },
//             {
//                 title: "Already Applied",
//                 text: "Apply to something else....",
//             }
//         );
//     }
// });
</script>

<template>
    <Head :title="job_listing.job_title" />
    <UseTemplate>
        <div class="p-4 sm:p-8 sm:rounded-lg">
            <div
                @click="openPDF"
                :class="
                    isOverPDFDropZone || form.pdf
                        ? 'text-gray-900 border-gray-950'
                        : 'text-gray-500'
                "
                class="cursor-pointer border-gray-400 border-dotted border-4 mt-2 min-h-52 flex justify-center items-center"
                ref="pdfDropZoneRef"
            >
                <div class="flex flex-wrap gap-4" v-if="form.pdf">
                    {{ form.pdf.name }}
                </div>
                <p v-else>Drop files here</p>
            </div>
        </div>
        <p class="text-sm text-muted-foreground">
            Add any relevant attachments here
        </p>
        <div class="px-4 sm:px-8 sm:rounded-lg">
            <div
                @click="openAttachments"
                :class="
                    isOverAttachmentsDropZone || form.attachments.length
                        ? 'text-gray-900 border-gray-950'
                        : 'text-gray-500'
                "
                class="cursor-pointer border-gray-400 border-dotted border-4 mt-2 min-h-52 flex justify-center items-center"
                ref="attachmentsDropZoneRef"
            >
                <div
                    class="flex flex-wrap gap-4"
                    v-if="form.attachments.length"
                >
                    <div v-for="file in form.attachments">
                        {{ file.name }}
                    </div>
                </div>
                <p v-else>Drop files here</p>
            </div>
            <button
                @click="upload"
                class="bg-black text-white mt-8 py-2 px-4 rounded-md flex mx-auto"
            >
                Upload
            </button>
        </div>
    </UseTemplate>

    <div class="container mx-auto">
        <div class="text-white mt-14 p-8 lg:p-16 bg-[#111] rounded-md">
            <div class="space-y-8">
                <div class="w-full flex justify-between items-center">
                    <div class="flex gap-2 items-center">
                        <div class="w-2 h-2 bg-darkOrange rounded-full"></div>
                        <span class="font-semibold text-darkOrange"
                            >Currently Hiring - {{ job_listing.type }}</span
                        >
                    </div>

                    <Dialog v-if="isDesktop" v-model:open="isOpen">
                        <DialogTrigger as-child>
                            <div class="">
                                <button class="button-action text-start">
                                    Apply
                                </button>
                            </div>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-[425px]">
                            <DialogHeader>
                                <DialogTitle>Apply Now</DialogTitle>
                                <DialogDescription>
                                    Upload your CV and our recruiters will take
                                    a look at it!
                                </DialogDescription>
                            </DialogHeader>
                            <GridForm />
                        </DialogContent>
                    </Dialog>

                    <Drawer v-else v-model:open="isOpen">
                        <DrawerTrigger as-child>
                            <div class="py-8">
                                <button class="title-button text-start">
                                    Apply Now
                                </button>
                            </div>
                        </DrawerTrigger>
                        <DrawerContent>
                            <DrawerHeader class="text-left">
                                <DrawerTitle>Edit profile</DrawerTitle>
                                <DrawerDescription>
                                    Upload your CV and our recruiters will take
                                    a look at it!
                                </DrawerDescription>
                            </DrawerHeader>
                            <GridForm />
                            <DrawerFooter class="pt-2">
                                <DrawerClose as-child>
                                    <button variant="outline">Cancel</button>
                                </DrawerClose>
                            </DrawerFooter>
                        </DrawerContent>
                    </Drawer>
                </div>
                <div class="flex flex-col gap-2">
                    <div>
                        <h5
                            class="text-3xl font-bold text-darkOrange tracking-wide"
                        >
                            {{ job_listing.job_title }}
                        </h5>
                        <p>
                            {{ job_listing.job_details.company_profile }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <div
                            class="flex items-center gap-2 py-2 px-4 text-xs text-textGray rounded-full hover:brightness-150 transition-all duration-200"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="#858585"
                                    d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5"
                                />
                            </svg>
                            <span class="text-textGray text-sm font-semibold"
                                >Location, LOC</span
                            >
                        </div>
                        <div
                            class="flex items-center justify-between bg-stone-900 hover:bg-primaryGray gap-4 px-6 py-2 rounded-full cursor-pointer transition-all duration-200"
                        >
                            <span>{{ job_listing.type }}</span>
                        </div>
                        <div class="flex items-start justify-start gap-2">
                            <div
                                v-for="(tag, index) in job_listing.tags"
                                :key="index"
                                class="flex items-center justify-between bg-stone-900 hover:bg-primaryGray gap-4 px-6 py-2 rounded-full cursor-pointer transition-all duration-200"
                            >
                                <span>{{ tag }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-3xl mb-8 font-semibold text-darkOrange">
                        Requirements:
                    </h3>
                    <ul class="list-disc ml-10 mt-2">
                        <li
                            v-for="item in job_listing.job_details.requirements"
                            class="flex items-center gap-4 my-4 text-primaryOrange"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="currentColor"
                                    d="M20 12a8 8 0 0 1-8 8a8 8 0 0 1-8-8a8 8 0 0 1 8-8c.76 0 1.5.11 2.2.31l1.57-1.57A9.8 9.8 0 0 0 12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10M7.91 10.08L6.5 11.5L11 16L21 6l-1.41-1.42L11 13.17z"
                                />
                            </svg>
                            <span class="text-white"> {{ item }}</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-3xl mb-8 font-semibold text-darkOrange">
                        Expected Tasks:
                    </h3>
                    <ul class="list-disc ml-10 mt-2">
                        <li
                            v-for="item in job_listing.job_details
                                .what_will_you_do"
                            class="flex items-center gap-4 my-4 text-primaryOrange"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="currentColor"
                                    d="M20 12a8 8 0 0 1-8 8a8 8 0 0 1-8-8a8 8 0 0 1 8-8c.76 0 1.5.11 2.2.31l1.57-1.57A9.8 9.8 0 0 0 12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10M7.91 10.08L6.5 11.5L11 16L21 6l-1.41-1.42L11 13.17z"
                                />
                            </svg>
                            <span class="text-white"> {{ item }}</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-3xl mb-8 font-semibold text-darkOrange">
                        Benefits:
                    </h3>
                    <ul class="ml-10 mt-2">
                        <li
                            v-for="item in job_listing.job_details.benefits"
                            class="flex items-center gap-4 my-4 text-primaryOrange"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="currentColor"
                                    d="M20 12a8 8 0 0 1-8 8a8 8 0 0 1-8-8a8 8 0 0 1 8-8c.76 0 1.5.11 2.2.31l1.57-1.57A9.8 9.8 0 0 0 12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10M7.91 10.08L6.5 11.5L11 16L21 6l-1.41-1.42L11 13.17z"
                                />
                            </svg>
                            <span class="text-white"> {{ item }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
