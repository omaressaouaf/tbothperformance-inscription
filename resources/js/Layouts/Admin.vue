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
                <!-- <div
                    class="sticky bottom-0 end-0 px-7 font-semibold text-xs text-gray-700 dark:text-gray-400"
                >
                    &#169; {{ __("All rights reserved.") }} {{ _appName }}
                </div> -->
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
                    url: route("admin.plans.index"),
                    title: __("Plans"),
                    icon: "ListIcon",
                },
                {
                    url: route("admin.course-categories.index"),
                    title: __("Categories"),
                    icon: "GridIcon",
                },
                {
                    url: route("admin.courses.index"),
                    title: __("Courses"),
                    icon: "BookIcon",
                },
                "divider",
                {
                    url: route("admin.leads.index"),
                    title: __("Leads"),
                    icon: "UsersIcon",
                },
                {
                    url: route("admin.enrollments.index"),
                    title: __("Enrollments"),
                    icon: "BookmarkIcon",
                },
            ],
        };
    },
};
</script>
