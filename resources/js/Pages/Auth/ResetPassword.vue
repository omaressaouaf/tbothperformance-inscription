<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.update"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head :title="__('Reset Password')" />

    <ServerErrors class="mb-4 mt-5" />

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

        <div class="mt-4">
            <label class="form-label">{{ __("New Password") }}</label>
            <input
                type="password"
                class="form-control py-3 px-4"
                :placeholder="printPlaceholder('password')"
                v-model="form.password"
                required
                autocomplete="new-password"
            />
        </div>

        <div class="mt-4">
            <label class="form-label">{{ __("Confirm Password") }}</label>
            <input
                type="password"
                class="form-control py-3 px-4"
                :placeholder="printPlaceholder('password')"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button :disabled="form.processing" class="btn btn-primary">
                {{ __("Reset Password") }}
            </button>
        </div>
    </form>
</template>
