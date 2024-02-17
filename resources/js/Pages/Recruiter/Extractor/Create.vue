<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useDropZone } from "@vueuse/core";
import { ref } from "vue";
import { useFileDialog } from "@vueuse/core";

const props = defineProps({
    evaluations: {
        required: false,
    },
});

const dropZoneRef = ref(null);

function onFileChange(files) {
    form.file = files[0];
}

const form = useForm({
    file: null,
});

const { files, open, reset, onChange } = useFileDialog();

const { isOverDropZone } = useDropZone(dropZoneRef, onFileChange);

onChange(onFileChange);

const upload = () => {
    form.post(route("recruiter.evaluation.store"));
};
</script>
<template>
    <Head title="Upload CV" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Upload CV</h2>

    <div class="max-w-7xl space-y-6">
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <div
                @click="open"
                :class="
                    isOverDropZone || form.file
                        ? 'text-white border-white'
                        : 'text-stone-300'
                "
                class="bg-stone-700 cursor-pointer border-dotted border-primaryGray border-4 mt-10 h-52 flex justify-center items-center"
                ref="dropZoneRef"
            >
                <p v-if="form.file">{{ form.file.name }}</p>
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
    </div>
</template>
