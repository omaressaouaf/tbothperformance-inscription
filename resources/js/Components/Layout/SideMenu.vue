<template>
    <nav class="side-nav" :class="[sideMenuSimple ? 'side-nav--simple' : '']">
        <Link
            :href="route('admin.dashboard')"
            class="flex items-center pt-4 mt-3"
        >
            <img src="/images/icon-white.png" class="block xl:hidden h-16" />
            <img
                v-if="sideMenuSimple"
                src="/images/icon-white.png"
                class="hidden xl:block h-14"
            />
            <img v-else src="/images/logo-white.svg" class="hidden xl:block" />
        </Link>
        <MenuDivider />
        <ul
            class="pe-5 space-y-3 max-h-[90%] overflow-hidden hover:overflow-y-overlay has-cool-scrollbar"
        >
            <MenuItems />
        </ul>
    </nav>
</template>

<script>
import MenuItems from "@/Components/Layout/MenuItems.vue";
import { mapState } from "vuex";

export default {
    name: "SideMenu",
    components: {
        MenuItems,
    },
    computed: {
        ...mapState("ui", ["sideMenuSimple"]),
    },
    provide() {
        return {
            menuClassPrefix: "side-",
        };
    },
};
</script>
