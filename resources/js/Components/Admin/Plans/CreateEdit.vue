<template>
    <Modal
        id="plans-create-edit"
        @modalClosed="handleModalClosed"
        :title="
            __(`${currentPlan ? 'Edit' : 'Add'} :resource`, {
                resource: __('a plan'),
            })
        "
        :slide-over="modalSlideOver"
        size="modal-xl"
    >
        <form @submit.prevent="handleSubmit" class="space-y-10 px-0 md:px-5">
            <ServerErrors :server-errors="form.errors.flatten()" />
            <div class="form-inline">
                <label class="form-label"
                    >{{ __("Name") }}
                    <span class="text-theme-21">*</span>
                </label>
                <input
                    v-model="form.name"
                    :placeholder="printPlaceholder('name')"
                    class="form-control"
                    required
                />
            </div>
            <div class="form-inline">
                <label class="form-label flex-shrink-0"
                    >{{ __("Price") }}
                    <span class="text-theme-21">*</span>
                </label>
                <div class="input-group w-full">
                    <input
                        v-model="form.price"
                        :placeholder="printPlaceholder('price')"
                        class="form-control"
                        type="number"
                        step="0.01"
                        required
                    />
                    <div class="input-group-text">{{ __("€") }}</div>
                </div>
            </div>
            <div
                class="rounded shadow border border-gray-200 dark:border-gray-800 px-5 py-4"
            >
                <div>
                    <div class="flex justify-between items-start mb-5">
                        <label class="form-label flex-shrink-0"
                            >{{ __("Features") }}
                            <span class="text-theme-21">*</span>
                        </label>
                        <button
                            type="button"
                            @click="addFeature"
                            class="btn btn-primary btn-sm btn-rounded"
                        >
                            <PlusIcon class="w-4 h-4 me-1" /> {{ __("Add") }}
                        </button>
                    </div>
                    <div
                        v-for="(feature, index) in form.features"
                        :key="index"
                        class="mb-4 flex gap-2"
                    >
                        <input
                            v-model="form.features[index]"
                            :placeholder="printPlaceholder('title')"
                            class="form-control"
                            required
                        />
                        <button
                            v-if="form.features?.length > 1"
                            type="button"
                            @click="removeFeature(index)"
                        >
                            <XIcon class="w-4 h-4 text-theme-21" />
                        </button>
                    </div>
                </div>
            </div>
            <button :disabled="form.busy" class="btn btn-primary">
                <LoadingIcon
                    v-if="form.busy"
                    icon="oval"
                    color="white"
                    class="w-4 h-4 me-2"
                />
                {{ __("Save") }}
            </button>
        </form>
    </Modal>
</template>

<script>
import cash from "cash-dom";
import { mapState } from "vuex";
import VForm from "@/libs/vform";

export default {
    name: "PlansCreateEdit",
    props: {
        modalSlideOver: Boolean,
    },
    data() {
        return {
            form: new VForm({
                name: "",
                price: "",
                features: [""],
            }),
        };
    },
    watch: {
        currentPlan: {
            handler(value) {
                this.form.fill(_.cloneDeep(value) || this.form.originalData);
            },
            immediate: true,
        },
    },
    computed: {
        ...mapState("admin/plans", ["currentPlan"]),
    },
    methods: {
        addFeature() {
            this.form.features = [...this.form.features, ""];
        },
        removeFeature(index) {
            this.form.features = this.form.features.filter(
                (feature, i) => i != index
            );
        },
        handleModalClosed() {
            this.$store.commit("admin/plans/setCurrentPlan", null);
        },
        async handleSubmit() {
            if (this.currentPlan) {
                await this.form.put(
                    route("admin.plans.update", [this.form.id])
                );
            } else {
                await this.form.post(route("admin.plans.store"));
            }

            cash("#plans-create-edit").modal("hide");
            this.form.reset();
            this.$inertia.reload();
        },
    },
};
</script>
