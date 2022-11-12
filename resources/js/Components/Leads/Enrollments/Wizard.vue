<template>
    <div class="box py-10 sm:py-20 mt-5 shadow rounded-xl">
        <div
            class="wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20"
        >
            <div
                class="intro-x lg:text-center flex items-center lg:block flex-1 z-10"
            >
                <button class="w-10 h-10 rounded-full btn btn-primary">
                    1
                </button>
                <div
                    class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"
                >
                    {{ __("Personal information") }}
                </div>
            </div>
            <div
                class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
            >
                <button
                    class="w-10 h-10 rounded-full btn text-gray-600 bg-gray-200 dark:bg-dark-1"
                >
                    2
                </button>
                <div
                    class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700 dark:text-gray-600"
                >
                    {{ __("The course") }}
                </div>
            </div>
            <div
                class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
            >
                <button
                    class="w-10 h-10 rounded-full btn text-gray-600 bg-gray-200 dark:bg-dark-1"
                >
                    3
                </button>
                <div
                    class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700 dark:text-gray-600"
                >
                    {{ __("CPF") }} {{ __("and") }} {{ __("Financing") }}
                </div>
            </div>
            <div
                class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
            >
                <button
                    class="w-10 h-10 rounded-full btn text-gray-600 bg-gray-200 dark:bg-dark-1"
                >
                    4
                </button>
                <div
                    class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700 dark:text-gray-600"
                >
                    {{ __("Validation") }}
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
            <div
                class="col-span-12 flex items-center justify-center sm:justify-end mt-10"
            >
                <button class="btn btn-secondary w-24">Previous</button>
                <button class="btn btn-primary w-24 ml-2">Next</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>
