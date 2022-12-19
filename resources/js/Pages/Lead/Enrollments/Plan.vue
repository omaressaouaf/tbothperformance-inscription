<template>
    <Head :title="__('Choose plan')" />

    <EnrollmentsWizard :enrollment="enrollment">
        <div class="grid grid-cols-3 gap-9">
            <div
                v-for="plan in enrollment.course.plans"
                :key="plan.id"
                class="relative col-span-3 xl:col-span-1 px-7 pt-2 pb-7 border border-gray-200 border-opacity-50 transition-all duration-200 lg:duration-300 rounded-md font-semibold text-lg bg-gray-200 dark:bg-dark-1 text-gray-700 dark:text-gray-200 shadow-md mb-3"
                :class="{
                    'scale-105 shadow-xl': plan.id === form.plan_id,
                }"
            >
                <div class="text-center px-10 py-8">
                    <h3 class="text-xl uppercase">{{ plan.name }}</h3>
                    <h3 class="text-xl lg:text-2xl mt-5">
                        {{ $filters.formatMoney(plan.price) }}
                    </h3>
                </div>
                <div
                    class="text-sm font-semibold space-y-4 flex flex-col items-center"
                >
                    <p
                        v-for="(feature, index) in plan.features"
                        :key="index"
                        class="text-center"
                    >
                        <CheckCircleIcon
                            class="w-4 h-4 me-1 mb-1 text-theme-20"
                        />{{ feature }}
                    </p>
                </div>
                <div class="flex flex-col items-center mt-10 space-y-2">
                    <a
                        v-if="enrollment.financing_type === 'cpf'"
                        :href="plan.pivot.cpf_link"
                        target="_blank"
                        rel="noopener"
                        class="btn bg-theme-39 text-white rounded font-semibold"
                    >
                        {{ __("Simulate my financing") }}
                    </a>
                    <button
                        class="btn btn-primary rounded font-semibold"
                        @click="handleSelectPlan(plan)"
                    >
                        {{
                            plan.id === form.plan_id
                                ? __("Selected")
                                : __("Choose plan")
                        }}
                    </button>
                </div>
            </div>
        </div>
        <div
            v-if="selectedPlan"
            class="grid grid-cols-3 mt-14 place-content-end gap-9"
        >
            <div class="col-span-3 xl:col-span-1 xl:col-start-3 space-y-2">
                <div class="flex items-center justify-between w-full">
                    <div class="font-semibold text-xl">
                        {{ __("Plan price") }}
                    </div>
                    <div class="font-semibold text-lg">
                        {{ $filters.formatMoney(selectedPlan.price) }}
                    </div>
                </div>
                <template v-if="enrollment.financing_type === 'cpf'">
                    <div
                        class="flex items-center justify-between w-full border-b pb-5"
                    >
                        <div class="font-semibold text-xl">
                            {{ __("CPF amount") }}
                        </div>
                        <div class="font-semibold text-lg">
                            {{ $filters.formatMoney(enrollment.cpf_amount) }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between w-full py-5">
                        <div
                            class="font-semibold text-xl"
                            :class="[
                                remainingCharges === 0
                                    ? 'text-theme-20'
                                    : 'text-theme-21',
                            ]"
                        >
                            {{ __("Remaining charges") }}
                        </div>
                        <div
                            class="font-semibold text-lg"
                            :class="[
                                remainingCharges === 0
                                    ? 'text-theme-20'
                                    : 'text-theme-21',
                            ]"
                        >
                            {{ $filters.formatMoney(remainingCharges) }}
                        </div>
                    </div>
                </template>
                <form
                    @submit.prevent="handleSubmit"
                    class="w-full mt-5 border-t pt-5 pb-2"
                >
                    <ServerErrors class="mb-4 mt-5" />
                    <label class="form-label text-lg font-semibold">
                        {{ __("Course start date") }}
                    </label>
                    <input
                        v-model="form.start_date"
                        type="date"
                        class="form-control form-control-lg"
                        required
                        :min="$filters.formatDateForInput(courseStartDateMin)"
                    />
                    <div class="form-help mt-2">
                        <p>
                            {{ __("The start date should be after") }}
                            {{ $filters.formatDate(courseStartDateMin) }}.
                        </p>
                        <p v-if="enrollment.financing_type === 'cpf'">
                            {{
                                __(
                                    "The law imposes a period of 14 days between registration and the start of training"
                                )
                            }}.
                        </p>
                    </div>
                    <button
                        class="btn btn-primary btn-lg w-full mt-4"
                        type="submit"
                    >
                        {{ __("Next") }}
                    </button>
                </form>
            </div>
        </div>
    </EnrollmentsWizard>
</template>

<script>
import EnrollmentsWizard from "@/Components/Lead/Enrollments/Wizard.vue";

export default {
    components: {
        EnrollmentsWizard,
    },
    props: {
        enrollment: Object,
        courseStartDateMin: String,
    },
    data() {
        return {
            form: this.$inertia.form({
                plan_id: this.enrollment.plan_id,
                start_date:
                    this.enrollment.start_date ||
                    this.$filters.formatDateForInput(this.courseStartDateMin),
            }),
        };
    },
    computed: {
        selectedPlan() {
            return this.enrollment.course.plans.find(
                (plan) => plan.id == this.form.plan_id
            );
        },
        remainingCharges() {
            const selectedPlanPrice = Number(this.selectedPlan.price);
            const cpfAmount = Number(this.enrollment.cpf_amount);

            if (selectedPlanPrice <= cpfAmount) return 0;

            return selectedPlanPrice - cpfAmount;
        },
        recommendedPlan() {
            if (this.enrollment.financing_type === "manual") return null;

            const absoluteDiffBetweenPlansPricesAndCpfAmount =
                this.enrollment.course.plans.map((plan) => {
                    return Math.abs(plan.price - this.enrollment.cpf_amount);
                });

            const closestPlanPriceDiff = Math.min(
                ...absoluteDiffBetweenPlansPricesAndCpfAmount
            );

            return this.enrollment.course.plans.find(
                (plan, index) =>
                    index ==
                    absoluteDiffBetweenPlansPricesAndCpfAmount.indexOf(
                        closestPlanPriceDiff
                    )
            );
        },
    },
    methods: {
        handleSelectPlan(plan) {
            this.form.plan_id = plan.id;
        },
        handleSubmit() {
            this.form.patch(
                route("lead.enrollments.plan.update", [this.enrollment]),
                {
                    preserveScroll: true,
                }
            );
        },
    },
    mounted() {
        if (!this.enrollment.plan_id) {
            this.form.plan_id = this.recommendedPlan?.id;
        }
    },
};
</script>
