<template>
    <div
        class="min-h-screen flex flex-col items-center pt-10 bg-gray-100 dark:bg-dark-4 svg-layout"
    >
        <div
            class="flex flex-col md:flex-row w-full container justify-center items-center md:justify-between"
        >
            <div></div>
            <Link href="/">
                <img
                    :src="`/images/${darkMode ? 'logo-white' : 'logo'}.svg`"
                    class="block h-12 w-auto"
                    :alt="_appName"
                />
            </Link>
            <div class="flex items-center justify-center">
                <ThemeSwitcher />
                <LocaleSwitcher />
                <Dropdown v-if="$page.props.auth.lead">
                    <template #trigger>
                        <div
                            class="w-8 h-8 rounded-full overflow-hidden image-fit zoom-in"
                            role="button"
                            aria-expanded="false"
                        >
                            <svg
                                class="h-full w-full text-gray-500"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"
                                ></path>
                            </svg>
                        </div>
                    </template>
                    <template #content>
                        <div
                            class="p-4 border-b border-black border-opacity-5 dark:border-dark-3"
                        >
                            <div class="font-medium capitalize">
                                {{ $page.props.auth?.lead.first_name }}
                                {{ $page.props.auth?.lead.last_name }}
                            </div>
                        </div>
                        <div
                            class="p-2 border-t border-black border-opacity-5 dark:border-dark-3"
                        >
                            <DropdownLink
                                :href="route('lead.dashboard')"
                                icon="HomeIcon"
                                :title="__('Dashboard')"
                            />
                            <DropdownLink
                                :href="route('lead.logout')"
                                icon="PowerIcon"
                                :title="__('Log Out')"
                                method="post"
                                as="button"
                            />
                        </div>
                    </template>
                </Dropdown>
            </div>
        </div>

        <div class="mx-5 container overflow-hidden mt-5">
            <slot />
        </div>
        <FlashToasts />
        <ConfirmationModal />
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    computed: {
        ...mapState("ui", ["darkMode"]),
    },
};
</script>
