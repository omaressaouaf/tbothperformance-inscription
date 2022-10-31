<template>
    <div class="floating-scroll group">
        <div
            ref="scrollArea"
            :class="`scroll-area has-cool-scrollbar-lg md:no-scrollbar ${scrollClasses} ${$attrs.class}`"
        >
            <slot />
        </div>
        <div
            ref="scrollContainer"
            :class="`scroll-container has-cool-scrollbar-lg hidden md:block sticky -bottom-10 ${scrollClasses}`"
        >
            <div ref="scrollBar" :style="scrollBarStyle">&nbsp;</div>
        </div>
    </div>
</template>
<script>
import { Inertia } from "@inertiajs/inertia";

export default {
    name: "floatingScroll",
    props: {
        onHover: Boolean,
    },
    data() {
        return {
            scrollbarWidth: "",
            scrollClasses: this.onHover
                ? "overflow-x-overlay xl:overflow-x-hidden xl:group-hover:overflow-x-overlay"
                : "overflow-x-overlay",
        };
    },
    computed: {
        scrollBarStyle() {
            return { paddingTop: "1px", width: this.scrollbarWidth };
        },
    },
    methods: {
        initScrollbar() {
            const scrollContainer = this.$refs.scrollContainer;
            const scrollArea = this.$refs.scrollArea;
            const setScrollAreaScrollLeft = () => {
                scrollArea.scrollLeft = scrollContainer.scrollLeft;
            };
            const setScrollContainerScrollLeft = () => {
                scrollContainer.scrollLeft = scrollArea.scrollLeft;
            };

            this.setScrollbarWidth();

            scrollContainer.addEventListener("scroll", setScrollAreaScrollLeft);
            scrollArea.addEventListener("scroll", setScrollContainerScrollLeft);
            window.addEventListener("resize", this.setScrollbarWidth);

            return () => {
                scrollContainer.removeEventListener(
                    "scroll",
                    setScrollAreaScrollLeft
                );
                scrollArea.removeEventListener(
                    "scroll",
                    setScrollContainerScrollLeft
                );
                window.removeEventListener("resize", this.setScrollbarWidth);
            };
        },
        setScrollbarWidth() {
            const scrollAreaScrollWidth =
                this.$refs.scrollArea && this.$refs.scrollArea.scrollWidth
                    ? this.$refs.scrollArea.scrollWidth + "px"
                    : "auto";

            if (scrollAreaScrollWidth !== this.scrollbarWidth) {
                this.scrollbarWidth = scrollAreaScrollWidth;
            }
        },
        registerSlotResizeListener() {
            const resizeObserver = new ResizeObserver(() => {
                this.destroyScrollbar();
                this.initScrollbar();
            });

            for (const element of this.$refs.scrollArea.children) {
                resizeObserver.observe(element);
            }

            this.removeOnSlotResizeListener = () => resizeObserver.disconnect();
        },
        registerInertiaStartListener() {
            this.removeInertiaStartEventListener = Inertia.on(
                "start",
                this.destroyScrollbar
            );
        },
        registerInertiaFinishListener() {
            this.removeInertiaFinishEventListener = Inertia.on(
                "finish",
                this.initScrollbar
            );
        },
    },
    mounted() {
        this.destroyScrollbar = this.initScrollbar();

        this.registerSlotResizeListener();
        // this.registerInertiaStartListener();
        // this.registerInertiaFinishListener();
    },
    beforeUnmount() {
        this.removeOnSlotResizeListener();
        // this.removeInertiaStartEventListener();
        // this.removeInertiaFinishEventListener();
    },
};
</script>
