<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { useDropZone } from "@vueuse/core";
import { ref } from "vue";
import { useFileDialog } from "@vueuse/core";
import Loader from "@/Components/Loader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    evaluations: {
        required: false,
    },
});

const dropZoneRef = ref(null);
const loading = ref(false);

function onFileChange(files) {
    form.files = files;
}

const form = useForm({
    files: [],
});

const { files, open, reset, onChange } = useFileDialog();

const { isOverDropZone } = useDropZone(dropZoneRef, onFileChange);

onChange(onFileChange);

const upload = () => {
    form.post(route("recruiter.evaluation.store"), {
        preserveState: false,
        hideProgress: true,
    });
};

router.on("progress", (event) => {
    loading.value = true;
});

router.on("finish", (e) => {
    loading.value = false;
});
</script>
<template>
    <Head title="Upload CV" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Upload CV</h2>
    <Loader v-if="loading" />
    <div class="max-w-7xl space-y-6 px-8">
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <div
                @click="open"
                :class="
                    isOverDropZone || form.files.length
                        ? 'text-white border-white'
                        : 'text-stone-300'
                "
                class="bg-stone-700 cursor-pointer border-dotted border-primaryGray border-4 mt-10 min-h-52 flex justify-center items-center"
                ref="dropZoneRef"
            >
                <div class="flex flex-wrap gap-4" v-if="form.files.length">
                    <div v-for="file in form.files">
                        {{ file.name }}
                    </div>
                </div>
                <p v-else>Drop files here</p>
            </div>
            <button
                @click="upload"
                class="bg-white text-black mt-4 px-4 rounded-md"
            >
                Upload
            </button>
        </div>
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <Link
                :href="route('recruiter.evaluation.show', evalu.id)"
                class="block text-blue-400 underline"
                v-for="evalu in evaluations"
            >
                {{ evalu.data.name }}
            </Link>
        </div>
        <PrimaryButton>
            <a :href="route('recruiter.evaluation.export')">Export to CSV</a>
        </PrimaryButton>
    </div>
</template>
