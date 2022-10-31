<template>
    <div
        class="dropdown"
        :data-placement="`bottom-${_locale === 'ar' ? 'end' : 'start'}`"
        :ref="`dropdown-${componentId}`"
    >
        <div
            class="dropdown-toggle"
            @keydown.tab.exact="closeDropdown"
            :ref="`dropdown-toggle-${componentId}`"
        >
            <slot name="trigger" />
        </div>
        <slot name="dropdown-menu" />
        <div
            class="dropdown-menu w-72 fixed"
            :class="[width ? `md:w-${width}` : 'md:w-80']"
        >
            <div class="dropdown-menu__content box dark:bg-dark-6">
                <slot name="content" />
            </div>
        </div>
    </div>
</template>

<script>
import { onElementAttributeChange } from "@/helpers";
import cash from "cash-dom";

export default {
    props: {
        width: String,
    },
    data() {
        return {
            componentId: _.uniqueId(),
        };
    },
    methods: {
        registerTriggerAriaExpandedListener() {
            onElementAttributeChange(
                this.$refs[`dropdown-toggle-${this.componentId}`],
                "aria-expanded",
                (node) => {
                    if (node.getAttribute("aria-expanded") === "false") {
                        setTimeout(() => this.$emit("dropdownClosed"), 200); //wait for fade out animation
                    } else {
                        this.$emit("dropdownOpened");
                    }
                }
            );
        },
        closeDropdown() {
            cash(this.$refs[`dropdown-${this.componentId}`]).dropdown("hide");
        },
    },
    mounted() {
        this.registerTriggerAriaExpandedListener();
    },
    beforeUnmount() {
        this.closeDropdown();
    },
};
</script>
