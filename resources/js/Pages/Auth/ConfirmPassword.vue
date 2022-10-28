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
            <InputLabel for="password" :value="__('Password')" />
            <TextInput
                id="password"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password"
                required
                autocomplete="current-password"
                autofocus
            />
        </div>

        <div class="flex justify-end mt-4">
            <PrimaryButton
                class="ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ __("Confirm") }}
            </PrimaryButton>
        </div>
    </form>
</template>
