const state = {
    currentPlan: null,
};

const mutations = {
    setCurrentPlan(state, data) {
        state.currentPlan = data;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
};
