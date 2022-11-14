<template>
    <Head :title="__('Enroll')" />

    <div class="mb-10">
        <div class="mx-auto max-w-2xl">
            <div
                class="box pb-8 pt-8 sm:pb-12 sm:pt-10 mt-5 shadow rounded-xl px-5 sm:px-14"
            >
                <h2 class="text-lg mb-3 font-bold">
                    {{ __("Passwordless login") }}
                </h2>
                <div class="text-gray-600 text-sm font-semibold">
                    {{
                        __(
                            "Enter your email if you already filled the enrollment form. we are gonna send you a quick login link so you can check your enrollments, support tickets and more ..."
                        )
                    }}
                </div>
                <hr class="mt-6 mb-8" />
                <form @submit.prevent="handleSubmit">
                    <ServerErrors class="mb-4 mt-5" />
                    <div class="grid grid-cols-12 gap-4 gap-y-5">
                        <div class="intro-y col-span-12">
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
                    </div>

                    <div
                        class="col-span-12 flex items-center flex-col space-y-4 md:space-y-0 md:flex-row justify-center sm:justify-end gap-2 mt-10"
                    >
                        <Link
                            :href="route('lead.enroll')"
                            class="btn btn-secondary font-semibold flex items-center flex-shrink-0"
                        >
                            <UserPlusIcon class="w-4 h-4 me-1" />
                            {{ __("Create new request") }}
                        </Link>
                        <button
                            :disabled="form.processing"
                            class="btn btn-primary flex-shrink-0 mt-5"
                            type="submit"
                        >
                            <LoadingIcon
                                v-if="form.processing"
                                icon="oval"
                                color="white"
                                class="w-4 h-4 me-2"
                            />
                            {{ __("Send login link") }}
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
                email: "",
            }),
        };
    },
    methods: {
        handleSubmit() {
            this.form.post(route("lead.passwordless-link.send"), {
                onSuccess: () => this.form.reset(),
            });
        },
    },
};
</script>
