<template>
    <Head :title="__('Validate your enrollment')" />
    <EnrollmentsWizard :enrollment="enrollment">
        <ServerErrors class="mb-4 mt-5" />
        <div
            v-if="enrollment.financing_type === 'cpf'"
            class="flex flex-col items-center justify-center"
        >
            <h2
                class="text-xl md:text-3xl font-semibold text-green-600 text-center"
            >
                <CheckCircleIcon class="w-8 h-8 me-1" />
                {{
                    __("Complete your enrollment in moncompteformation.gouv.fr")
                }}
            </h2>
            <p
                class="text-base text-center text-gray-700 dark:text-gray-300 font-semibold mt-2"
            >
                {{
                    __(
                        "Congrats. you have completed the first step of your enrollment. all is left is to enroll in moncompteformation.gouv.fr and come back and enter your dossier number"
                    )
                }}
            </p>
            <div class="flex items-center flex-col md:flex-row gap-2 mt-7">
                <button
                    @click="wantsToValidateWithDossierNumber = true"
                    class="btn btn-lg"
                    :class="[
                        wantsToValidateWithDossierNumber === true
                            ? 'btn-primary'
                            : 'btn-outline-primary',
                    ]"
                    type="button"
                >
                    {{ __("Show me how to find my dossier number") }}
                </button>
                <button
                    @click="
                        wantsToValidateWithDossierNumber = false;
                        form.cpf_dossier_number = '';
                    "
                    class="btn btn-lg"
                    :class="[
                        wantsToValidateWithDossierNumber === false
                            ? 'btn-primary'
                            : 'btn-outline-primary',
                    ]"
                    type="button"
                >
                    {{ __("Validate my enrollment without dossier number") }}
                </button>
            </div>
            <div
                v-if="wantsToValidateWithDossierNumber"
                class="w-full flex items-center justify-center flex-col gap-2 mt-10"
            >
                <div class="w-full md:w-3/4 intro-y">
                    <ol
                        class="relative border-l border-gray-500 dark:border-gray-600 w-full"
                    >
                        <li class="mb-10 ml-4 rounded-lg border shadow-sm p-5">
                            <div
                                class="absolute w-3 h-3 bg-gray-500 dark:bg-gray-600 rounded-full mt-1.5 -left-1.5"
                            ></div>
                            <time
                                class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"
                            ></time>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Step") }} 1
                            </h3>
                            <div
                                class="flex items-start justify-between flex-col md:flex-row space-y-7 md:space-y-0 mt-5 text-lg"
                            >
                                <div>
                                    <p class="text-base font-semibold">
                                        {{ __("Follow this enrollment link") }}
                                    </p>
                                    <a
                                        class="text-white bg-blue-600 btn-sm md:btn md:btn-lg underline mt-2"
                                        :href="enrollment.cpf_link"
                                        target="_blank"
                                        rel="noopener"
                                        >{{ __("Enrollment link") }}</a
                                    >
                                </div>
                            </div>
                        </li>
                        <li class="mb-10 ml-4 rounded-lg border shadow-sm p-5">
                            <div
                                class="absolute w-3 h-3 bg-gray-500 dark:bg-gray-600 rounded-full mt-1.5 -left-1.5"
                            ></div>
                            <time
                                class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"
                            ></time>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Step") }} 2
                            </h3>
                            <div class="space-y-5 mt-5 text-lg">
                                <p class="text-base font-semibold">
                                    {{
                                        __(
                                            "Once you complete your enrollment in moncompteformation.gouv.fr. you will find your enrollment dossier number in 'Mes dossiers'"
                                        )
                                    }}
                                </p>
                            </div>
                        </li>
                        <li class="mb-10 ml-4 rounded-lg border shadow-sm p-5">
                            <div
                                class="absolute w-3 h-3 bg-gray-500 dark:bg-gray-600 rounded-full mt-1.5 -left-1.5"
                            ></div>
                            <time
                                class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"
                            ></time>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Step") }} 3
                            </h3>
                            <div class="space-y-5 mt-5 text-lg">
                                <p
                                    class="alert alert-secondary show text-base font-semibold mb-5"
                                >
                                    <InfoIcon class="w-4 h-4 me-1" />
                                    {{
                                        __(
                                            "Return to this page after consulting your dossier number"
                                        )
                                    }}
                                </p>
                                <div class="md:p-5">
                                    <label class="form-label text-lg">
                                        {{ __("Enter your dossier number") }}
                                    </label>
                                    <input
                                        v-model="form.cpf_dossier_number"
                                        class="form-control form-control-lg"
                                        :placeholder="
                                            printPlaceholder(
                                                'cpf_dossier_number'
                                            )
                                        "
                                    />
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="mt-7 w-full md:w-3/4 intro-y">
                <div>
                    <label class="form-label text-lg">
                        {{ __("Notes") }}
                    </label>
                    <textarea
                        class="form-control"
                        v-model="form.notes"
                        rows="5"
                        :placeholder="printPlaceholder('notes')"
                    ></textarea>
                </div>
                <button
                    @click="handleSubmit"
                    :disabled="form.processing"
                    class="btn btn-lg btn-primary w-full mt-5"
                >
                    {{ __("Validate") }}
                </button>
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
    },
    data() {
        return {
            wantsToValidateWithDossierNumber: true,
            form: this.$inertia.form({
                cpf_dossier_number: this.enrollment.cpf_dossier_number,
                notes: this.enrollment.notes,
            }),
        };
    },
    methods: {
        handleSubmit() {
            this.form.patch(
                route("lead.enrollments.validation.update", [this.enrollment])
            );
        },
    },
};
</script>
