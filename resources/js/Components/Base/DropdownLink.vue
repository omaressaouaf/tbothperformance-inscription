<template>
    <Component
        :is="anchor ? 'a' : 'Link'"
        data-dismiss="dropdown"
        class="dropdown-menu-link"
        :class="{
            'bg-gray-200 dark:bg-dark-3': active,
        }"
    >
        <Component
            v-if="icon"
            :is="icon"
            class="w-4 h-4"
            :class="{ 'me-2': title }"
        />
        <template v-if="title">
            {{ title }}
        </template>
        <slot v-else />
    </Component>
</template>

<script>
export default {
    props: {
        icon: String,
        title: String,
        icon: String,
        anchor: Boolean,
        active: Boolean,
    },
};
</script>
