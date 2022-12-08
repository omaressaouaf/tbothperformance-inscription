<template>
    <Head :title="__('Dashboard')" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <LeadLoggedInAlert />
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-7 gap-5">
            <div class="col-span-7 xl:col-span-3">
                <h2
                    class="font-semibold text-xl mb-6 flex items-center px-2 text-gray-700 dark:text-gray-200"
                >
                    <UserIcon class="w-6 h-6 me-2" />
                    <span>
                        {{ __("Personal information") }}
                    </span>
                </h2>
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white dark:bg-dark-3 border-b border-gray-200"
                >
                    <LeadCreateEdit :lead="$page.props.auth.lead" />
                </div>
            </div>
            <div class="col-span-7 xl:col-span-4">
                <h2
                    class="font-semibold text-xl mb-6 flex items-center px-2 text-gray-700 dark:text-gray-200"
                >
                    <BookmarkIcon class="w-6 h-6 me-2" />
                    <span>
                        {{ __("Enrollments") }}
                    </span>
                </h2>
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white dark:bg-dark-3 border-b border-gray-200"
                >
                    <EnrollmentsList
                        :pending-enrollment="pendingEnrollment"
                        :enrollments="enrollments"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LeadCreateEdit from "@/Components/Lead/CreateEdit.vue";
import EnrollmentsList from "@/Components/Lead/Enrollments/List.vue";
import LeadLoggedInAlert from "@/Components/Lead/LoggedInAlert.vue";

export default {
    components: {
        LeadCreateEdit,
        EnrollmentsList,
        LeadLoggedInAlert,
    },
    props: {
        pendingEnrollment: Object,
        enrollments: Array,
    },
};
</script>
