<template>
    <DataTable
        :pagination="{
            links: enrollments.links,
            from: enrollments.from,
            to: enrollments.to,
            total: enrollments.total,
            currentPage: enrollments.current_page,
        }"
        table-to-export="enrollments"
        :export-query-params="{
            leadId: currentLead?.id,
        }"
        default-sort="-created_at"
        :sorts="[
            { text: 'Started at', column: 'created_at' },
            { text: 'Lead last interaction', column: 'updated_at' },
            { text: 'Finished at', column: 'completed_at' },
            { text: 'Course start date', column: 'course_start_date' },
        ]"
    >
        <template #actions>
            <button
                :disabled="bulkDeleteLoading"
                @click="handleBulkDelete('enrollments')"
                class="btn btn-elevated-danger shadow-md"
            >
                <TrashIcon class="w-4 h-4 me-2" />
                {{ __("Delete") }}
            </button>
        </template>
        <template #filters-box>
            <EnrollmentsFilters />
        </template>
        <template #headings>
            <tr>
                <Th
                    ><input
                        v-model="toggleAllSelectedItems"
                        class="form-check-input bg-white"
                        type="checkbox"
                /></Th>
                <Th> {{ __("Number") }} </Th>
                <Th v-if="!currentLead"> {{ __("Lead") }} </Th>
                <Th> {{ __("Course") }} </Th>
                <Th>{{ __("Financing") }}</Th>
                <Th>{{ __("Status") }}</Th>
            </tr>
        </template>
        <Tr v-for="enrollment in enrollments.data" :key="enrollment.id">
            <Td>
                <input
                    v-model="selectedItems"
                    :value="enrollment.id"
                    class="form-check-input bg-white"
                    type="checkbox"
                />
            </Td>
            <Td>
                <Badge class="bg-primary-11 text-xs">
                    {{ enrollment.number }}
                </Badge>
            </Td>
            <Td v-if="!currentLead">
                <p
                    class="flex flex-col space-y-1 font-semibold text-gray-800 dark:text-gray-400"
                >
                    <Component
                        :is="enrollment.lead_id ? 'Link' : 'span'"
                        :href="'#'"
                        :class="[
                            enrollment.lead_id
                                ? 'text-primary-11 hover:underline'
                                : '',
                        ]"
                        >{{ enrollment.lead_data.first_name }}
                        {{ enrollment.lead_data.last_name }}</Component
                    >
                    <span class="text-xs">
                        <PhoneIcon class="w-3 h-3 me-2" />{{
                            enrollment.lead_data.phone
                        }}</span
                    >
                    <span class="text-xs normal-case"
                        ><MailIcon class="w-3 h-3 me-2" />{{
                            enrollment.lead_data.email
                        }}</span
                    >
                </p>
            </Td>
            <Td>
                <p
                    v-if="enrollment.course"
                    class="flex flex-col space-y-1 font-semibold text-gray-800 dark:text-gray-400"
                >
                    <Link
                        v-if="enrollment.course"
                        :href="
                            route('admin.courses.edit', [enrollment.course_id])
                        "
                        class="text-primary-11 hover:underline"
                        >{{ enrollment.course.title }}</Link
                    >
                    <span class="text-xs" v-if="enrollment.plan">
                        <ListIcon class="w-3 h-3 me-2" />{{
                            enrollment.plan.name
                        }}
                        {{ $filters.formatMoney(enrollment.total) }}
                    </span>
                    <span class="text-xs" v-if="enrollment.course_start_date">
                        <Tippy tag="span" :content="__('Course start date')"
                            ><CalendarIcon class="w-3 h-3 me-2" /> </Tippy
                        >{{ $filters.formatDate(enrollment.course_start_date) }}
                    </span>
                </p>
                <span v-else class="text-theme-21">{{
                    __("Not selected")
                }}</span>
            </Td>
            <Td>
                <p
                    class="flex flex-col items-start space-y-1 font-semibold text-gray-800 dark:text-gray-400"
                >
                    <Badge
                        v-if="enrollment.financing_type"
                        class="uppercase text-xs"
                        :class="[
                            enrollment.financing_type === 'cpf'
                                ? 'bg-primary-1'
                                : 'bg-theme-39 bg-opacity-100',
                        ]"
                        >{{ __(enrollment.financing_type) }}</Badge
                    >
                    <span v-else class="text-theme-21">{{
                        __("Not selected")
                    }}</span>
                    <span v-if="enrollment.cpf_amount" class="text-xs">
                        <span class="text-primary-11">
                            {{ __("CPF amount") }} :
                        </span>
                        {{ $filters.formatMoney(enrollment.cpf_amount) }}
                    </span>
                    <span
                        v-if="enrollment.cpf_remaining_charges"
                        class="text-xs"
                    >
                        <span class="text-primary-11">
                            {{ __("Remaining charges") }} :
                        </span>
                        {{
                            $filters.formatMoney(
                                enrollment.cpf_remaining_charges
                            )
                        }}
                    </span>
                    <span v-if="enrollment.cpf_dossier_number" class="text-xs">
                        <span class="text-primary-11">
                            {{ __("Dossier number") }} :
                        </span>
                        {{ enrollment.cpf_dossier_number }}</span
                    >
                    <span v-if="enrollment.payment_method" class="text-xs">
                        <span class="text-primary-11">
                            {{ __("Payment method") }} :
                        </span>
                        {{ __(enrollment.payment_method) }}</span
                    >
                    <span v-if="enrollment.paid_at" class="text-xs">
                        <span class="text-primary-11">
                            {{ __("Paid at") }} :
                        </span>
                        {{ $filters.formatDateTime(enrollment.paid_at) }}</span
                    >
                </p>
            </Td>
            <Td>
                <Badge
                    :class="{
                        'bg-gray-300 text-gray-800':
                            enrollment.status === 'pending',
                        'bg-orange-600':
                            enrollment.status === 'contract signed',
                        'bg-green-600': enrollment.status === 'complete',
                        'bg-theme-21': enrollment.status === 'canceled',
                    }"
                    class="text-xs capitalize"
                >
                    {{ __(enrollment.status) }}
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
                                :href="
                                    route('admin.enrollments.show', [
                                        enrollment.id,
                                    ])
                                "
                                icon="EyeIcon"
                                :title="__('View')"
                                class="text-primary-11"
                            />
                            <DropdownLink
                                href="#"
                                icon="Trash2Icon"
                                :title="__('Remove')"
                                class="text-theme-21"
                                @click="handleDelete(enrollment)"
                            />
                        </div>
                    </template>
                </Dropdown>
            </Td>
        </Tr>
    </DataTable>
</template>

<script>
import { fireConfirmationModal } from "@/helpers";
import bulk from "@/mixins/bulk";
import EnrollmentsFilters from "@/Components/Admin/Enrollments/Filters.vue";

export default {
    components: { EnrollmentsFilters },
    mixins: [bulk],
    props: {
        enrollments: Object,
    },
    inject: {
        currentLead: {
            default: null,
        },
    },
    computed: {
        allItems() {
            return this.enrollments.data.map((enrollment) => enrollment.id); // required for bulk mixin
        },
    },
    methods: {
        handleDelete(enrollment) {
            fireConfirmationModal(() => {
                this.$inertia.delete(
                    route("admin.enrollments.destroy", [enrollment.id])
                );
            });
        },
    },
};
</script>
