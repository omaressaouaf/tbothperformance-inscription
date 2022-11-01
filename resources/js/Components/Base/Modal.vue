<template>
    <div
        :id="id"
        class="modal overflow-y-overlay overflow-x-hidden modal-root"
        :class="{ 'modal-slide-over': slideOver }"
        tabindex="-1"
        aria-hidden="true"
        v-bind="staticBackdrop ? { 'data-backdrop': 'static' } : {}"
    >
        <div class="modal-dialog" :class="[size]">
            <div class="modal-content">
                <a
                    v-if="showCloseButton"
                    data-dismiss="modal"
                    href="javascript:;"
                >
                    <XIcon class="w-8 h-8 text-gray-500" />
                </a>
                <div v-if="title" class="modal-header">
                    <h2 class="font-medium text-base mr-auto">
                        {{ title }}
                    </h2>
                </div>
                <div class="modal-body" :class="{ 'p-10': bodyPadding }">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onElementAttributeChange } from "@/helpers";

export default {
    props: {
        title: String,
        id: String,
        size: {
            type: String,
            default: "modal-xl",
        },
        slideOver: Boolean,
        showCloseButton: {
            type: Boolean,
            default: true,
        },
        bodyPadding: {
            type: Boolean,
            default: true,
        },
        staticBackdrop: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["modalOpened", "modalClosed"],
    methods: {
        registerModalAriaHiddenListener() {
            onElementAttributeChange(
                document.getElementById(this.id),
                "aria-hidden",
                (node) => {
                    if (node.getAttribute("aria-hidden") === "true") {
                        setTimeout(() => this.$emit("modalClosed"), 200); //wait for fade out animation
                    } else {
                        this.$emit("modalOpened");
                    }
                }
            );
        },
    },
    mounted() {
        this.registerModalAriaHiddenListener();
    },
};
</script>
