<script setup>
import FormInputText from "@/Components/FormInputText.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('applicant.profile.update'))"
            class="mt-6 space-y-6"
        >
            <div>
                <FormInputText
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    :error="form.errors.first_name"
                    autocomplete="first_name"
                    label="First Name"
                    required
                />
            </div>
            <div>
                <FormInputText
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    :error="form.errors.last_name"
                    autocomplete="last_name"
                    label="Last Name"
                    required
                />
            </div>

            <div>
                <FormInputText
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    :error="form.errors.email"
                    autocomplete="email"
                    label="Email"
                    required
                />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-from-class="opacity-0"
                    leave-to-class="opacity-0"
                    class="transition ease-in-out"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
