<template>
    <EnrollmentsWizard :enrollment="enrollment"> </EnrollmentsWizard>
</template>

<script>
import EnrollmentsWizard from "@/Components/Lead/Enrollments/Wizard.vue";

export default {
    components: {
        EnrollmentsWizard,
    },
    props: {
        enrollment: Object,
    },
};
</script>
