const state = {
    currentCourseCategory: null,
};

const mutations = {
    setCurrentCourseCategory(state, data) {
        state.currentCourseCategory = data;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
};
