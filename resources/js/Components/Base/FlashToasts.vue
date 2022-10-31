<template>
    <div class="text-center">
        <div
            v-for="(toast, index) in toasts"
            :key="index"
            :ref="toast.ref"
            class="toastify-content hidden"
        >
            <Component
                v-if="toast.icon"
                :is="toast.icon"
                :class="toast.iconColor || getIconColor(toast.icon)"
            />
            <div class="ml-4 mr-4">
                <div v-if="toast.title" class="font-medium">
                    {{ toast.title }}
                </div>
                <div v-if="toast.description" class="text-gray-600 mt-1">
                    {{ toast.description }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Toastify from "toastify-js";
import cash from 'cash-dom';

export default {
    data() {
        return {
            toasts: [],
            flashMessagesTypes: {
                successMessage: "CheckCircleIcon",
                infoMessage: "AlertCircleIcon",
                warningMessage: "AlertTriangleIcon",
                errorMessage: "XCircleIcon",
            },
        };
    },
    watch: {
        "$page.props.flash": {
            handler(flash) {
                if (flash) {
                    Object.keys(flash).forEach((key) => {
                        if (
                            flash[key] &&
                            Object.keys(this.flashMessagesTypes).includes(key)
                        ) {
                            const toastOptions = {
                                ref: this.generateToastRef(),
                                icon: this.flashMessagesTypes[key],
                                title: flash[key],
                            };

                            this.handleFireToast(toastOptions);
                        }
                    });
                }
            },
            deep: true,
            immediate: true,
        },
    },
    methods: {
        generateToastRef() {
            return Date.now() + _.uniqueId();
        },
        getIconColor(icon) {
            switch (icon) {
                case "CheckCircleIcon":
                    return "text-theme-20";
                case "AlertCircleIcon":
                    return "text-primary-11";
                case "XCircleIcon":
                    return "text-theme-21";
                case "AlertTriangleIcon":
                    return "text-theme-15";
                default:
                    return "";
            }
        },
        handleFireToast({
            icon = "AlertCircleIcon",
            iconColor,
            title,
            description,
            duration = 3000,
            close = true,
            gravity = "top",
            position = "right",
            destination = undefined,
        } = {}) {
            const toastRef = this.generateToastRef();

            this.toasts.push({
                ref: toastRef,
                icon,
                iconColor,
                title,
                description,
            });

            this.$nextTick(() => {
                Toastify({
                    node: cash(this.$refs[toastRef])
                        .clone()
                        .removeClass("hidden")
                        .addClass("flex")[0],
                    duration,
                    newWindow: true,
                    stopOnFocus: true,
                    close,
                    gravity,
                    position,
                    destination,
                }).showToast();

                this.toasts.pop();
            });
        },
    },
    mounted() {
        this.$emitter.on("fire-toast", this.handleFireToast);
    },
    beforeUnmount() {
        this.$emitter.off("fire-toast");
    },
};
</script>
