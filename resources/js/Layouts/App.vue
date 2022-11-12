<template>
    <div
        class="min-h-screen flex flex-col items-center pt-10 bg-gray-100 dark:bg-dark-4"
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
            </div>
        </div>

        <div class="mx-5 container overflow-hidden mt-5">
            <slot />
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
