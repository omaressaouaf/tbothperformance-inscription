import { createStore } from "vuex";
import ui from "@/store/modules/ui";

const store = createStore({
    modules: {
        ui,
    },
});

export default store;
