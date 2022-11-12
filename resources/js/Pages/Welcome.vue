<template>
    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0"
    >
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link
                v-if="$page.props.auth.user"
                :href="route('admin.dashboard')"
                class="text-sm text-gray-700 dark:text-gray-500 underline"
                >{{ __("Administrator") }}</Link
            >

            <Link
                v-if="$page.props.auth.lead"
                :href="route('admin.dashboard')"
                class="text-sm text-gray-700 dark:text-gray-500 underline"
                >{{ __("Dashboard") }}</Link
            >

            <template v-if="!$page.props.auth.lead">
                <Link
                    :href="route('lead.enroll')"
                    class="text-sm text-gray-700 dark:text-gray-500 underline"
                    >{{ __("Enroll") }}</Link
                >
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div
                class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0"
            >
                {{ _appName }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    layout: null,
};
</script>
