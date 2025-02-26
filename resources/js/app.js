import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import axios from "axios";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

import RecruiterLayout from "@/Layouts/RecruiterLayout.vue";
import PublicLayout from "@/Layouts/PublicLayout.vue";

axios.defaults.baseURL = "https://job-nexus.mundanedev.com" + "/api";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        if (page.default.layout === undefined) {
            if (name.startsWith("Recruiter")) {
                page.default.layout = RecruiterLayout;
            } else if (name.startsWith("Public")) {
                page.default.layout = PublicLayout;
            }
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: "#ff8d4e",
    },
});
