<template>
    <Head :title="__('Dashboard')" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-2">
            <div class="col-span-2 xl:col-span-1">
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
        </div>
    </div>
</template>

<script>
import LeadCreateEdit from "@/Components/Lead/CreateEdit.vue";

export default {
    components: {
        LeadCreateEdit,
    },
};
</script>
