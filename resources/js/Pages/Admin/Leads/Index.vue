<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title" />
    <CrudWrapper
        :title="__(':resources list', { resources: __('Leads') })"
        icon="UsersIcon"
    >
        <DataTable
            :pagination="{
                links: leads.links,
                from: leads.from,
                to: leads.to,
                total: leads.total,
            }"
            table-to-export="leads"
            default-sort="-created_at"
            :sorts="[{ text: 'Joined at', column: 'created_at' }]"
        >
            <template #actions>
                <button
                    :disabled="bulkDeleteLoading"
                    @click="handleBulkDelete('leads')"
                    class="btn btn-elevated-danger shadow-md"
                >
                    <TrashIcon class="w-4 h-4 me-2" />
                    {{ __("Delete") }}
                </button>
            </template>
            <template #filters-box>
                <LeadsFilters />
            </template>
            <template #headings>
                <tr>
                    <Th
                        ><input
                            v-model="toggleAllSelectedItems"
                            class="form-check-input bg-white"
                            type="checkbox"
                    /></Th>
                    <Th> {{ __("Full name") }} </Th>
                    <Th> {{ __("Contact information") }} </Th>
                    <Th> {{ __("Career") }} </Th>
                    <Th> {{ __("Joined at") }} </Th>
                    <Th> {{ __("Enrollments") }} </Th>
                </tr>
            </template>
            <Tr v-for="lead in leads.data" :key="lead.id">
                <Td>
                    <input
                        v-model="selectedItems"
                        :value="lead.id"
                        class="form-check-input bg-white"
                        type="checkbox"
                    />
                </Td>
                <Td>
                    {{ lead.full_name }}
                </Td>
                <Td>
                    <p
                        class="flex flex-col space-y-1 font-semibold text-gray-800 dark:text-gray-400"
                    >
                        <span>
                            <PhoneIcon class="w-4 h-4 me-2 text-primary-11" />
                            {{ lead.phone }}
                        </span>
                        <span class="normal-case">
                            <MailIcon class="w-4 h-4 me-2 text-primary-11" />
                            {{ lead.email }}
                        </span>
                    </p>
                </Td>
                <Td>
                    <p
                        class="flex flex-col space-y-1 font-semibold text-gray-800 dark:text-gray-400"
                    >
                        <span>
                            <Tippy
                                tag="span"
                                :content="__('Years worked in france')"
                            >
                                <ClockIcon
                                    class="w-4 h-4 me-2 text-primary-11"
                                />
                            </Tippy>
                            {{ __(lead.years_worked_in_france) }}
                        </span>
                        <span>
                            <Tippy
                                tag="span"
                                :content="__('Professional situation')"
                            >
                                <ActivityIcon
                                    class="w-4 h-4 me-2 text-primary-11"
                                />
                            </Tippy>
                            {{ __(lead.professional_situation) }}
                        </span>
                    </p>
                </Td>
                <Td>
                    {{ $filters.formatDateTime(lead.created_at) }}
                </Td>
                <Td>
                    <Badge
                        class="text-xs"
                        :class="[
                            lead.enrollments_count > 0
                                ? 'bg-theme-20'
                                : 'bg-theme-21',
                        ]"
                    >
                        {{ lead.enrollments_count }}
                    </Badge>
                </Td>
                <Td>
                    <Dropdown>
                        <template #trigger>
                            <button>
                                <MoreHorizontalIcon
                                    class="w-6 h-6 text-gray-700 font-semibold"
                                />
                            </button>
                        </template>
                        <template #content>
                            <div class="p-2">
                                <DropdownLink
                                    :href="route('admin.leads.show', [lead.id])"
                                    icon="EyeIcon"
                                    :title="__('View')"
                                    class="text-primary-11"
                                />
                                <DropdownLink
                                    href="#"
                                    icon="Trash2Icon"
                                    :title="__('Remove')"
                                    class="text-theme-21"
                                    @click="handleDelete(lead)"
                                />
                            </div>
                        </template>
                    </Dropdown>
                </Td>
            </Tr>
        </DataTable>
    </CrudWrapper>
</template>

<script>
import { fireConfirmationModal } from "@/helpers";
import bulk from "@/mixins/bulk";
import LeadsFilters from "@/Components/Admin/Leads/Filters.vue";

export default {
    components: {
        LeadsFilters,
    },
    mixins: [bulk],
    props: {
        leads: Object,
    },
    computed: {
        title() {
            return __(":data Management", { data: __("Leads") });
        },
        allItems() {
            return this.leads.data.map((lead) => lead.id); // required for bulk mixin
        },
    },
    methods: {
        handleDelete(lead) {
            fireConfirmationModal(() => {
                this.$inertia.delete(route("admin.leads.destroy", [lead.id]));
            });
        },
    },
};
</script>
