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
        <Dropdown>
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
export default {};
</script>
