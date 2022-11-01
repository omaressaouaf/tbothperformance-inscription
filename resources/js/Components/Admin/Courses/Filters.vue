<template>
    <form @submit.prevent="applyFilters" class="space-y-6">
        <div class="grid grid-cols-4 gap-5">
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Title") }} </label>
                <input
                    :placeholder="printPlaceholder('title')"
                    class="form-control"
                    v-model="filter.title"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Certificate") }} </label>
                <input
                    :placeholder="printPlaceholder('certificate')"
                    class="form-control"
                    v-model="filter.certificate"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Category") }} </label>
                <input
                    :placeholder="printPlaceholder('category')"
                    class="form-control"
                    v-model="filter['category.name']"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Plan") }} </label>
                <input
                    :placeholder="printPlaceholder('plan')"
                    class="form-control"
                    v-model="filter['plans.name']"
                />
            </div>
        </div>
        <div class="grid grid-cols-4 gap-5">
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Eligible for CPF") }} </label>
                <select v-model="filter.eligible_for_cpf" class="form-select">
                    <option :value="null" selected>
                        {{ __("Choose an option") }}
                    </option>
                    <option :value="1" selected>
                        {{ __("Yes") }}
                    </option>
                    <option :value="0" selected>
                        {{ __("No") }}
                    </option>
                </select>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Creation date") }} </label>
                <input
                    :placeholder="printPlaceholder('created_at')"
                    class="form-control"
                    v-model="filter.created_at"
                    type="date"
                />
            </div>
        </div>
        <div class="flex items-start gap-2">
            <button type="submit" class="btn btn-primary">
                <FilterIcon class="w-4 h-4 me-2" />
                {{ __("Apply") }}
            </button>
            <button
                @click="resetFilters"
                type="button"
                class="btn btn-outline-secondary"
            >
                <XIcon class="w-4 h-4 me-2" />
                {{ __("Reset") }}
            </button>
        </div>
    </form>
</template>

<script>
import filters from "@/mixins/filters";
import VForm from "@/libs/vform";

export default {
    mixins: [filters],
    data() {
        return {
            filter: new VForm({
                title: "",
                certificate: "",
                "category.name": "",
                "plans.name": "",
                eligible_for_cpf: null,
                created_at: "",
            }),
        };
    },
};
</script>
