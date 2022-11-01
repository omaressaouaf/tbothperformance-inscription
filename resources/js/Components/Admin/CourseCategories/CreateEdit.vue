<template>
    <Modal
        id="course-categories-create-edit"
        @modalClosed="handleModalClosed"
        :title="
            __(`${currentCourseCategory ? 'Edit' : 'Add'} :resource`, {
                resource: __('a course category'),
            })
        "
        :slide-over="modalSlideOver"
        size="modal-md"
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
    name: "CourseCategoriesCreateEdit",
    props: {
        modalSlideOver: Boolean,
    },
    data() {
        return {
            form: new VForm({
                name: "",
            }),
        };
    },
    watch: {
        currentCourseCategory: {
            handler(value) {
                this.form.fill(value || this.form.originalData);
            },
            immediate: true,
        },
    },
    computed: {
        ...mapState("admin/courseCategories", ["currentCourseCategory"]),
    },
    methods: {
        handleModalClosed() {
            this.$store.commit(
                "admin/courseCategories/setCurrentCourseCategory",
                null
            );
        },
        async handleSubmit() {
            if (this.currentCourseCategory) {
                await this.form.put(
                    route("admin.course-categories.update", [this.form.id])
                );
            } else {
                await this.form.post(route("admin.course-categories.store"));
            }

            cash("#course-categories-create-edit").modal("hide");
            this.form.reset();
            this.$inertia.reload();
        },
    },
};
</script>
