<template>
    <div class="breadcrumb me-auto hidden sm:flex mt-5">
        <h2 class="text-xl text-gray-800 dark:text-gray-400 hidden md:block">
            {{ pageTitle }}
        </h2>
        <div class="ms-auto">
            <Link :href="route('admin.dashboard')">{{ __("Dashboard") }}</Link>
            <ChevronRightIcon class="breadcrumb__icon" />
            <slot />
            <a href="#" class="breadcrumb--active">{{ pageTitle }}</a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pageTitle: String,
    },
};
</script>
