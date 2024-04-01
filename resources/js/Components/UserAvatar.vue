<script setup>
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import { computed } from "vue";

const props = defineProps({
    user: {
        required: true,
        type: Object,
    },

    // temporary, use user name once you have applicant users
    name: {
        required: false,
    },
});

const initials = computed(() => {
    //use with user once applicant is setup
    // return props.user.first_name[0] + props.user.last_name[0];

    return props.name
        .match(/(^\S\S?|\b\S)?/g)
        .join("")
        .match(/(^\S|\S$)?/g)
        .join("")
        .toUpperCase();
});
</script>
<template>
    <Avatar>
        <AvatarImage :src="user.media" />
        <AvatarFallback>{{ initials }}</AvatarFallback>
    </Avatar>
</template>
