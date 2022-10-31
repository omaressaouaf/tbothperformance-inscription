<template>
    <li>
        <Component
            :is="anchor ? 'a' : 'Link'"
            v-bind="{ ...restAttrs }"
            :href="href"
            :class="[
                active ? `${menuClassPrefix}menu--active` : '',
                `${menuClassPrefix}menu`,
            ]"
        >
            <MenuTippy :content="title" class="flex">
                <div :class="`${menuClassPrefix}menu__icon`">
                    <Component :is="icon" class="w-6 h-6" />
                </div>
                <div :class="`${menuClassPrefix}menu__title`">{{ title }}</div>
            </MenuTippy>
        </Component>
    </li>
</template>

<script>
export default {
    props: {
        anchor: Boolean,
        restAttrs: Object,
        href: String,
        icon: String,
        title: String,
        active: Boolean,
    },
    inject: ["menuClassPrefix"],
};
</script>
