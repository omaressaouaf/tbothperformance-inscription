import { createStore } from "vuex";
import ui from "@/store/modules/ui";
import admin from "@/store/modules/admin";

const store = createStore({
    modules: {
        ui,
        admin,
    },
});

export default store;
