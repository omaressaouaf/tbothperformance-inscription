<template>
    <Head :title="__('Choose financing')" />
    <EnrollmentsWizard :enrollment="enrollment">
        <div
            v-if="enrollment.course.eligible_for_cpf"
            class="flex flex-col items-center justify-center"
        >
            <h2
                class="text-xl md:text-3xl font-semibold text-primary-11 text-center"
            >
                <AwardIcon class="w-8 h-8 me-1" />
                {{ __("Do you know the amount of your training rights ?") }}
            </h2>
            <p
                class="text-base text-gray-700 dark:text-gray-300 font-semibold mt-2"
            >
                {{
                    __(
                        "If you have worked in France, you necessarily have one, even without knowing it..."
                    )
                }}
            </p>
            <div class="flex items-center flex-col md:flex-row gap-2 mt-7">
                <button
                    @click="knowsCpfAmount = true"
                    class="btn btn-lg"
                    :class="[
                        knowsCpfAmount === true
                            ? 'btn-primary'
                            : 'btn-outline-primary',
                    ]"
                    type="button"
                >
                    <UserCheckIcon class="w-6 h-6 me-2" />
                    {{ __("Yes. i know my amount") }}
                </button>
                <button
                    @click="knowsCpfAmount = false"
                    class="btn btn-lg"
                    :class="[
                        knowsCpfAmount === false
                            ? 'btn-primary'
                            : 'btn-outline-primary',
                    ]"
                    type="button"
                >
                    <HelpCircleIcon class="w-6 h-6 me-2" />
                    {{ __("No. show me how") }}
                </button>
            </div>
            <div
                class="w-full flex items-center justify-center flex-col gap-2 mt-10"
            >
                <div
                    v-if="knowsCpfAmount === false"
                    class="w-full md:w-3/4 intro-y"
                >
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
                                        {{
                                            __(
                                                "Create your account in the government site"
                                            )
                                        }}
                                    </p>
                                    <a
                                        class="text-white bg-blue-600 btn-sm md:btn md:btn-lg underline mt-2"
                                        href="https://www.moncompteformation.gouv.fr/"
                                        target="_blank"
                                        rel="noopener"
                                        >moncompteformation.gouv.fr</a
                                    >
                                    <p class="text-xs mt-2 font-semibold">
                                        {{
                                            __(
                                                "I already have an account in moncompteformation.gouv.fr"
                                            )
                                        }}.
                                        <a
                                            class="underline text-primary-11"
                                            href="https://www.moncompteformation.gouv.fr/espace-prive/html/#/droits"
                                        >
                                            {{ __("Log in") }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-base font-semibold">
                                        {{
                                            __("Or connect with FranceConnect")
                                        }}
                                    </p>
                                    <a
                                        class="text-white underline"
                                        href="https://franceconnect.gouv.fr/"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        <img
                                            src="/images/logo-fc.svg"
                                            alt="franceconnect"
                                            class="w-auto h-16 mt-1"
                                        />
                                    </a>
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
                                            "Once logged in, follow the steps in this video to get your CPF amount"
                                        )
                                    }}
                                </p>
                                <iframe
                                    width="760"
                                    style="max-width: 100%"
                                    height="315"
                                    src="https://www.youtube.com/embed/j4ED4Xl5wTQ"
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen=""
                                ></iframe>
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
                                            "Return to this page after consulting your rights"
                                        )
                                    }}
                                </p>
                                <div
                                    id="cpf-accordion"
                                    class="accordion accordion-boxed"
                                >
                                    <div class="accordion-item">
                                        <div
                                            id="cpf-accordion-header-1"
                                            class="accordion-header"
                                        >
                                            <button
                                                class="accordion-button text-lg !font-semibold !text-gray-800 dark:!text-gray-200"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#cpf-accordion-content-1"
                                                aria-expanded="true"
                                                aria-controls="cpf-accordion-content-1"
                                            >
                                                <ChevronRightIcon
                                                    class="w-4 h-4 me-1"
                                                />{{
                                                    __(
                                                        "If you found some CPF amount"
                                                    )
                                                }}
                                            </button>
                                        </div>
                                        <div
                                            id="cpf-accordion-content-1"
                                            class="accordion-collapse collapse show"
                                            style="visibility: inherit"
                                            aria-labelledby="cpf-accordion-header-1"
                                            data-bs-parent="#cpf-accordion"
                                        >
                                            <div class="accordion-body md:p-5">
                                                <EnrollmentsCpfAmountForm
                                                    v-model="form.cpf_amount"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <div
                                            id="cpf-accordion-header-1"
                                            class="accordion-header"
                                        >
                                            <button
                                                class="accordion-button collapsed text-lg !font-semibold !text-gray-800 dark:!text-gray-200"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#cpf-accordion-content-1"
                                                aria-expanded="true"
                                                aria-controls="cpf-accordion-content-1"
                                            >
                                                <ChevronRightIcon
                                                    class="w-4 h-4 me-1"
                                                />{{ __("Otherwise") }}
                                            </button>
                                        </div>
                                        <div
                                            id="cpf-accordion-content-1"
                                            class="accordion-collapse collapse"
                                            style="visibility: inherit"
                                            aria-labelledby="cpf-accordion-header-1"
                                            data-bs-parent="#cpf-accordion"
                                        >
                                            <div class="accordion-body md:p-5">
                                                <button
                                                    class="btn btn-secondary btn-lg flex items-start md:block"
                                                    type="button"
                                                >
                                                    <CreditCardIcon
                                                        class="w-6 h-6 me-2 mt-1 md:mt-0"
                                                    />
                                                    {{
                                                        __(
                                                            "Finance the course on your own"
                                                        )
                                                    }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>

                <div
                    v-if="knowsCpfAmount === true"
                    class="intro-y md:w-1/2 mt-5"
                >
                    <EnrollmentsCpfAmountForm v-model="form.cpf_amount" />
                </div>
            </div>

            <div class="flex items-center justify-center mt-14 border-t pt-8">
                <button
                    class="btn btn-secondary btn-lg flex items-start md:block"
                    type="button"
                >
                    <CreditCardIcon class="w-6 h-6 me-2 mt-1 md:mt-0" />
                    {{ __("Finance the course myself") }}
                </button>
            </div>
        </div>
        <div v-else class="flex flex-col items-center justify-center">
            <div class="alert alert-secondary show font-semibold text-lg mt-5">
                {{ __("This course is not eligible for CPF") }}
            </div>
            <button
                class="btn btn-primary btn-lg flex items-start md:block mt-5"
                type="button"
            >
                <CreditCardIcon class="w-6 h-6 me-2 mt-1 md:mt-0" />
                {{ __("Finance the course on your own") }}
            </button>
        </div>
    </EnrollmentsWizard>
</template>

<script>
import EnrollmentsWizard from "@/Components/Lead/Enrollments/Wizard.vue";
import EnrollmentsCpfAmountForm from "@/Components/Lead/Enrollments/CpfAmountForm.vue";

export default {
    components: {
        EnrollmentsWizard,
        EnrollmentsCpfAmountForm,
    },
    props: {
        enrollment: Object,
    },
    data() {
        return {
            knowsCpfAmount: null,
            form: {
                financing_type: null,
                cpf_amount: "",
            },
        };
    },
};
</script>
