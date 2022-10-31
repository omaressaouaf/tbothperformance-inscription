<template>
    <div v-if="hasErrors">
        <div class="font-medium text-primary-3">
            {{ __("Whoops! Something went wrong.") }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm text-primary-3">
            <li v-for="(error, key) in errors" :key="key">
                {{ Array.isArray(error) ? error[0] : error }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        serverErrors: Object,
    },
    computed: {
        errors() {
            return this.serverErrors || this.$page.props.errors;
        },
        hasErrors() {
            return Object.keys(this.errors).length > 0;
        },
    },
};
</script>
