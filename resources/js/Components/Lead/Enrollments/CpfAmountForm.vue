<template>
    <form @submit.prevent="handleSubmit">
        <label class="form-label text-lg">
            {{ __("Enter your CPF amount") }}
        </label>
        <div class="w-full flex items-center flex-col md:flex-row gap-2">
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                type="number"
                class="form-control form-control-lg"
                :placeholder="printPlaceholder('cpf_amount')"
                required
                min="0.01"
                step="0.01"
            />
            <button class="btn btn-primary btn-lg" type="submit">
                {{ __("Validate") }}
            </button>
        </div>
    </form>
</template>

<script>
export default {
    props: {
        modelValue: [Number, String],
    },
    methods: {
        handleSubmit() {
            this.$emit("cpfAmountSubmitted");
        },
    },
};
</script>
