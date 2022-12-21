const state = {
    notifications: [],
    notificationsNextPage: 1,
    notificationsDataComplete: false,
};

const actions = {
    async fetchNotifications({ commit, state }) {
        const component = "NotificationsDropdown";
        try {
            commit("ui/setLoading", component, { root: true });

            const res = await axios.get(route("admin.notifications.index"), {
                params: {
                    page: state.notificationsNextPage,
                },
            });

            commit("setNotifications", res.data.notifications);
        } catch {
        } finally {
            commit("ui/clearLoading", component, { root: true });
        }
    },
    async markNotificationAsRead({ commit }, notification) {
        try {
            await axios.put(
                route("admin.notifications.mark-as-read", [notification.id])
            );

            commit("markNotificationAsRead", notification);
        } catch {}
    },
    async markAllNotificationsAsRead({ commit }) {
        try {
            await axios.put(route("admin.notifications.mark-all-as-read"));

            commit("markAllNotificationsAsRead");
        } catch {}
    },
};

const mutations = {
    setNotifications(state, payload) {
        if (payload.current_page == 1) {
            this.commit("admin/notifications/resetNotificationsNextPage");
            state.notifications = payload.data;
        } else {
            state.notifications = [...state.notifications, ...payload.data];
        }

        state.notificationsNextPage += 1;
        state.notificationsDataComplete = payload.next_page_url ? false : true;
    },
    resetNotificationsNextPage(state) {
        state.notificationsNextPage = 1;
    },
    resetNotificationsPagination() {
        this.commit("admin/notifications/resetNotificationsNextPage");
    },
    markNotificationAsRead(state, notification) {
        state.notifications = state.notifications.map((notif) =>
            notif.id === notification.id
                ? { ...notif, read_at: new Date() }
                : { ...notif }
        );
    },
    markAllNotificationsAsRead(state) {
        state.notifications = state.notifications.map((notif) => ({
            ...notif,
            read_at: new Date(),
        }));
    },
    addNotification(state, notification) {
        state.notifications = [notification, ...state.notifications];
    },
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
};
