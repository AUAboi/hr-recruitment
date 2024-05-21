<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div
    :class="
      cn('animate-pulse rounded-md bg-stone-100 dark:bg-stone-800', props.class)
    "
  />
</template>
