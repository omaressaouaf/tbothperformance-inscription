<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-dark-4 svg-layout"
    >
        <div>
            <Link href="/">
                <img
                    :src="`/images/${darkMode ? 'logo-white' : 'logo'}.svg`"
                    class="block h-12 w-auto"
                    :alt="_appName"
                />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-dark-1 shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>

        <div class="mt-10 flex items-center justify-center">
            <LocaleSwitcher />
            <ThemeSwitcher />
        </div>
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
