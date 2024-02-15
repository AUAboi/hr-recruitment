<script setup>
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    file: null,
});

const onFileChange = (e) => {
    if (e.target && e.target.files) {
        form.file = e.target.files[0];
        console.log(e.target.files[0]);
    }
};

const upload = () => {
    form.post(route("recruiter.evaluation.store"));
};
</script>
<template>
    <Head title="Upload CV" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">Upload CV</h2>

    <div class="max-w-7xl space-y-6">
        <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg text-white">
            <div class="space-y-4">
                <div>
                    <label for="file">Upload cv</label>
                </div>
                <input @change="onFileChange" type="file" />
            </div>
            <button
                @click="upload"
                class="bg-white text-black mt-4 px-4 rounded-md"
            >
                Upload
            </button>
        </div>
    </div>
</template>
