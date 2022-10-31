<template>
    <button
        @click="toggleDarkMode"
        class="notification me-5 flex items-end"
        role="button"
        aria-expanded="false"
    >
        <Component
            :is="darkMode ? 'MoonIcon' : 'SunIcon'"
            class="notification__icon dark:text-gray-300"
        />
    </button>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
    methods: {
        ...mapActions("ui", ["toggleDarkMode", "setDarkModeFromLocalStorage"]),
    },
    computed: {
        ...mapState("ui", ["darkMode"]),
    },
    created() {
        this.setDarkModeFromLocalStorage();
    },
};
</script>
