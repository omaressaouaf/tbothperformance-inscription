<template>
    <form @submit.prevent="applyFilters" class="space-y-6">
        <div class="grid grid-cols-4 gap-5">
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("First name") }} </label>
                <input
                    :placeholder="printPlaceholder('first_name')"
                    class="form-control"
                    v-model="filter.first_name"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Last name") }} </label>
                <input
                    :placeholder="printPlaceholder('last_name')"
                    class="form-control"
                    v-model="filter.last_name"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Phone") }} </label>
                <input
                    :placeholder="printPlaceholder('phone')"
                    class="form-control"
                    v-model="filter.phone"
                />
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Email") }} </label>
                <input
                    :placeholder="printPlaceholder('email')"
                    class="form-control"
                    v-model="filter.email"
                />
            </div>
        </div>
        <div class="grid grid-cols-4 gap-5">
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label"
                    >{{ __("Years worked in france") }}
                </label>
                <select
                    v-model="filter.years_worked_in_france"
                    class="form-select"
                >
                    <option :value="null" selected>
                        {{ __("Choose an option") }}
                    </option>
                    <option
                        v-for="(option, index) in [
                            'less than 1 year',
                            'between 1 and 2 years',
                            'more than 3 years',
                            'never',
                        ]"
                        :key="index"
                        :value="option"
                        class="capitalize"
                    >
                        {{ __(option) }}
                    </option>
                </select>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label"
                    >{{ __("Professional situation") }}
                </label>
                <select v-model="filter.professional_situation" class="form-select">
                    <option :value="null" selected>
                        {{ __("Choose an option") }}
                    </option>
                    <option
                        v-for="(professionalSituation, index) in [
                            'employee',
                            'freelancer',
                            'auto entrepreneur',
                            'job seeker',
                            'entrepreneur',
                            'public agent',
                            'student',
                            'retired',
                        ]"
                        :key="index"
                        :value="professionalSituation"
                        class="capitalize"
                    >
                        {{ __(professionalSituation) }}
                    </option>
                </select>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <label class="form-label">{{ __("Joined at") }} </label>
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
                first_name: "",
                last_name: "",
                phone: "",
                email: "",
                created_at: "",
                years_worked_in_france: null,
                professional_situation: null,
            }),
        };
    },
};
</script>
