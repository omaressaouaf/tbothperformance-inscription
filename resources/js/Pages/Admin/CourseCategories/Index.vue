<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title" />
    <CrudWrapper
        :title="__(':resources list', { resources: __('Course Categories') })"
        icon="GridIcon"
    >
        <DataTable
            :pagination="{
                links: courseCategories.links,
                from: courseCategories.from,
                to: courseCategories.to,
                total: courseCategories.total,
            }"
            :export-table="false"
        >
            <template #actions>
                <button
                    :disabled="bulkDeleteLoading"
                    @click="handleBulkDelete('course_categories')"
                    class="btn btn-elevated-danger shadow-md"
                >
                    <TrashIcon class="w-4 h-4 me-2" />
                    {{ __("Delete") }}
                </button>
                <a
                    href="javascript:;"
                    data-toggle="modal"
                    data-target="#course-categories-create-edit"
                    class="btn btn-elevated-primary shadow-md"
                >
                    <PlusIcon class="w-4 h-4 me-2" />
                    {{
                        __("Add :resource", {
                            resource: __("a course category"),
                        })
                    }}
                </a>
            </template>
            <template #headings>
                <tr>
                    <Th
                        ><input
                            v-model="toggleAllSelectedItems"
                            class="form-check-input bg-white"
                            type="checkbox"
                    /></Th>
                    <Th> {{ __("ID") }} </Th>
                    <Th> {{ __("Name") }} </Th>
                    <Th> {{ __("Created at") }} </Th>
                    <Th> {{ __("Actions") }} </Th>
                </tr>
            </template>
            <Tr
                v-for="courseCategory in courseCategories.data"
                :key="courseCategory.id"
            >
                <Td>
                    <input
                        v-model="selectedItems"
                        :value="courseCategory.id"
                        class="form-check-input bg-white"
                        type="checkbox"
                    />
                </Td>
                <Td>
                    {{ courseCategory.id }}
                </Td>
                <Td>
                    {{ courseCategory.name }}
                </Td>
                <Td>
                    {{ $filters.formatDateTime(courseCategory.created_at) }}
                </Td>
                <Td>
                    <div class="flex">
                        <a
                            @click="handleEdit(courseCategory)"
                            href="javascript:;"
                            data-toggle="modal"
                            data-target="#course-categories-create-edit"
                            class="flex items-center me-3 text-theme-20"
                        >
                            <EditIcon class="w-4 h-4 me-1" />
                        </a>
                        <button
                            class="flex items-center text-theme-21"
                            @click="handleDelete(courseCategory)"
                        >
                            <Trash2Icon class="w-4 h-4 me-1" />
                        </button>
                    </div>
                </Td>
            </Tr>
        </DataTable>
    </CrudWrapper>
    <CourseCategoriesCreateEdit />
</template>

<script>
import { fireConfirmationModal } from "@/helpers";
import bulk from "@/mixins/bulk";
import CourseCategoriesCreateEdit from "@/Components/Admin/CourseCategories/CreateEdit.vue";

export default {
    components: { CourseCategoriesCreateEdit },
    mixins: [bulk],
    props: {
        courseCategories: Object,
    },
    computed: {
        title() {
            return __(":data Management", { data: __("Course Categories") });
        },
        allItems() {
            return this.courseCategories.data.map(
                (courseCategory) => courseCategory.id
            ); // required for bulk mixin
        },
    },
    methods: {
        handleEdit(courseCategory) {
            this.$store.commit(
                "admin/courseCategories/setCurrentCourseCategory",
                courseCategory
            );
        },
        handleDelete(courseCategory) {
            fireConfirmationModal(() => {
                this.$inertia.delete(
                    route("admin.course-categories.destroy", [
                        courseCategory.id,
                    ])
                );
            });
        },
    },
};
</script>
