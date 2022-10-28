<template>
    <Head title="Dashboard" />
    
    <Breadcrumb :page-title="__('Dashboard')" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __("Welcome Back!") }}
                    {{ $page.props.auth.user.name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
};
</script>
