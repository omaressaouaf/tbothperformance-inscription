import "../sass/app.scss";
import "./bootstrap";
import "nprogress/nprogress.css";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import AuthLayout from "@/Layouts/Auth.vue";
import AdminLayout from "@/Layouts/Admin.vue";
import registerBaseComponents from "@/Components/Base";
import bladeToJs from "./mixins/bladeToJs";
import translate from "@/mixins/translate";
import printPlaceholder from "@/mixins/printPlaceholder";
import VueClickAway from "vue3-click-away";
import store from "@/store";
import mitt from "mitt";
import {
    formatDate,
    formatDateTime,
    formatDateForInput,
    formatMoney,
} from "@/helpers";

// Setup Inertia js app with Vue app
createInertiaApp({
    title: (title) => `${title} - ${_appName}`,
    resolve: async (name) => {
        const page = (
            await resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob("./Pages/**/*.vue")
            )
        ).default;

        if (!page.layout) {
            if (name.startsWith("Auth/")) {
                page.layout = AuthLayout;
            }

            if (name.startsWith("Admin/")) {
                page.layout = AdminLayout;
            }
        }

        return page;
    },
    setup({ el, app, props, plugin }) {
        const vueApp = createApp({
            render: () => h(app, props),
        });

        // Global config
        vueApp.use(plugin);
        vueApp.use(store);
        vueApp.use(VueClickAway);
        vueApp.use(ZiggyVue, Ziggy);
        window.emitter = mitt();
        vueApp.config.globalProperties.$emitter = emitter;

        // Mixins
        vueApp.mixin({ methods: { route } });
        vueApp.mixin(bladeToJs);
        vueApp.mixin(translate);
        vueApp.mixin(printPlaceholder);

        // Translation and localization
        window.__ = translate.methods.__;
        window.__choice = translate.methods.__choice;

        // Base and Global components
        registerBaseComponents(vueApp);

        // Global filters
        vueApp.config.globalProperties.$filters = {
            formatDate,
            formatDateTime,
            formatDateForInput,
            formatMoney,
        };

        vueApp.mount(el);

        return vueApp;
    },
});
