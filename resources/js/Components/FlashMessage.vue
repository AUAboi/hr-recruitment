<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import CloseCircleOutline from "~icons/mdi/close-circle-outline";

const show = ref(false);

watch(
    () => usePage().props.flash,
    () => {
        show.value = true;
        setTimeout(() => {
            show.value = false;
        }, 4000);
    },
    { deep: true }
);
</script>

<template>
    <Transition
        enter-from-class="translate-x-60"
        enter-to-class="translate-x-0"
        leave-to-class="translate-x-60"
        style="z-index: 100"
        class="text-white fixed top-10 right-0 mx-2 transition-all"
    >
        <div v-if="show">
            <div
                v-if="$page.props.flash.success"
                class="mb-8 flex items-center justify-between bg-green-500 rounded max-w-3xl"
            >
                <div class="flex items-center">
                    <i class="fas fa-check mx-2"></i>
                    <div class="py-4 text-sm font-medium">
                        {{ $page.props.flash.success }}
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2 hover:text-black text-white"
                    @click="show = false"
                >
                    <CloseCircleOutline />
                </button>
            </div>
            <div
                v-if="
                    $page.props.flash.error ||
                    Object.keys($page.props.errors).length > 0
                "
                class="mb-8 flex items-center justify-between bg-red-400 rounded max-w-3xl"
            >
                <div class="flex items-center">
                    <i class="far fa-times-circle mx-2"></i>
                    <div
                        v-if="$page.props.flash.error"
                        class="py-4 text-white text-sm font-medium"
                    >
                        {{ $page.props.flash.error }}
                    </div>
                    <div v-else class="py-4 text-white text-sm font-medium">
                        <span
                            v-if="Object.keys($page.props.errors).length === 1"
                            >There is one form error.</span
                        >
                        <span v-else
                            >There are
                            {{ Object.keys($page.props.errors).length }} form
                            errors.</span
                        >
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2 text-black hover:text-white"
                    @click="show = false"
                >
                    <CloseCircleOutline />
                </button>
            </div>
        </div>
    </Transition>
</template>
