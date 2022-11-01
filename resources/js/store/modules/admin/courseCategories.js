const state = {
    currentCourseCategory: null,
    courseCategories: [],
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
