<template>
    <Head :title="title" />
    <Breadcrumb :page-title="title" />
    <CrudWrapper
        :title="__(':resources list', { resources: __('Enrollments') })"
        icon="BookmarkIcon"
    >
        <EnrollmentsList :enrollments="enrollments" />
    </CrudWrapper>
</template>

<script>
import EnrollmentsList from "@/Components/Admin/Enrollments/List.vue";

export default {
    components: {
        EnrollmentsList,
    },
    props: {
        enrollments: Object,
    },
    computed: {
        title() {
            return __(":data Management", { data: __("Enrollments") });
        },
    },
};
</script>
