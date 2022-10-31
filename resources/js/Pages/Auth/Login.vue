<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head :title="__('Log in')" />

    <ServerErrors class="mb-4 mt-5" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <label class="form-label">{{ __("Email") }}</label>
            <input
                type="email"
                class="form-control py-3 px-4"
                :placeholder="printPlaceholder('email')"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
            />
        </div>

        <div class="mt-5">
            <label class="form-label">{{ __("Password") }}</label>
            <input
                type="password"
                class="form-control py-3 px-4"
                :placeholder="printPlaceholder('password')"
                v-model="form.password"
                required
                autocomplete="current-password"
            />
        </div>

        <div class="block mt-4">
            <label class="flex items-center">
                <input
                    id="remember-me"
                    type="checkbox"
                    v-model="form.remember"
                    class="form-check-input me-2"
                />
                <span class="ml-2 text-sm text-gray-600">{{
                    __("Remember me")
                }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="underline text-sm text-gray-600"
            >
                {{ __("Forgot your password?") }}
            </Link>

            <button
                :disabled="form.processing"
                class="btn btn-primary"
            >
                {{ __("Login") }}
            </button>
        </div>
    </form>
</template>
