<script setup>
const props = defineProps({
    src: {
        required: false,
    },
});

const generateImagePreview = () => {
    if (typeof props.src === "string") {
        return props.src;
    }
    return URL.createObjectURL(props.src);
};
</script>
<template>
    <div class="flex flex-wrap mb-4">
        <div class="w-6/12 flex-grow relative">
            <img v-bind="{ ...$attrs }" :src="generateImagePreview()" />
        </div>
    </div>
</template>
