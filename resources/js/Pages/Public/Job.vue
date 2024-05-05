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
    file: null,
});

const [UseTemplate, GridForm] = createReusableTemplate();
const isDesktop = useMediaQuery("(min-width: 768px)");

const isOpen = ref(false);

const dropZoneRef = ref(null);
const loading = ref(false);

function onFileChange(files) {
    form.file = files[0];
}

const { files, open, reset, onChange } = useFileDialog({
    accept: "application/pdf",
    multiple: false,
});

const { isOverDropZone } = useDropZone(dropZoneRef, onFileChange);

onChange(onFileChange);

const upload = () => {
    form.post(route("public.jobs.application.store", props.job_listing.id), {
        preserveState: false,
    });
};

const { alertError } = useSweetAlert();

onMounted(() => {
    if (props.has_applied) {
        alertError(
            () => {
                router.visit(route("public.jobs"));
            },
            {
                title: "Already Applied",
                text: "Apply to something else....",
            }
        );
    }
});
</script>

<template>
    <Head :title="job_listing.job_title" />
    <UseTemplate>
        <div class="p-4 sm:p-8 sm:rounded-lg">
            <div
                @click="open"
                :class="
                    isOverDropZone || form.file
                        ? 'text-gray-900 border-gray-950'
                        : 'text-gray-500'
                "
                class="cursor-pointer border-gray-400 border-dotted border-4 mt-2 min-h-52 flex justify-center items-center"
                ref="dropZoneRef"
            >
                <div class="flex flex-wrap gap-4" v-if="form.file">
                    {{ form.file.name }}
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
        <div class="text-white mt-14">
            <div class="space-y-8">
                <div>
                    <h5 class="text-3xl">
                        Job Title: {{ job_listing.job_title }}
                    </h5>
                    <p>
                        {{ job_listing.job_details.company_profile }}
                    </p>
                </div>
                <div>
                    <h3 class="text-3xl">Requirements:</h3>
                    <ul class="list-disc ml-10 mt-2">
                        <li
                            v-for="item in job_listing.job_details.requirements"
                        >
                            {{ item }}
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-3xl">Expected Tasks:</h3>
                    <ul class="list-disc ml-10 mt-2">
                        <li
                            v-for="item in job_listing.job_details
                                .what_will_you_do"
                        >
                            {{ item }}
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-3xl">Benefits:</h3>
                    <ul class="list-disc ml-10 mt-2">
                        <li v-for="item in job_listing.job_details.benefits">
                            {{ item }}
                        </li>
                    </ul>
                </div>
            </div>

            <Dialog v-if="isDesktop" v-model:open="isOpen">
                <DialogTrigger as-child>
                    <div class="py-8">
                        <button class="title-button text-start">Apply</button>
                    </div>
                </DialogTrigger>
                <DialogContent class="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Apply Now</DialogTitle>
                        <DialogDescription>
                            Upload your CV and our recruiters will take a look
                            at it!
                        </DialogDescription>
                    </DialogHeader>
                    <GridForm />
                </DialogContent>
            </Dialog>

            <Drawer v-else v-model:open="isOpen">
                <DrawerTrigger as-child>
                    <div class="py-8">
                        <button class="title-button text-start">Apply</button>
                    </div>
                </DrawerTrigger>
                <DrawerContent>
                    <DrawerHeader class="text-left">
                        <DrawerTitle>Edit profile</DrawerTitle>
                        <DrawerDescription>
                            Make changes to your profile here. Click save when
                            you're done.
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
    </div>
</template>
