// import { fireTextConfirmationModal } from "@/helpers";

export default {
    data() {
        return {
            selectedItems: [],
            bulkDeleteLoading: false,
        };
    },
    computed: {
        toggleAllSelectedItems: {
            get: function () {
                return (
                    this.allItems.length &&
                    this.allItems.length == this.selectedItems.length
                );
            },
            set: function (value) {
                if (value) {
                    this.allItems.forEach((item) => {
                        if (!this.selectedItems.includes(item)) {
                            this.selectedItems.push(item);
                        }
                    });
                } else {
                    this.selectedItems = [];
                }
            },
        },
    },
    methods: {
        handleBulkDelete(table) {
            if (!this.selectedItems.length) return;

            // fireTextConfirmationModal(() => {
                this.$inertia.delete(route("bulk.destroy", { table }), {
                    data: { ids: this.selectedItems },
                    onBefore: () => (this.bulkDeleteLoading = true),
                    onFinish: () => {
                        this.bulkDeleteLoading = false;
                        this.selectedItems = [];
                    },
                });
            // }, __("I understand"));
        },
    },
};
