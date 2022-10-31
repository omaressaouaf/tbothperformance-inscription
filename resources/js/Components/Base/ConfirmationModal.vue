<template>
    <Modal
        id="delete-confirmation-modal"
        size="modal-md"
        :show-close-button="false"
        :body-padding="false"
        :static-backdrop="false"
        @modalClosed="handleModalClosed"
    >
        <div class="p-5 text-center">
            <Component
                :is="options.icon"
                class="w-16 h-16 text-theme-21 mx-auto mt-3"
            />
            <div class="text-3xl mt-5">
                {{ options.title }}
            </div>
            <div class="text-gray-600 mt-2">
                {{ options.description }}
            </div>
            <div
                v-if="options.textToEnter"
                class="text-gray-700 dark:text-gray-200 font-semibold mt-4 mb-1"
            >
                <label class="form-label mb-3">
                    {{ __("Please enter") }}
                    <span class="font-extrabold">{{
                        options.textToEnter
                    }}</span>
                    {{ __("to confirm") }}
                </label>
                <input v-model="enteredText" class="form-control" />
            </div>
        </div>
        <div class="pb-8 text-center">
            <button
                type="button"
                id="delete-confirmation-button"
                class="btn btn-danger"
                @click="handleDelete"
                :disabled="options.textToEnter && !this.textIsConfirmed"
            >
                <span v-if="options.textToEnter" class="me-2">
                    {{ __("I understand") }}.
                </span>
                {{ __("Remove") }}
            </button>
            <button
                type="button"
                data-dismiss="modal"
                class="btn btn-outline-secondary w-24 ms-2"
            >
                {{ __("Cancel") }}
            </button>
        </div>
    </Modal>
</template>
<script>
import VForm from "@/libs/vform";
import cash from 'cash-dom';

export default {
    data() {
        return {
            onDelete: () => {},
            options: new VForm({
                icon: "AlertCircleIcon",
                title: __("Are you sure?"),
                description: __("This process cannot be undone."),
                textToEnter: null,
            }),
            enteredText: "",
        };
    },
    computed: {
        textIsConfirmed() {
            return this.enteredText === this.options.textToEnter;
        },
    },
    methods: {
        handleFireConfirmationModal({
            onDelete,
            options: {
                icon = this.options.icon,
                title = this.options.title,
                description = this.options.description,
                textToEnter,
            } = {},
        }) {
            this.onDelete = onDelete;

            this.options.fill({
                icon,
                title,
                description,
                textToEnter,
            });

            this.$nextTick(() => {
                cash("#delete-confirmation-modal").modal("show");
            });
        },
        handleDelete() {
            if (
                !this.options.textToEnter ||
                (this.options.textToEnter && this.textIsConfirmed)
            ) {
                cash("#delete-confirmation-modal").modal("hide");
                this.onDelete();
            }
        },
        handleModalClosed() {
            this.options.reset();
            this.enteredText = "";
        },
    },
    mounted() {
        this.$emitter.on(
            "fire-confirmation-modal",
            this.handleFireConfirmationModal
        );
    },
    beforeUnmount() {
        this.$emitter.off("fire-confirmation-modal");
    },
};
</script>
