import axios from "axios";
import * as _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import NProgress from "nprogress";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import LocalizedFormat from "dayjs/plugin/localizedFormat";
import objectSupport from "dayjs/plugin/objectSupport";
import "dayjs/locale/fr";

// Lodash
window._ = _;

// Axios config
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Inertia events
let timeout = null;
NProgress.configure({
    trickleRate: 0.05,
    trickleSpeed: 20,
    showSpinner: false,
});

Inertia.on("start", () => {
    timeout = setTimeout(() => NProgress.start(), 250);
});

Inertia.on("finish", (event) => {
    clearTimeout(timeout);
    if (!NProgress.isStarted()) {
        return;
    } else if (event.detail.visit.completed) {
        NProgress.done();
    } else if (event.detail.visit.interrupted) {
        NProgress.set(0);
    } else if (event.detail.visit.cancelled) {
        NProgress.done();
        NProgress.remove();
    }
});

Inertia.on("progress", (event) => {
    if (NProgress.isStarted() && event.detail.progress.percentage) {
        NProgress.set((event.detail.progress.percentage / 100) * 0.9);
    }
});

// Days js
dayjs.extend(objectSupport);
dayjs.extend(relativeTime);
dayjs.extend(LocalizedFormat);
dayjs.locale(window._locale);

//Pusher & Echo config
// window.Pusher = Pusher;
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
