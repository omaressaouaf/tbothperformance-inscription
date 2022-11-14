<template>
    <Head :title="__('Enroll')" />

    <div class="mb-10">
        <div class="mx-auto max-w-2xl">
            <div
                class="box pb-8 pt-8 sm:pb-12 sm:pt-10 mt-5 shadow rounded-xl px-5 sm:px-14"
            >
                <h2 class="text-lg mb-3 font-bold">
                    {{ __("Start your enrollment") }}
                </h2>
                <div class="text-gray-600 text-sm font-semibold">
                    {{
                        __(
                            "Send your request and complete your enrollment with ease"
                        )
                    }}
                </div>
                <hr class="mt-6 mb-8" />
                <form @submit.prevent="handleSubmit">
                    <ServerErrors class="mb-4 mt-5" />
                    <div class="grid grid-cols-12 gap-4 gap-y-5">
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label class="form-label"
                                >{{ __("First name") }}
                                <span class="text-theme-21">*</span>
                            </label>
                            <input
                                v-model="form.first_name"
                                type="text"
                                class="form-control"
                                :placeholder="printPlaceholder('first_name')"
                                required
                            />
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label class="form-label">
                                {{ __("Last name") }}
                                <span class="text-theme-21">*</span>
                            </label>
                            <input
                                v-model="form.last_name"
                                type="text"
                                class="form-control"
                                :placeholder="printPlaceholder('last_name')"
                                required
                            />
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label class="form-label">
                                {{ __("Email") }}
                                <span class="text-theme-21">*</span>
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                :placeholder="printPlaceholder('email')"
                                required
                            />
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label class="form-label">
                                {{ __("Phone") }}
                                <span class="text-theme-21">*</span>
                            </label>
                            <input
                                v-model="form.phone"
                                type="text"
                                class="form-control"
                                :placeholder="printPlaceholder('phone')"
                                required
                            />
                        </div>
                        <div class="intro-y col-span-12 mt-2">
                            <label class="form-label flex-shrink-0">
                                {{
                                    __(
                                        "How many years you have worked in france?"
                                    )
                                }}
                                <span class="text-theme-21">*</span>
                            </label>
                            <select
                                v-model="form.years_worked_in_france"
                                class="form-select"
                                required
                            >
                                <option :value="null" selected>
                                    {{ __("Choose an option") }}
                                </option>
                                <option
                                    v-for="(
                                        option, index
                                    ) in yearsWorkedInFrance"
                                    :key="index"
                                    :value="option"
                                >
                                    {{ __(option) }}
                                </option>
                            </select>
                        </div>
                        <div class="intro-y col-span-12">
                            <label class="form-label flex-shrink-0">
                                {{
                                    __(
                                        "What is your current professional situation?"
                                    )
                                }}
                                <span class="text-theme-21">*</span>
                            </label>
                            <select
                                v-model="form.professional_situation"
                                class="form-select"
                                required
                            >
                                <option :value="null" selected>
                                    {{ __("Choose an option") }}
                                </option>
                                <option
                                    v-for="(
                                        situation, index
                                    ) in professionalSituations"
                                    :key="index"
                                    :value="situation"
                                >
                                    {{ __(situation) }}
                                </option>
                            </select>
                        </div>
                        <hr class="my-3 col-span-12" />
                        <div class="intro-y col-span-12">
                            <div class="form-check items-start">
                                <input
                                    v-model="form.terms"
                                    class="form-check-input flex-shrink-0"
                                    type="checkbox"
                                    id="terms"
                                    required
                                />
                                <label class="form-check-label" for="terms">
                                    {{
                                        __(
                                            "Your personal data will be used to process your order, support your experience on this website and for other purposes"
                                        )
                                    }}.
                                </label>
                            </div>
                            <p class="mt-1 ps-4 form-check-label">
                                {{ __("Check") }}
                                <a
                                    href="https://t-bothperformance.com/conditions-generales-de-vente/"
                                    target="_blank"
                                    rel="noopener"
                                    class="text-primary-11 hover:underline"
                                >
                                    {{ __("Terms and conditions") }},
                                </a>
                                <a
                                    href="https://t-bothperformance.com/politique-de-confidentialite/"
                                    target="_blank"
                                    rel="noopener"
                                    class="text-primary-11 hover:underline"
                                >
                                    {{ __("Privacy policy") }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div
                        class="col-span-12 flex items-center flex-col space-y-4 md:space-y-0 md:flex-row justify-center sm:justify-end gap-2 mt-10"
                    >
                        <Link
                            :href="route('lead.passwordless-link')"
                            class="btn btn-secondary font-semibold flex items-center flex-shrink-0"
                        >
                            <UserCheckIcon class="w-4 h-4 me-1" />
                            {{ __("Already filled this form in the past?") }}
                        </Link>
                        <button
                            :disabled="form.processing"
                            class="btn btn-primary flex-shrink-0"
                            type="submit"
                        >
                            <LoadingIcon
                                v-if="form.processing"
                                icon="oval"
                                color="white"
                                class="w-4 h-4 me-2"
                            />
                            {{ __("Launch your enrollment") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: this.$inertia.form({
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
                years_worked_in_france: null,
                professional_situation: null,
                terms: false,
            }),
            yearsWorkedInFrance: [
                "less than 1 year",
                "between 1 and 2 years",
                "more than 3 years",
                "never",
            ],
            professionalSituations: [
                "employee",
                "freelancer",
                "auto entrepreneur",
                "job seeker",
                "entrepreneur",
                "public agent",
                "student",
                "retired",
            ],
        };
    },
    methods: {
        handleSubmit() {
            this.form.post(
                route("lead.store", {
                    courseId: route().params.courseId || undefined,
                })
            );
        },
    },
};
</script>
