<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title">
        <Link :href="route('admin.enrollments.index')">{{
            __("Enrollments")
        }}</Link>
        <ChevronRightIcon class="breadcrumb__icon" />
    </Breadcrumb>
    <div class="mt-8">
        <CrudWrapper :title="title" icon="EyeIcon">
            <div
                class="max-w-[fit-content] ms-auto flex flex-col lg:flex-row space-y-2 lg:space-y-0"
            >
                <Dropdown
                    v-if="
                        enrollment.status === 'contract signed' &&
                        enrollment.financing_type === 'manual'
                    "
                    class="me-2"
                >
                    <template #trigger>
                        <button
                            class="btn btn-success w-full"
                            aria-expanded="false"
                        >
                            <CheckSquareIcon class="w-4 h-3 me-2" />
                            {{ __("Set as complete") }}
                        </button>
                    </template>
                    <template #content>
                        <form
                            @submit.prevent="handleSetAsComplete"
                            class="p-5 space-y-5"
                        >
                            <div>
                                <p
                                    class="font-semibold text-xs text-gray-600 dark:text-gray-200"
                                >
                                    <AlertCircleIcon class="w-4 h-4 me-2" />{{
                                        __(
                                            "If for whatever reason the lead paid with another payment method. you can use this form to mark the enrollment as complete"
                                        )
                                    }}
                                </p>
                            </div>
                            <div>
                                <label class="form-label flex-shrink-o"
                                    >{{ __("Paid at") }}
                                    <span class="text-theme-21 ms-1">*</span>
                                </label>
                                <input
                                    v-model="setAsCompleteForm.paid_at"
                                    :placeholder="printPlaceholder('paid_at')"
                                    class="form-control"
                                    type="datetime-local"
                                    required
                                />
                            </div>
                            <div class="mt-5 flex items-center flex-wrap gap-4">
                                <label class="form-label flex-shrink-o"
                                    >{{ __("Payment method") }}
                                    <span class="text-theme-21 ms-1">*</span>
                                </label>
                                <select
                                    v-model="setAsCompleteForm.payment_method"
                                    class="form-select"
                                    required
                                >
                                    <option :value="null" selected>
                                        {{ __("Choose an option") }}
                                    </option>
                                    <option
                                        v-for="(paymentMethod, index) in [
                                            'bank transfer',
                                            'check',
                                            'cash',
                                            'other',
                                        ]"
                                        :key="index"
                                        :value="paymentMethod"
                                    >
                                        {{ __(paymentMethod) }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex items-center">
                                <button
                                    :disabled="
                                        setAsCompleteForm.busy ||
                                        !setAsCompleteForm.payment_method
                                    "
                                    class="btn btn-primary ml-auto mt-3"
                                    type="submit"
                                >
                                    {{ __("Save") }}
                                </button>
                            </div>
                        </form>
                    </template>
                </Dropdown>
                <button
                    v-if="!['complete', 'canceled'].includes(enrollment.status)"
                    @click="handleCancel"
                    class="btn btn-elevated-warning shadow-md me-2"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="handleDelete"
                    class="btn btn-elevated-danger shadow-md me-2"
                >
                    {{ __("Delete") }}
                </button>
                <Link
                    :href="route('admin.enrollments.index')"
                    class="btn btn-elevated-secondary shadow-md"
                >
                    <ArrowLeftIcon class="w-4 h-4 me-2" />
                    {{ __("Back to list") }}
                </Link>
            </div>
            <div class="px-5 pt-5">
                <div
                    class="flex flex-col lg:flex-row flex-wrap border-b border-gray-200 dark:border-dark-5 pb-5 -mx-5"
                >
                    <div
                        class="mt-6 lg:mt-0 flex-auto flex-shrink-0 dark:text-gray-300 px-5 border-s border-e border-gray-200 dark:border-dark-5 border-t lg:border-t-0 pt-5 lg:pt-0"
                    >
                        <div
                            class="flex gap-2 font-semibold text-center lg:text-start lg:mt-3"
                        >
                            {{ __("General") }}
                        </div>
                        <div class="space-y-3 mt-5">
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Number')">
                                    <HashIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <Badge class="bg-primary-11 text-xs me-2">
                                    {{ enrollment.number }}
                                </Badge>
                                <Badge
                                    :class="{
                                        'bg-gray-300 text-gray-800':
                                            enrollment.status === 'pending',
                                        'bg-orange-600':
                                            enrollment.status ===
                                            'contract signed',
                                        'bg-green-600':
                                            enrollment.status === 'complete',
                                        'bg-theme-21':
                                            enrollment.status === 'canceled',
                                    }"
                                    class="bg-primary-11 text-xs"
                                >
                                    {{ __(enrollment.status) }}
                                </Badge>
                            </div>
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Started at')">
                                    <CalendarIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span>
                                    {{
                                        $filters.formatDateTime(
                                            enrollment.created_at
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="enrollment.completed_at"
                                class="flex items-center"
                            >
                                <Tippy tag="span" :content="__('Finished at')">
                                    <CheckSquareIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span>
                                    {{
                                        $filters.formatDateTime(
                                            enrollment.completed_at
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="enrollment.completed_by"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Completed manually by") }} :
                                </span>
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit"
                                >
                                    <img
                                        :alt="
                                            responsibleUserForm.responsible_user
                                                ?.name
                                        "
                                        src="/images/avatar.png"
                                    />
                                </div>
                                <Link
                                    :href="'#'"
                                    class="text-primary-11 hover:underline"
                                >
                                    {{ enrollment.completed_by.name }}
                                </Link>
                            </div>
                            <div
                                v-if="enrollment.canceled_by"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Canceled by") }} :
                                </span>
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit"
                                >
                                    <img
                                        :alt="
                                            responsibleUserForm.responsible_user
                                                ?.name
                                        "
                                        src="/images/avatar.png"
                                    />
                                </div>
                                <Link
                                    :href="'#'"
                                    class="text-primary-11 hover:underline"
                                >
                                    {{ enrollment.canceled_by.name }}
                                </Link>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-primary-11 flex-shrink-0">
                                    {{ __("Responsible user") }} :
                                </span>
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit"
                                >
                                    <img
                                        :alt="
                                            responsibleUserForm.responsible_user
                                                ?.name
                                        "
                                        src="/images/avatar.png"
                                    />
                                </div>
                                <div v-if="enrollment.status === 'canceled'">
                                    <Link
                                        v-if="enrollment.responsible_user"
                                        :href="'#'"
                                        class="text-primary-11 hover:underline"
                                    >
                                        {{ enrollment.responsible_user?.name }}
                                    </Link>
                                    <span v-else class="text-theme-21">
                                        {{ __("Not selected") }}
                                    </span>
                                </div>
                                <SearchSelect
                                    v-else
                                    :defaultOptionText="__('Not selected')"
                                    v-model="
                                        responsibleUserForm.responsible_user_id
                                    "
                                    :options="users"
                                    track-by="id"
                                    label="name"
                                    class="btn-sm"
                                >
                                    <template #option="props">
                                        <div class="flex items-start gap-2">
                                            <div
                                                class="flex-shrink-0 w-6 h-6 rounded-full overflow-hidden shadow-lg image-fit"
                                            >
                                                <img
                                                    :alt="props.option.name"
                                                    src="/images/avatar.png"
                                                />
                                            </div>
                                            <div>
                                                <p>
                                                    {{ props.option.name }}
                                                </p>
                                                <p class="text-xs mt-0.5">
                                                    {{ props.option.email }}
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                </SearchSelect>
                            </div>
                            <div
                                v-if="enrollment.status === 'complete'"
                                class="flex items-center gap-2"
                            >
                                <div class="form-inline">
                                    <input
                                        v-model="enrollment.processed"
                                        class="form-check-switch me-2 bg-gray-200"
                                        type="checkbox"
                                        @change="
                                            $inertia.put(
                                                route(
                                                    'admin.enrollments.toggle-processed',
                                                    [enrollment.id]
                                                )
                                            )
                                        "
                                        id="toggle-processed"
                                    />
                                    <label
                                        class="form-label cursor-pointer"
                                        for="toggle-processed"
                                    >
                                        {{ __("Processed") }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="mt-6 lg:mt-0 flex-auto flex-shrink-0 dark:text-gray-300 px-5 border-s border-e border-gray-200 dark:border-dark-5 border-t lg:border-t-0 pt-5 lg:pt-0"
                    >
                        <div
                            class="flex gap-2 font-semibold text-center lg:text-start lg:mt-3"
                        >
                            {{ __("Lead") }}
                        </div>
                        <div class="space-y-3 mt-5">
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Full name')">
                                    <UserIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <Component
                                    :is="enrollment.lead ? 'Link' : 'span'"
                                    :href="
                                        route('admin.leads.show', [
                                            enrollment.lead,
                                        ])
                                    "
                                    :class="[
                                        enrollment.lead
                                            ? 'text-primary-11 hover:underline'
                                            : '',
                                    ]"
                                    class="capitalize"
                                >
                                    {{ enrollment.lead_data.first_name }}
                                    {{ enrollment.lead_data.last_name }}
                                </Component>
                            </div>
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Phone')">
                                    <PhoneIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span>
                                    {{ enrollment.lead_data.phone }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Email')">
                                    <MailIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span>
                                    {{ enrollment.lead_data.email }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <Tippy
                                    tag="span"
                                    :content="__('Years worked in france')"
                                >
                                    <ClockIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span class="capitalize">
                                    {{
                                        __(
                                            enrollment.lead_data
                                                .years_worked_in_france
                                        )
                                    }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <Tippy
                                    tag="span"
                                    :content="__('Professional situation')"
                                >
                                    <ActivityIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span class="capitalize">
                                    {{
                                        __(
                                            enrollment.lead_data
                                                .professional_situation
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="enrollment.course"
                        class="mt-6 lg:mt-0 flex-auto flex-shrink-0 dark:text-gray-300 px-5 border-s border-e border-gray-200 dark:border-dark-5 border-t lg:border-t-0 pt-5 lg:pt-0"
                    >
                        <div
                            class="flex gap-2 font-semibold text-center lg:text-start lg:mt-3"
                        >
                            {{ __("Course") }}
                        </div>
                        <div class="space-y-3 mt-5">
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Course')">
                                    <BookIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <Link
                                    :href="
                                        route('admin.courses.edit', [
                                            enrollment.course.id,
                                        ])
                                    "
                                    class="text-primary-11 hover:underline capitalize me-2"
                                >
                                    {{ enrollment.course?.title }}
                                </Link>
                                <a
                                    v-if="enrollment.financing_type === 'cpf'"
                                    :href="enrollment.cpf_link"
                                    target="_blank"
                                    rel="noopener"
                                    class="text-primary-11 underline capitalize"
                                >
                                    {{ __("Enrollment link") }}
                                </a>
                            </div>
                            <div
                                v-if="enrollment.plan"
                                class="flex items-center"
                            >
                                <Tippy tag="span" :content="__('Plan')">
                                    <ListIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span class="capitalize">
                                    {{ enrollment.plan?.name }}
                                    {{
                                        $filters.formatMoney(
                                            enrollment.plan?.price
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="enrollment.course_start_date"
                                class="flex items-center"
                            >
                                <Tippy
                                    tag="span"
                                    :content="__('Course start date')"
                                >
                                    <CalendarIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span class="capitalize">
                                    {{
                                        $filters.formatDate(
                                            enrollment.course_start_date
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="enrollment.financing_type"
                        class="mt-6 lg:mt-0 flex-auto flex-shrink-0 dark:text-gray-300 px-5 border-s border-e border-gray-200 dark:border-dark-5 border-t lg:border-t-0 pt-5 lg:pt-0 pb-24"
                    >
                        <div
                            class="flex gap-2 font-semibold text-center lg:text-start lg:mt-3"
                        >
                            {{ __("Financing") }}
                        </div>
                        <div class="space-y-3 mt-5">
                            <div class="flex items-center">
                                <Badge
                                    class="text-xs uppercase"
                                    :class="[
                                        enrollment.financing_type === 'cpf'
                                            ? 'bg-primary-1'
                                            : 'bg-theme-39 bg-opacity-100',
                                    ]"
                                >
                                    {{ __(enrollment.financing_type) }}
                                </Badge>
                            </div>
                            <div
                                v-if="enrollment.cpf_amount"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("CPF amount") }} :
                                </span>
                                <span>
                                    {{
                                        $filters.formatMoney(
                                            enrollment.cpf_amount
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="enrollment.cpf_remaining_charges"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Remaining charges") }} :
                                </span>
                                <span>
                                    {{
                                        $filters.formatMoney(
                                            enrollment.cpf_remaining_charges
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="enrollment.cpf_dossier_number"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Dossier number") }} :
                                </span>
                                <span>
                                    {{ enrollment.cpf_dossier_number }}
                                </span>
                            </div>
                            <div
                                v-if="enrollment.payment_method"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Payment method") }} :
                                </span>
                                <span>
                                    {{ __(enrollment.payment_method) }}
                                </span>
                            </div>
                            <div
                                v-if="enrollment.paid_at"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Paid at") }} :
                                </span>
                                <span>
                                    {{
                                        $filters.formatDateTime(
                                            enrollment.paid_at
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="
                        enrollment.financing_type === 'manual' &&
                        ['contract signed', 'complete'].includes(
                            enrollment.status
                        )
                    "
                    class="flex flex-col lg:flex-row flex-wrap border-b border-gray-200 dark:border-dark-5 pb-5 -mx-5"
                >
                    <div
                        class="mt-6 lg:mt-0 flex-auto flex-shrink-0 dark:text-gray-300 px-5 border-s border-e border-gray-200 dark:border-dark-5 border-t pt-5"
                    >
                        <div
                            class="flex gap-2 font-semibold text-center lg:text-start lg:mt-3"
                        >
                            {{ __("Contract") }}
                        </div>
                        <div class="space-y-3 mt-5">
                            <div
                                v-if="enrollment.signature_request_data?.id"
                                class="flex flex-col lg:flex-row items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Signature request id") }} :
                                </span>
                                <span class="text-center">
                                    {{ enrollment.signature_request_data.id }}
                                </span>
                            </div>
                            <div
                                v-if="
                                    enrollment.signature_request_data
                                        ?.signature_link
                                "
                                class="flex flex-col lg:flex-row items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Signature link") }} :
                                </span>
                                <a
                                    :href="
                                        enrollment.signature_request_data
                                            .signature_link
                                    "
                                    class="text-primary-11 hover:underline"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    {{
                                        enrollment.signature_request_data.signature_link.substring(
                                            0,
                                            30
                                        )
                                    }}
                                    ...
                                </a>
                            </div>
                            <div
                                v-if="enrollment.contract_files?.unsigned"
                                class="flex flex-col lg:flex-row items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Download") }} :
                                </span>
                                <a
                                    :href="
                                        route('files.serve', [
                                            enrollment.contract_files?.unsigned,
                                        ])
                                    "
                                    class="text-primary-11 hover:underline"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    {{ __("Unsigned contract") }}
                                </a>
                            </div>
                            <div
                                v-if="enrollment.contract_files?.signed"
                                class="flex flex-col lg:flex-row items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Download") }} :
                                </span>
                                <a
                                    :href="
                                        route('files.serve', [
                                            enrollment.contract_files?.signed,
                                        ])
                                    "
                                    class="text-primary-11 hover:underline"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    {{ __("Signed contract") }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </CrudWrapper>
    </div>
</template>

<script>
import { fireConfirmationModal } from "@/helpers";
import cash from "cash-dom";

export default {
    props: {
        enrollment: Object,
        users: Array,
    },
    data() {
        return {
            setAsCompleteForm: this.$inertia.form({
                payment_method: null,
                paid_at: this.$filters.formatDateForInput(new Date(), true),
            }),
            responsibleUserForm: this.$inertia.form({
                responsible_user_id: this.enrollment.responsible_user_id,
            }),
        };
    },
    computed: {
        title() {
            return __("View :resource", {
                resource: __("Enrollment"),
            });
        },
    },
    watch: {
        "responsibleUserForm.responsible_user_id":
            "handleUpdateResponsibleUser",
    },
    methods: {
        handleCancel() {
            fireConfirmationModal(
                () => {
                    this.$inertia.put(
                        route("admin.enrollments.cancel", [this.enrollment.id])
                    );
                },
                {
                    confirmButtonText: __("Confirm"),
                }
            );
        },
        handleDelete() {
            fireConfirmationModal(() => {
                this.$inertia.delete(
                    route("admin.enrollments.destroy", [this.enrollment.id])
                );
            });
        },
        handleSetAsComplete() {
            fireConfirmationModal(
                () => {
                    this.setAsCompleteForm.put(
                        route("admin.enrollments.complete", [
                            this.enrollment.id,
                        ])
                    );
                },
                {
                    confirmButtonText: __("Confirm"),
                }
            );
        },
        handleUpdateResponsibleUser() {
            this.responsibleUserForm.put(
                route("admin.enrollments.responsible-user.update", [
                    this.enrollment.id,
                ]),
                {
                    onSuccess: () => {
                        cash("#enrollments-edit-responsible-user").modal(
                            "hide"
                        );
                    },
                }
            );
        },
    },
};
</script>
