<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title" />
    <CrudWrapper
        :title="__(':resources list', { resources: __('Plans') })"
        icon="GridIcon"
    >
        <DataTable
            :pagination="{
                links: plans.links,
                from: plans.from,
                to: plans.to,
                total: plans.total,
            }"
            :export-table="false"
        >
            <template #actions>
                <button
                    :disabled="bulkDeleteLoading"
                    @click="handleBulkDelete('plans')"
                    class="btn btn-elevated-danger shadow-md"
                >
                    <TrashIcon class="w-4 h-4 me-2" />
                    {{ __("Delete") }}
                </button>
                <a
                    href="javascript:;"
                    data-toggle="modal"
                    data-target="#plans-create-edit"
                    class="btn btn-elevated-primary shadow-md"
                >
                    <PlusIcon class="w-4 h-4 me-2" />
                    {{
                        __("Add :resource", {
                            resource: __("a plan"),
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
                    <Th> {{ __("Price") }} </Th>
                    <Th> {{ __("Features") }} </Th>
                    <Th> {{ __("Created at") }} </Th>
                    <Th> {{ __("Actions") }} </Th>
                </tr>
            </template>
            <Tr v-for="plan in plans.data" :key="plan.id">
                <Td>
                    <input
                        v-model="selectedItems"
                        :value="plan.id"
                        class="form-check-input bg-white"
                        type="checkbox"
                    />
                </Td>
                <Td>
                    {{ plan.id }}
                </Td>
                <Td>
                    {{ plan.name }}
                </Td>
                <Td>
                    <Badge class="bg-primary-1">
                        {{ $filters.formatMoney(plan.price) }}
                    </Badge>
                </Td>
                <Td>
                    <ShowMore :max-nodes="2" content-type="nodes">
                        <p
                            v-for="(feature, index) in plan.features"
                            :key="index"
                        >
                            <Badge
                                class="bg-primary-1 block mb-2 w-fit max-w-[270px] truncate ..."
                            >
                                {{ feature }}
                            </Badge>
                        </p>
                    </ShowMore>
                </Td>
                <Td>
                    {{ $filters.formatDateTime(plan.created_at) }}
                </Td>
                <Td>
                    <div class="flex">
                        <a
                            @click="handleEdit(plan)"
                            href="javascript:;"
                            data-toggle="modal"
                            data-target="#plans-create-edit"
                            class="flex items-center me-3 text-theme-20"
                        >
                            <EditIcon class="w-4 h-4 me-1" />
                        </a>
                        <button
                            class="flex items-center text-theme-21"
                            @click="handleDelete(plan)"
                        >
                            <Trash2Icon class="w-4 h-4 me-1" />
                        </button>
                    </div>
                </Td>
            </Tr>
        </DataTable>
    </CrudWrapper>
    <PlansCreateEdit />
</template>

<script>
import { fireConfirmationModal } from "@/helpers";
import bulk from "@/mixins/bulk";
import PlansCreateEdit from "@/Components/Admin/Plans/CreateEdit.vue";

export default {
    components: { PlansCreateEdit },
    mixins: [bulk],
    props: {
        plans: Object,
    },
    computed: {
        title() {
            return __(":data Management", { data: __("Plans") });
        },
        allItems() {
            return this.plans.data.map((plan) => plan.id); // required for bulk mixin
        },
    },
    methods: {
        handleEdit(plan) {
            this.$store.commit("admin/plans/setCurrentPlan", plan);
        },
        handleDelete(plan) {
            fireConfirmationModal(() => {
                this.$inertia.delete(route("admin.plans.destroy", [plan.id]));
            });
        },
    },
};
</script>
