<template>
    <MobileMenu />
    <div class="flex overflow-hidden">
        <SideMenu />
        <div class="content">
            <div
                class="md:overflow-x-hidden md:overflow-y-overlay md:h-screen md:max-h-screen md:pb-10 md:px-6 has-cool-scrollbar-lg"
            >
                <FlashToasts />
                <ConfirmationModal />
                <TopBar />
                <slot />
                <div class="absolute bottom-2 right-0 px-7 font-semibold text-xs text-gray-700 dark:text-gray-400">
                    &#169; {{ __("All rights reserved.") }} {{ _appName }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MobileMenu from "@/Components/Layout/MobileMenu.vue";
import SideMenu from "@/Components/Layout/SideMenu.vue";
import TopBar from "@/Components/Layout/TopBar.vue";

export default {
    components: {
        MobileMenu,
        SideMenu,
        TopBar,
    },
    provide() {
        return {
            menuItems: [
                {
                    url: route("admin.dashboard"),
                    title: __("Dashboard"),
                    icon: "ActivityIcon",
                },
                "divider",
                {
                    url: route("admin.course-categories.index"),
                    title: __("Categories"),
                    icon: "GridIcon",
                },
            ],
        };
    },
};
</script>
