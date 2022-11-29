<template>
    <Head :title="__('Choose course')" />

    <EnrollmentsWizard :enrollment="enrollment">
        <div class="grid grid-cols-7 gap-8 items-start">
            <div class="col-span-7 lg:col-span-2 intro-y">
                <CourseFilters :course-categories="courseCategories" />
            </div>
            <div class="col-span-7 lg:col-span-5 grid grid-cols-3 gap-4">
                <div
                    v-for="course in courses"
                    :key="course.id"
                    class="col-span-3 lg:col-span-1 dark:bg-dark-2 rounded-md cursor-pointer shadow dark:shadow-none hover:shadow-xl hover:scale-105 transition-all duration-200 border mb-3 flex flex-col"
                    :class="{
                        'shadow-xl scale-105':
                            enrollment.course_id == course.id,
                    }"
                    @click="handleSelectCourse(course)"
                >
                    <div class="w-full h-100 relative">
                        <img
                            :src="course.image_url"
                            :alt="course.title"
                            class="w-full h-full object-cover rounded-t-md"
                        />
                        <Badge
                            v-if="course.eligible_for_cpf"
                            class="bg-primary-1 text-xs uppercase absolute bottom-2 right-1"
                        >
                            {{ __("CPF") }}
                        </Badge>
                    </div>
                    <div class="p-3">
                        <p class="font-semibold text-base mb-2">
                            {{ course.title }}
                        </p>
                        <p
                            class="text-xs font-semibold uppercase flex-shrink-0"
                        >
                            <span class=""> {{ __("Certificate") }} : </span>
                            {{ course.certificate }}
                        </p>
                    </div>
                    <div
                        v-if="enrollment.course_id == course.id"
                        class="text-right p-3 mt-auto text-theme-20 text-xs"
                    >
                        <CheckCircleIcon class="w-4 h-4 me-1" />{{
                            __("Selected")
                        }}
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
    methods: {
        handleSelectCourse(course) {
            const form = this.$inertia.form({ course_id: course.id });

            form.patch(
                route("lead.enrollments.course.update", [this.enrollment])
            );
        },
    },
};
</script>
