<template>
    <div class="top-bar -mx-4 px-4 md:mx-0 md:px-0">
        <div class="-intro-x breadcrumb font-semibold me-auto hidden sm:flex">
            <a class="hidden xl:block" href="javascript:;">
                <MenuIcon
                    @click="$store.commit('ui/toggleSideMenuSimple')"
                    class="text-gray-600 dark:text-white transform me-5"
                />
            </a>
        </div>
        <LocaleSwitcher />
        <ThemeSwitcher />
        <NotificationsDropdown />
        <Dropdown>
            <template #trigger>
                <div
                    class="w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in"
                >
                    <img
                        :alt="$page.props.auth?.user.name"
                        src="/images/avatar.png"
                    />
                </div>
            </template>
            <template #content>
                <div
                    class="p-4 border-b border-black border-opacity-5 dark:border-dark-3"
                >
                    <div class="font-medium">
                        {{ $page.props.auth?.user.name }}
                    </div>
                </div>
                <div
                    class="p-2 border-t border-black border-opacity-5 dark:border-dark-3"
                >
                    <DropdownLink
                        :href="route('logout')"
                        icon="PowerIcon"
                        :title="__('Log Out')"
                        method="post"
                        as="button"
                    />
                </div>
            </template>
        </Dropdown>
    </div>
</template>

<script>
import NotificationsDropdown from "@/Components/Admin/Notifications/Dropdown.vue";

export default {
    components: {
        NotificationsDropdown,
    },
};
</script>
