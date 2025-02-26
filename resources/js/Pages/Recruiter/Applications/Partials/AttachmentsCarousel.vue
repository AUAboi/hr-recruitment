<script setup>
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from "@/Components/ui/carousel";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import { urlIsImage } from "@/utils";

const props = defineProps(["attachments"]);
</script>
<template>
    <Carousel>
        <CarouselContent>
            <template v-for="attachment in attachments">
                <CarouselItem v-if="urlIsImage(attachment)">
                    <img
                        class="w-full object-cover object-center"
                        :src="attachment"
                        alt=""
                    />
                </CarouselItem>
                <CarouselItem v-else>
                    <div
                        class="bg-gray-700 flex items-center justify-center w-full h-full"
                    >
                        <div class="flex flex-col">
                            <PrimaryButton class="w-fit mx-auto my-6">
                                <a download :href="attachment">
                                    Download file
                                </a>
                            </PrimaryButton>
                            <p class="text-white">
                                {{ attachment.split("/").pop() }}
                            </p>
                        </div>
                    </div>
                </CarouselItem>
            </template>
        </CarouselContent>
        <CarouselPrevious />
        <CarouselNext />
    </Carousel>
</template>
