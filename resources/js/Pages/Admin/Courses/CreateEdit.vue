<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title">
        <Link :href="route('admin.courses.index')">{{ __("Courses") }}</Link>
        <ChevronRightIcon class="breadcrumb__icon" />
    </Breadcrumb>
    <CrudWrapper :title="title" :icon="course ? 'EditIcon' : 'PlusIcon'">
        <div class="actions flex flex-wrap items-center mb-10 justify-end">
            <Link
                :href="route('admin.courses.index')"
                class="btn btn-elevated-danger shadow-md ms-2"
            >
                <ArrowLeftIcon class="w-4 h-4 me-2" />
                {{ __("Back to list") }}
            </Link>
        </div>
        <form @submit.prevent="handleSubmit" class="space-y-10 px-0 md:px-5">
            <ServerErrors />
            <div class="form-inline">
                <label class="form-label"
                    >{{ __("Title") }}
                    <span class="text-theme-21">*</span>
                </label>
                <input
                    v-model="form.title"
                    :placeholder="printPlaceholder('title')"
                    class="form-control"
                    required
                />
            </div>
            <div class="form-inline">
                <label class="form-label">{{ __("Certificate") }} </label>
                <input
                    v-model="form.certificate"
                    :placeholder="printPlaceholder('certificate')"
                    class="form-control"
                />
            </div>
            <div class="form-inline">
                <label class="form-label"
                    >{{ __("Eligible for CPF") }}
                    <span class="text-theme-21">*</span>
                </label>
                <input
                    v-model="form.eligible_for_cpf"
                    class="form-check-switch"
                    type="checkbox"
                />
            </div>
            <div class="flex w-full items-center gap-3">
                <div class="form-inline w-full">
                    <label class="form-label">{{ __("Image") }} </label>
                    <label class="w-full">
                        <input
                            class="form-control form-file"
                            type="file"
                            @input="form.image = $event.target.files[0]"
                            ref="imageInput"
                        />
                    </label>
                </div>
                <img
                    v-if="course"
                    :src="course.image_url"
                    :alt="course.title"
                    class="h-14 w-auto rounded-lg shadow object-contain"
                    data-action="zoom"
                />
            </div>
            <div class="form-inline">
                <label class="form-label flex-shrink-0">
                    {{ __("Category") }}
                </label>
                <div class="flex items-start w-full gap-5">
                    <select v-model="form.category_id" class="form-select">
                        <option :value="null" selected>
                            {{ __("Choose an option") }}
                        </option>
                        <option
                            v-for="courseCategory in courseCategories"
                            :key="courseCategory.id"
                            :value="courseCategory.id"
                        >
                            {{ courseCategory.name }}
                        </option>
                    </select>
                    <a
                        href="javascript:;"
                        data-toggle="modal"
                        data-target="#course-categories-create-edit"
                        class="btn btn-primary"
                    >
                        <PlusIcon class="w-4 h-4" />
                    </a>
                </div>
            </div>
            <div class="form-inline">
                <label class="form-label flex-shrink-0"
                    >{{ __("Plans") }}
                    <span class="text-theme-21">*</span>
                </label>
                <div class="flex items-start w-full gap-5 ms-3">
                    <MultiSelect
                        v-model="form.plans"
                        :options="plansWithPivot"
                        :preserve-search="true"
                        :multiple="true"
                        :close-on-select="false"
                        :placeholder="`${__('Select')} ${__('plans')}`"
                        :show-labels="false"
                        :show-no-results="false"
                        track-by="id"
                        label="name"
                    />
                    <a
                        href="javascript:;"
                        data-toggle="modal"
                        data-target="#plans-create-edit"
                        class="btn btn-primary"
                    >
                        <PlusIcon class="w-4 h-4" />
                    </a>
                </div>
            </div>
            <template v-if="form.eligible_for_cpf">
                <div
                    v-for="(plan, index) in form.plans"
                    :key="index"
                    class="form-inline"
                >
                    <label class="form-label">
                        {{ __("CPF link") }} ({{ plan.name }})
                    </label>
                    <input
                        v-model="form.plans[index].pivot.cpf_link"
                        :placeholder="printPlaceholder('cpf_link')"
                        class="form-control"
                        type="url"
                    />
                </div>
            </template>

            <div class="flex justify-between">
                <button :disabled="form.processing" class="btn btn-primary">
                    <LoadingIcon
                        v-if="form.processing"
                        icon="oval"
                        color="white"
                        class="w-4 h-4 me-2"
                    />
                    {{ __("Save") }}
                </button>
            </div>
        </form>
    </CrudWrapper>
    <CourseCategoriesCreateEdit :modal-slide-over="true" />
    <PlansCreateEdit :modal-slide-over="true" />
</template>

<script>
import CourseCategoriesCreateEdit from "@/Components/Admin/CourseCategories/CreateEdit.vue";
import PlansCreateEdit from "@/Components/Admin/Plans/CreateEdit.vue";

export default {
    components: {
        CourseCategoriesCreateEdit,
        PlansCreateEdit,
    },
    props: {
        course: Object,
        courseCategories: Array,
        plans: Array,
    },
    data() {
        return {
            form: this.$inertia.form(
                this.course
                    ? { ...this.course, image: "" }
                    : {
                          title: "",
                          certificate: "",
                          image: "",
                          category_id: null,
                          eligible_for_cpf: true,
                          plans: [],
                      }
            ),
        };
    },
    computed: {
        title() {
            return __(`${this.course ? "Edit" : "Add"} :resource`, {
                resource: __("a course"),
            });
        },
        plansWithPivot() {
            return this.plans.map((plan) => ({
                ...plan,
                pivot: {},
            }));
        },
    },
    methods: {
        handleSubmit() {
            if (this.course) {
                this.form
                    .transform((data) => ({
                        ...data,
                        _method: "put",
                    }))
                    .post(route("admin.courses.update", [this.course.id]), {
                        onSuccess: () => {
                            this.$refs.imageInput.value = "";
                        },
                    });
            } else {
                this.form.post(route("admin.courses.store"));
            }
        },
    },
};
</script>
