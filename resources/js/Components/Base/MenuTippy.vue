<template>
    <Tippy
        :options="{
            placement: 'left',
        }"
        ref-key="menuTooltipRef"
    >
        <slot></slot>
    </Tippy>
</template>

<script>
import { defineComponent, provide, ref, onMounted, computed, watch } from "vue";
import { useStore } from "vuex";
import cash from 'cash-dom';

export default defineComponent({
    setup() {
        const tippyRef = ref();
        const store = useStore();
        const sideMenuSimple = computed(() => store.state.ui.sideMenuSimple);

        provide("bind[menuTooltipRef]", (el) => {
            tippyRef.value = el;
        });

        const toggleTooltip = () => {
            const el = tippyRef.value;
            const windowWidth = cash(window).width();

            if (
                (windowWidth >= 600 && windowWidth <= 1260) ||
                sideMenuSimple.value
            ) {
                el._tippy.enable();
            } else {
                el._tippy.disable();
            }
        };

        const initTooltipEvent = () => {
            window.addEventListener("resize", () => {
                toggleTooltip();
            });
        };

        watch(sideMenuSimple, toggleTooltip);

        onMounted(() => {
            toggleTooltip();
            initTooltipEvent();
        });
    },
});
</script>
