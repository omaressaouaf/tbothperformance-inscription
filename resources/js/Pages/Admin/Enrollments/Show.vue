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
                                <Badge class="bg-primary-11 text-xs">
                                    {{ enrollment.number }}
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
                            <div class="flex items-center">
                                <Tippy
                                    tag="span"
                                    :content="__('Lead last interaction')"
                                >
                                    <ActivityIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span>
                                    {{
                                        $filters.formatDateTime(
                                            enrollment.updated_at
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
                                    :href="'#'"
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
                                    class="text-primary-11 hover:underline capitalize"
                                >
                                    {{ enrollment.course?.title }}
                                </Link>
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
                            <div
                                v-if="enrollment.payment_approver"
                                class="flex items-center gap-2"
                            >
                                <span class="text-primary-11">
                                    {{ __("Payment approved by") }} :
                                </span>
                                <Link
                                    :href="'#'"
                                    class="text-primary-11 hover:underline"
                                >
                                    {{ enrollment.payment_approver.name }}
                                </Link>
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

export default {
    props: {
        enrollment: Object,
    },
    computed: {
        title() {
            return __("View :resource", {
                resource: __("Enrollment"),
            });
        },
    },
    methods: {
        handleDelete() {
            fireConfirmationModal(() => {
                this.$inertia.delete(
                    route("admin.enrollments.destroy", [this.enrollment.id])
                );
            });
        },
    },
};
</script>
