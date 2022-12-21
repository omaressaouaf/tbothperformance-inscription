<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title">
        <Link :href="route('admin.leads.index')">{{ __("Leads") }}</Link>
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
                        <div class="space-y-3">
                            <div
                                class="w-16 h-16 sm:w-24 sm:h-24 flex-none image-fit relative"
                            >
                                <svg
                                    class="h-full w-full text-gray-500 bg-gray-100 rounded-full"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('ID')">
                                    <HashIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <Badge class="bg-primary-11 text-xs me-2">
                                    {{ lead.id }}
                                </Badge>
                            </div>
                            <div class="flex items-center">
                                <span class="text-primary-11">
                                    {{ __("First name") }} :
                                </span>
                                <span>
                                    {{ lead.first_name }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-primary-11">
                                    {{ __("Last name") }} :
                                </span>
                                <span>
                                    {{ lead.last_name }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-primary-11">
                                    {{ __("Joined at") }} :
                                </span>
                                <span>
                                    {{
                                        $filters.formatDateTime(lead.created_at)
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
                            {{ __("Contact information") }} {{ __("and") }}
                            {{ __("Career") }}
                        </div>
                        <div class="space-y-3 mt-5">
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Phone')">
                                    <PhoneIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span>
                                    {{ lead.phone }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <Tippy tag="span" :content="__('Email')">
                                    <MailIcon
                                        class="w-5 h-5 me-2 text-primary-11"
                                    />
                                </Tippy>
                                <span>
                                    {{ lead.email }}
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
                                    {{ __(lead.years_worked_in_france) }}
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
                                    {{ __(lead.professional_situation) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="mt-6 lg:mt-0 flex-auto flex items-center justify-center border-s border-e border-t lg:border-t-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0 px-5 gap-5"
                    >
                        <div class="text-center rounded-md py-3">
                            <div class="font-medium text-primary-11 text-xl">
                                {{ lead.enrollments_count }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-500">
                                {{ __("Enrollments") }}
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="nav nav-tabs flex-col py-2 sm:flex-row justify-center sm:justify-start overflow-x-overlay has-cool-scrollbar-sm xl:overflow-x-hidden xl:hover:overflow-x-overlay"
                    role="tablist"
                >
                    <Link
                        :href="route('admin.leads.show', [lead])"
                        class="flex flex-shrink-0 pt-4 pb-3 sm:me-8"
                        :class="{
                            active: !route().params.tab,
                        }"
                        preserveState
                    >
                        <BookmarkIcon class="w-4 h-4 me-2" />
                        {{ __("Enrollments") }}
                    </Link>
                </div>
            </div>
        </CrudWrapper>
        <div class="tab-content mt-5">
            <div class="box">
                <CrudWrapper
                    v-if="!route().params.tab"
                    :title="
                        __(':resources list', { resources: __('Enrollments') })
                    "
                    icon="BookmarkIcon"
                    margin-top="10"
                >
                    <EnrollmentsList :enrollments="lead.enrollments" />
                </CrudWrapper>
            </div>
        </div>
    </div>
</template>

<script>
import { fireConfirmationModal } from "@/helpers";
import EnrollmentsList from "@/Components/Admin/Enrollments/List.vue";

export default {
    components: {
        EnrollmentsList,
    },
    props: {
        lead: Object,
    },
    computed: {
        title() {
            return __("View :resource", {
                resource: __("Lead"),
            });
        },
    },
    provide() {
        return {
            currentLead: this.lead,
        };
    },
    methods: {
        handleDelete() {
            fireConfirmationModal(() => {
                this.$inertia.delete(
                    route("admin.leads.destroy", [this.lead.id])
                );
            });
        },
    },
};
</script>
