<template>
    <div class="box pt-5 sm:pt-14 mt-5 shadow rounded-xl mb-10">
        <div
            class="text-gray-700 dark:text-gray-300 text-center text-3xl mb-10 px-2 md:px-0"
        >
            {{ __("Complete your enrollment") }}
            <p v-if="enrollment.course" class="text-base mt-5">
                <span class="font-semibold text-primary-11"
                    >{{ __("Course") }} :
                </span>
                {{ enrollment.course.title }}
            </p>
            <p class="text-sm mt-1">
                <span class="font-semibold text-primary-11"
                    >{{ __("Enrollment No.") }}
                </span>
                {{ enrollment.id }}
            </p>
        </div>
        <div
            v-if="$page.props.flash.enrollmentSuccessMessage"
            class="mx-4 md:mx-16 2xl:mx-48"
        >
            <LeadLoggedInAlert />
        </div>
        <div
            class="wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20"
        >
            <div
                v-for="(step, index) in steps"
                class="lg:text-center flex items-center lg:block flex-1 z-10"
            >
                <Component
                    class="w-10 h-10 rounded-full btn"
                    :class="[
                        index + 1 > currentStep
                            ? 'btn-secondary'
                            : 'btn-primary',
                    ]"
                    :is="index + 1 > enrollment.next_step ? 'button' : 'Link'"
                    :href="step.url"
                    :disabled="index + 1 > enrollment.next_step"
                >
                    {{ index + 1 }}
                </Component>
                <div
                    class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"
                >
                    {{ __(step.title) }}
                </div>
            </div>
            <div
                class="wizard__line hidden lg:block w-2/3 bg-gray-200 dark:bg-dark-1 absolute mt-5"
            ></div>
        </div>
        <div
            class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5"
        >
            <slot />
        </div>
        <div
            class="self-end bg-gray-100 dark:bg-dark-2 border border-gray-300 dark:border-dark-2 mt-20 px-10 py-8"
        >
            <div
                class="flex items-center flex-col md:flex-row space-y-8 md:space-y-0 justify-between"
            >
                <img
                    src="/images/logo-qualiopi.png"
                    alt="qualiopi"
                    class="w-auto h-20"
                />
                <img
                    src="/images/logo-mcpf.png"
                    alt="moncompteformation"
                    class="w-auto h-20"
                />
                <img
                    src="/images/logo-fc.svg"
                    alt="franceconnect"
                    class="w-auto h-20"
                />
            </div>
        </div>
    </div>
</template>

<script>
import LeadLoggedInAlert from "@/Components/Lead/LoggedInAlert.vue";

export default {
    components: {
        LeadLoggedInAlert,
    },
    props: {
        enrollment: Object,
    },
    data() {
        return {
            steps: [
                {
                    title: "The course",
                    url: route("lead.enrollments.course.edit", [
                        this.enrollment.id,
                    ]),
                },
                {
                    title: "CPF and Financing",
                    url: route("lead.enrollments.financing.edit", [
                        this.enrollment.id,
                    ]),
                },
                {
                    title: "Validation",
                    url: route("lead.enrollments.validation.edit", [
                        this.enrollment.id,
                    ]),
                },
            ],
        };
    },
    computed: {
        currentStep() {
            if (route().current("lead.enrollments.validation.edit")) {
                return 3;
            }

            if (route().current("lead.enrollments.financing.edit")) {
                return 2;
            }

            return 1;
        },
    },
};
</script>
