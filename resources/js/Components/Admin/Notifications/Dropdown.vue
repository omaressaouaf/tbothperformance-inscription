<template>
    <div class="me-6">
        <Dropdown id="notifications-dropdown">
            <template #trigger>
                <button class="notification relative cursor-pointer">
                    <div
                        v-if="unreadNotificationsLength"
                        class="inline-flex absolute -top-2 -right-2 justify-center items-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full border-2 border-red-500 dark:border-gray-900"
                    >
                        {{ unreadNotificationsLength }}
                    </div>
                    <BellIcon
                        class="w-7 h-7 notification__icon dark:text-gray-300"
                    />
                </button>
            </template>
            <template #dropdown-menu>
                <div
                    class="notification-content pt-2 dropdown-menu md:!w-[450px]"
                    data-placement="top-start"
                >
                    <div
                        class="notification-content__box dropdown-menu__content box dark:bg-dark-6"
                    >
                        <div class="flex justify-between p-0 m-0">
                            <div class="notification-content__title !mb-0">
                                {{ __("Notifications") }}
                            </div>
                            <button
                                v-if="filteredNotifications.length"
                                @click="handleMarkAllAsRead"
                                class="text-primary-11 text-xs hover:underline font-semibold"
                            >
                                {{ __("Mark all as read") }}
                            </button>
                        </div>
                        <div
                            v-if="filteredNotifications.length"
                            class="max-h-[75vh] overflow-y-auto has-cool-scrollbar-sm"
                        >
                            <div
                                v-for="(
                                    notification, index
                                ) in filteredNotifications"
                                :key="index"
                                data-dismiss="dropdown"
                                class="cursor-pointer dropdown-menu-link relative flex items-center mt-5 !py-3"
                                :class="{
                                    'bg-gray-100 dark:bg-dark-1':
                                        !notification.read_at,
                                }"
                                @click="handleClickNotification(notification)"
                            >
                                <div
                                    class="w-12 h-12 flex items-center justify-center rounded-full px-3.5"
                                    :class="[
                                        notification.presentation
                                            .icon_background,
                                    ]"
                                >
                                    <Component
                                        :is="notification.presentation.icon"
                                        class="text-white"
                                    />
                                </div>
                                <div class="ms-2 overflow-hidden">
                                    <div
                                        class="flex items-center justify-between mb-0.5"
                                    >
                                        <div
                                            class="text-xs font-semibold text-gray-500 whitespace-nowrap first-letter:uppercase"
                                        >
                                            {{
                                                $filters.formatDateTime(
                                                    notification.created_at
                                                )
                                            }}
                                        </div>
                                        <div
                                            v-if="!notification.read_at"
                                            class="p-1.5 rounded-full bg-primary-11"
                                        ></div>
                                    </div>
                                    <div
                                        class="w-full font-semibold text-gray-700 dark:text-gray-200 first-letter:uppercase"
                                    >
                                        {{ notification.presentation.message }}
                                    </div>
                                </div>
                            </div>
                            <VueEternalLoading :load="handleEternalLoading">
                                <template #loading>
                                    <div class="flex justify-center">
                                        <div class="w-1/12">
                                            <LoadingIcon
                                                icon="three-dots"
                                                color="gray"
                                            />
                                        </div>
                                    </div>
                                </template>
                                <template #no-more><span></span></template>
                            </VueEternalLoading>
                        </div>
                        <div
                            v-else
                            class="w-56 py-4 flex flex-col items-center justify-center mx-auto"
                        >
                            <div class="w-1/4">
                                <img
                                    src="/images/notification.svg"
                                    :alt="__('Notifications')"
                                />
                            </div>
                            <div class="flex flex-col items-center ms-5">
                                <h3
                                    class="font-semibold text-lg mt-4 whitespace-nowrap"
                                >
                                    {{ __("Notifications center") }}
                                </h3>
                                <p
                                    class="text-xs md:whitespace-nowrap mt-2 font-semibold text-gray-700 dark:text-gray-400"
                                >
                                    {{
                                        __(
                                            "Manage your notifications and reminders"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Dropdown>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { VueEternalLoading } from "@ts-pro/vue-eternal-loading";
import cash from "cash-dom";

export default {
    components: {
        VueEternalLoading,
    },
    data() {
        return {};
    },
    computed: {
        filteredNotifications() {
            return _.uniqBy(this.notifications, "id");
        },
        unreadNotificationsLength() {
            return this.filteredNotifications.filter((notification) => {
                return notification.read_at == null;
            }).length;
        },
        ...mapState("admin/notifications", [
            "notifications",
            "notificationsDataComplete",
        ]),
    },
    methods: {
        async handleEternalLoading({ loaded, noMore }) {
            await this.fetchNotifications();

            if (this.notificationsDataComplete) {
                noMore();
            } else {
                loaded();
            }
        },
        handleClickNotification(notification) {
            cash("#notifications-dropdown").dropdown("hide");

            this.$inertia.get(notification.presentation.url);

            this.markNotificationAsRead(notification);
        },
        handleMarkAllAsRead() {
            this.markAllNotificationsAsRead();
        },
        async notificationsRealtimeHandler(notification) {
            this.$store.commit("admin/notifications/addNotification", {
                ...notification,
                read_at: null,
            });

            new Audio("/notification.mp3").play();

            if ("Notification" in window) {
                if ((await Notification.requestPermission()) === "granted") {
                    new Notification(this._appName, {
                        body: notification.presentation.message,
                        silent: true,
                    });
                }
            }
        },
        registerNotificationsRealtimeListener() {
            Echo.private(
                `App.Models.User.${this.$page.props.auth.user.id}`
            ).notification(this.notificationsRealtimeHandler);
        },
        ...mapActions("admin/notifications", [
            "fetchNotifications",
            "markNotificationAsRead",
            "markAllNotificationsAsRead",
        ]),
    },
    mounted() {
        this.fetchNotifications();

        this.registerNotificationsRealtimeListener();
    },
    beforeUnmount() {
        this.$store.commit("admin/notifications/resetNotificationsPagination");
    },
};
</script>
