<template>
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <Link :href="route('admin.dashboard')" class="flex me-auto">
                <img src="/images/logo.svg" class="h-12 flex me-auto" />
            </Link>
            <a href="javascript:;" id="mobile-menu-toggler">
                <BarChart2Icon
                    @click="showMobileMenu = !showMobileMenu"
                    class="w-8 h-8 text-gray-600 dark:text-white transform -rotate-90"
                />
            </a>
        </div>
        <SlideUpDown>
            <ul
                v-if="showMobileMenu"
                class="mobile-menu-box py-5"
                :class="{ hidden: !showMobileMenu }"
            >
                <MenuItems />
            </ul>
        </SlideUpDown>
    </div>
</template>

<script>
import MenuItems from "@/Components/Layout/MenuItems.vue";

export default {
    name: "MobileMenu",
    components: {
        MenuItems,
    },
    props: {
        menuItems: Array,
    },
    data() {
        return {
            showMobileMenu: false,
        };
    },
    provide() {
        return {
            menuClassPrefix: "",
        };
    },
};
</script>
