<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import FormInputText from "@/Components/FormInputText.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    username: "",
    email: "",
    first_name: "",
    last_name: "",
    password: "",
    password_confirmation: "",
    role: "recruiter",
});

const submit = () => {
    form.post(route("recruiter.users.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>
<template>
    <Head title="Create Job" />
    <h2 class="font-semibold text-xl text-white leading- pb-6">
        Create Recruiter <br />
    </h2>
    <div
        class="p-4 sm:p-8 max-w-7xl bg-primaryGray shadow sm:rounded-lg text-white"
    >
        <form class="text-white" @submit.prevent="submit">
            <div class="flex flex-col sm:flex-row sm:gap-10">
                <FormInputText
                    label="First Name"
                    v-model="form.first_name"
                    :error="form.errors.first_name"
                />
                <FormInputText
                    label="Last Name"
                    v-model="form.last_name"
                    :error="form.errors.last_name"
                />
            </div>
            <div class="sm:mt-4 flex flex-col sm:flex-row sm:gap-10">
                <FormInputText
                    label="Username"
                    v-model="form.username"
                    :error="form.errors.username"
                />
                <FormInputText
                    label="Email"
                    v-model="form.email"
                    :error="form.errors.email"
                />
            </div>
            <div class="sm:mt-4 flex flex-col sm:flex-row sm:gap-10">
                <FormInputText
                    label="Password"
                    v-model="form.password"
                    :error="form.errors.password"
                />
                <FormInputText
                    label="Confirm Password"
                    v-model="form.password_confirmation"
                    :error="form.errors.password_confirmation"
                />
            </div>

            <input v-model="form.role" required hidden />

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Create
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
