<template>
    <EnrollmentsWizard :enrollment="enrollment">
        <div class="grid grid-cols-7 gap-8 items-start">
            <div class="col-span-1 md:col-span-2">
                <CourseFilters :course-categories="courseCategories" />
            </div>
            <div class="col-span-1 md:col-span-5 grid grid-cols-3 gap-4">
                <div
                    v-for="course in courses"
                    :key="course.id"
                    class="col-span-3 md:col-span-2 xl:col-span-1 rounded cursor-pointer shadow hover:shadow-lg hover:scale-105 transition-all duration-200 border mb-3"
                >
                    <div class="w-full h-100 relative">
                        <img
                            :src="course.image_url"
                            :alt="course.title"
                            class="w-full h-full object-cover rounded-t"
                        />
                        <Badge
                            v-if="course.eligible_for_cpf"
                            class="bg-primary-1 text-xs uppercase absolute bottom-2 right-1"
                        >
                            {{ __("CPF") }}
                        </Badge>
                    </div>
                    <div class="p-3">
                        <h4 class="text-lg font-semibold mb-2">
                            {{ course.title }}
                        </h4>
                        <p
                            class="text-xs font-semibold uppercase flex-shrink-0"
                        >
                            <span class=""> {{ __("Certificate") }} : </span>
                            {{ course.certificate }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </EnrollmentsWizard>
</template>

<script>
import EnrollmentsWizard from "@/Components/Lead/Enrollments/Wizard.vue";
import CourseFilters from "@/Components/Lead/Enrollments/CourseFilters.vue";

export default {
    components: {
        EnrollmentsWizard,
        CourseFilters,
    },
    props: {
        enrollment: Object,
        courses: Array,
        courseCategories: Array,
    },
};
</script>
