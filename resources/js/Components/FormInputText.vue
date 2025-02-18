<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "text",
    },
    error: String,
    label: String,
    modelValue: {
        type: [String, Number],
        default: "",
    },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});
</script>
<template>
    <div class="pr-6 pb-8 w-full" :class="$attrs.class">
        <label v-if="label" class="form-label mb-2 block text-white"
            >{{ label }}:</label
        >
        <input
            v-bind="{ ...$attrs, class: null }"
            class="leading-normal block w-full border-gray-300 focus:border-orange-500 p-2 focus:ring-orange-500 rounded-md shadow-sm bg-neutral-700 border-0 text-white"
            :class="{ 'focus:ring focus:ring-red-200': error }"
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            ref="input"
        />
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}

.form-input.error {
    @apply border-red-500;
}

.form-error {
    @apply text-red-700 mt-2 text-sm;
}
</style>
