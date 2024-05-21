<script setup>
import FormInputImage from "@/Components/FormInputImage.vue";
import ImagePreview from "@/Components/ImagePreview.vue";
import UpdatePasswordForm from "@/Pages/Public/Profile/partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Public/Profile/partials/UpdateProfileInformationForm.vue";
import { urlToImageFile } from "@/utils";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const form = useForm({
    _method: "put",
    image: null,
});

const user = usePage().props.auth.user;

const handleSelectedMedia = (files) => {
    form.image = files;
    submit();
};

const setFormValues = async () => {
    form.image = await urlToImageFile(user.media);
};

onMounted(setFormValues);

const submit = () => {
    form.post(route("applicant.profile.updateImage"));
};

const inputFile = ref(null);

const previewClick = () => {
    inputFile.value.input.click();
};
</script>

<template>
    <Head :title="`${user.full_name} Profile`" />

    <div class="pb-12 pt-36">
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
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
            >
                <UpdateProfileInformationForm />
            </div>
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
            >
                <UpdatePasswordForm />
            </div>

            <Link
                as="button"
                method="post"
                class="text-xl hover:text-red-600 text-red-500 underline px-4 py-1"
                :href="route('logout')"
            >
                Logout</Link
            >
        </div>
    </div>
</template>
