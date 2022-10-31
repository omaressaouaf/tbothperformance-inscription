<template>
    <transition @enter="slideDown" @leave="slideUp">
        <slot />
    </transition>
</template>

<script>
import velocityAnimation from "@/mixins/velocityAnimation";

export default {
    mixins: [velocityAnimation],
};
</script>
