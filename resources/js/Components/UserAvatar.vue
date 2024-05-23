<script setup>
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import { computed } from "vue";

const props = defineProps({
    user: {
        required: true,
        type: Object,
    },
});

const initials = computed(() => {
    return props.user.first_name[0] + props.user.last_name[0];
});
</script>
<template>
    <Avatar>
        <AvatarImage :src="user.media ?? 'null'" />
        <AvatarFallback>{{ initials }}</AvatarFallback>
    </Avatar>
</template>
