import cash from "cash-dom";

const state = {
    sideMenuSimple: false,
    darkMode: false,
    loadings: {},
    serverErrors: {},
};

const actions = {
    toggleDarkMode({ commit, state }) {
        commit("setDarkMode", !state.darkMode);
        cash("html")[state.darkMode ? "addClass" : "removeClass"]("dark");
        try {
            localStorage.setItem("darkMode", state.darkMode);
        } catch {}
    },
    setDarkModeFromLocalStorage({ commit }) {
        try {
            const val = localStorage.getItem("darkMode");
            commit("setDarkMode", val === null ? false : JSON.parse(val));
            cash("html")[state.darkMode ? "addClass" : "removeClass"]("dark");
        } catch {}
    },
};

const mutations = {
    toggleSideMenuSimple(state) {
        state.sideMenuSimple = !state.sideMenuSimple;
    },
    setDarkMode(state, value) {
        state.darkMode = value;
    },
    setLoading(state, component) {
        state.loadings[component] = true;
    },
    clearLoading(state, component) {
        delete state.loadings[component];
    },
    setServerErrors(state, { component, err }) {
        if (typeof err === "string") {
            state.serverErrors[component] = [__(err)];
            return;
        }

        if (err.response?.data?.errors) {
            state.serverErrors[component] = err.response.data.errors;
        } else {
            state.serverErrors[component] = [
                err.response?.data?.error || __("Unexpected error happened"),
            ];
        }
    },
    clearServerErrors(state, component) {
        delete state.serverErrors[component];
    },
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
};
