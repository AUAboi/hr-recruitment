<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {
    ContextMenu,
    ContextMenuContent,
    ContextMenuItem,
    ContextMenuTrigger,
} from "@/components/ui/context-menu";
import UserAvatar from "@/Components/UserAvatar.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="page-container bg-black">
        <header>
            <div class="p-4 flex items-center justify-between">
                <Link class="block w-fit" href="/">
                    <ApplicationLogo
                        class="w-20 h-20 fill-current text-gray-500"
                    />
                </Link>
                <div class="flex items-center" v-if="$page.props.auth.user">
                    <ContextMenu>
                        <ContextMenuTrigger
                            ><Link
                                :href="
                                    route(
                                        `${$page.props.auth.user.roles[0].name}.profile.edit`
                                    )
                                "
                            >
                                <UserAvatar
                                    :user="$page.props.auth.user"
                                /> </Link
                        ></ContextMenuTrigger>
                        <ContextMenuContent>
                            <ContextMenuItem>
                                <Link
                                    as="button"
                                    method="post"
                                    :href="route('logout')"
                                >
                                    Logout</Link
                                >
                            </ContextMenuItem>
                        </ContextMenuContent>
                    </ContextMenu>
                </div>
            </div>
        </header>

        <main class="w-full">
            <FlashMessage
                v-if="
                    $page.props.flash.success ||
                    $page.props.flash.error ||
                    Object.keys($page.props.errors).length > 0
                "
            />
            <slot />
        </main>
    </div>
</template>
<style>
.page-container {
    min-height: 100vh;
}
</style>
