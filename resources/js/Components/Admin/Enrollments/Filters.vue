<template>
    <form @submit.prevent="applyFilters" class="space-y-6">
        <div class="grid grid-cols-4 gap-5">
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Number") }} </label>
                <input
                    :placeholder="printPlaceholder('number')"
                    class="form-control"
                    v-model="filter.number"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Lead") }} </label>
                <input
                    :placeholder="printPlaceholder('lead')"
                    class="form-control"
                    v-model="filter.lead_data"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Course") }} </label>
                <input
                    :placeholder="printPlaceholder('title')"
                    class="form-control"
                    v-model="filter['course.title']"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Plan") }} </label>
                <input
                    :placeholder="printPlaceholder('name')"
                    class="form-control"
                    v-model="filter['plans.name']"
                />
            </div>
        </div>
        <div class="grid grid-cols-4 gap-5">
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Financing") }} </label>
                <select v-model="filter.financing_type" class="form-select">
                    <option :value="null" selected>
                        {{ __("Choose an option") }}
                    </option>
                    <option value="cpf">
                        {{ __("CPF") }}
                    </option>
                    <option value="manual" class="capitalize">
                        {{ __("manual") }}
                    </option>
                </select>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Status") }} </label>
                <select v-model="filter.status" class="form-select">
                    <option :value="null" selected>
                        {{ __("Choose an option") }}
                    </option>
                    <option
                        v-for="(status, index) in [
                            'pending',
                            'contract signed',
                            'complete',
                            'canceled',
                        ]"
                        :key="index"
                        :value="status"
                        class="capitalize"
                    >
                        {{ __(status) }}
                    </option>
                </select>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Started at") }} </label>
                <input
                    :placeholder="printPlaceholder('created_at')"
                    class="form-control"
                    v-model="filter.created_at"
                    type="date"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Finished at") }} </label>
                <input
                    :placeholder="printPlaceholder('completed_at')"
                    class="form-control"
                    v-model="filter.completed_at"
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
                number: "",
                lead_data: "",
                "course.title": "",
                "plan.name": "",
                created_at: "",
                completed_at: "",
                financing_type: null,
                status: null,
            }),
        };
    },
};
</script>
