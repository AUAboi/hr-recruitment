<script setup>
import ImagePreview from "@/Components/ImagePreview.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { urlToImageFile } from "@/utils";
import FormInputImage from "@/Components/FormInputImage.vue";
import { ref } from "vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    _method: "put",
    image: null,
});

const handleSelectedMedia = (files) => {
    form.image = files;
};

const setFormValues = async () => {
    form.image = await urlToImageFile(user.media);
};

onMounted(setFormValues);

const submit = () => {
    form.post(route("recruiter.profile.updateImage"));
};

const inputFile = ref(null);

const previewClick = () => {
    inputFile.value.input.click();
};
</script>

<template>
    <Head title="Profile" />

    <h2 class="font-semibold text-xl text-white leading-tight">Profile</h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <ImagePreview
                @click="previewClick"
                class="rounded-full relative w-52 before:absolute before:bg-black cursor-pointer before:w-full before:h-full before:z-10 before:rounded-full before:transition-opacity before:opacity-0 hover:before:opacity-55"
                :src="form.image ?? '/images/user.png'"
            />
            <FormInputImage
                @selected="handleSelectedMedia"
                hidden
                ref="inputFile"
                v-model="form.image"
            />
            <button @click="submit" class="bg-white px-2">SUBMIT</button>
            <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-primaryGray shadow sm:rounded-lg">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>
</template>
