<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head :title="__('Forgot Password')" />

    <div class="mb-4 text-sm text-gray-600">
        {{
            __(
                "Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one."
            )
        }}
    </div>

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

        <div class="flex items-center justify-end mt-4">
            <button :disabled="form.processing" class="btn btn-primary">
                  {{ __("Email Password Reset Link") }}
            </button>
        </div>
    </form>
</template>
