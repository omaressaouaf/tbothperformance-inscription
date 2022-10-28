<template>
    <header class="bg-gradient-to-r from-purple-800 to-indigo-600 shadow mt-0.5">
        <div
            class="max-w-7xl mx-auto flex justify-between py-6 px-4 sm:px-6 lg:px-8"
        >
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ pageTitle }}
            </h2>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        pageTitle: String,
    },
};
</script>
