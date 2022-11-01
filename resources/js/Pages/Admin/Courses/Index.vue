<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title" />
    <CrudWrapper
        :title="__(':resources list', { resources: __('Courses') })"
        icon="BookIcon"
    >
        <DataTable
            :pagination="{
                links: courses.links,
                from: courses.from,
                to: courses.to,
                total: courses.total,
            }"
            table-to-export="courses"
            default-sort="-created_at"
            :sorts="[
                { text: 'Creation date', column: 'created_at' },
                { text: 'Update date', column: 'updated_at' },
            ]"
        >
            <template #actions>
                <button
                    :disabled="bulkDeleteLoading"
                    @click="handleBulkDelete('courses')"
                    class="btn btn-elevated-danger shadow-md"
                >
                    <TrashIcon class="w-4 h-4 me-2" />
                    {{ __("Delete") }}
                </button>
                <Link
                    :href="route('admin.courses.create')"
                    class="btn btn-elevated-primary shadow-md"
                >
                    <PlusIcon class="w-4 h-4 me-2" />
                    {{
                        __("Add :resource", {
                            resource: __("a course"),
                        })
                    }}
                </Link>
            </template>
            <template #filters-box>
                <CoursesFilters />
            </template>
            <template #headings>
                <tr>
                    <Th
                        ><input
                            v-model="toggleAllSelectedItems"
                            class="form-check-input bg-white"
                            type="checkbox"
                    /></Th>
                    <Th> {{ __("General") }} </Th>
                    <Th> {{ __("Eligible for CPF") }} </Th>
                    <Th> {{ __("Image") }} </Th>
                    <Th> {{ __("Category") }} </Th>
                    <Th> {{ __("Plans") }} </Th>
                    <Th> {{ __("Creation date") }} </Th>
                    <Th> {{ __("Actions") }} </Th>
                </tr>
            </template>
            <Tr v-for="course in courses.data" :key="course.id">
                <Td>
                    <input
                        v-model="selectedItems"
                        :value="course.id"
                        class="form-check-input bg-white"
                        type="checkbox"
                    />
                </Td>
                <Td>
                    <p
                        class="flex flex-col font-semibold text-gray-800 dark:text-gray-400"
                    >
                        <span>{{ course.title }}</span>
                        <span v-if="course.certificate" class="text-xs"
                            >{{ __("Certificate") }} :
                            {{ course.certificate }}</span
                        >
                        <span class="text-xs"
                            >{{ __("ID") }} : {{ course.id }}</span
                        >
                    </p>
                </Td>
                <Td>
                    <Badge
                        :class="[
                            course.eligible_for_cpf
                                ? 'bg-primary-1'
                                : 'bg-theme-39 bg-opacity-100',
                        ]"
                    >
                        {{ course.eligible_for_cpf ? __("Yes") : __("No") }}
                    </Badge>
                </Td>
                <Td>
                    <img
                        :src="course.image_url"
                        :alt="course.title"
                        class="h-8 w-auto rounded-lg shadow object-contain"
                        data-action="zoom"
                    />
                </Td>
                <Td>
                    {{ course.category?.name }}
                </Td>
                <Td>
                    <ShowMore :max-nodes="2" content-type="nodes">
                        <p v-for="plan in course.plans" :key="plan.id">
                            <Badge
                                class="bg-primary-1 block text-xs w-fit mb-2"
                            >
                                {{ plan.name }}
                            </Badge>
                        </p>
                    </ShowMore>
                </Td>
                <Td>
                    {{ $filters.formatDateTime(course.created_at) }}
                </Td>
                <Td>
                    <div class="flex">
                        <Link
                            :href="route('admin.courses.edit', [course.id])"
                            class="flex items-center me-3 text-theme-20"
                        >
                            <EditIcon class="w-4 h-4 me-1" />
                        </Link>
                        <button
                            class="flex items-center text-theme-21"
                            @click="handleDelete(course)"
                        >
                            <Trash2Icon class="w-4 h-4 me-1" />
                        </button>
                    </div>
                </Td>
            </Tr>
        </DataTable>
    </CrudWrapper>
</template>

<script>
import { fireConfirmationModal } from "@/helpers";
import bulk from "@/mixins/bulk";
import CoursesFilters from "@/Components/Admin/Courses/Filters.vue";

export default {
    components: { CoursesFilters },
    mixins: [bulk],
    props: {
        courses: Object,
    },
    computed: {
        title() {
            return __(":data Management", { data: __("Courses") });
        },
        allItems() {
            return this.courses.data.map((course) => course.id); // required for bulk mixin
        },
    },
    methods: {
        handleDelete(course) {
            fireConfirmationModal(() => {
                this.$inertia.delete(
                    route("admin.courses.destroy", [course.id])
                );
            });
        },
    },
};
</script>
