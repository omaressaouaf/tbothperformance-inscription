<template>
    <div
        class="relative col-span-12 p-5"
        :class="[
            `mt-${marginTop}`,
            showBackground ? 'bg-white dark:bg-dark-3 rounded-xl' : '',
        ]"
    >
        <div class="absolute start-4 lg:-top-4 flex items-center">
            <div
                class="hidden xl:block bg-primary-1 p-7 me-4 text-gray-100 rounded shadow-md"
            >
                <Component :is="icon" />
            </div>
            <h2 class="hidden xl:block text-xl">{{ __(title) }}</h2>
        </div>
        <slot />
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        icon: String,
        marginTop: {
            type: String,
            default: "16",
        },
        showBackground: {
            type: Boolean,
            default: true,
        },
    },
};
</script>

<style></style>
