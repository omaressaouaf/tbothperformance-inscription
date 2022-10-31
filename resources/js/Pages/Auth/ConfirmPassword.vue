<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

const form = useForm({
    password: "",
});

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head :title="__('Confirm Password')" />

    <div class="mb-4 text-sm text-gray-600">
        {{
            __(
                "This is a secure area of the application. Please confirm your password before continuing."
            )
        }}
    </div>

    <ServerErrors class="mb-4 mt-5" />

    <form @submit.prevent="submit">
        <div>
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

        <div class="flex justify-end mt-4">
            <button :disabled="form.processing" class="btn btn-primary">
                {{ __("Confirm") }}
            </button>
        </div>
    </form>
</template>
