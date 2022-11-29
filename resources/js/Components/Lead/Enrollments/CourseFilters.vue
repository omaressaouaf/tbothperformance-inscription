<template>
    <div>
        <form @submit.prevent="applyFilters" class="space-y-6">
            <div>
                <label class="form-label">{{ __("Search") }} </label>
                <input
                    :placeholder="__('Search') + '...'"
                    class="form-control"
                    v-model="filter.search_query"
                />
            </div>
            <div>
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
            <div>
                <label class="form-label">{{ __("Categories") }} </label>
                <div class="flex flex-col space-y-2">
                    <div
                        class="form-check"
                        v-for="courseCategory in courseCategories"
                        :key="courseCategory.id"
                    >
                        <input
                            v-model="selectedCourseCategories"
                            :value="courseCategory.id"
                            class="form-check-input"
                            type="checkbox"
                            :id="`course-category-${courseCategory.id}`"
                        />
                        <label
                            class="form-check-label"
                            :for="`course-category-${courseCategory.id}`"
                        >
                            {{ courseCategory.name }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
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
    </div>
</template>

<script>
import filters from "@/mixins/filters";
import VForm from "@/libs/vform";

export default {
    mixins: [filters],
    props: {
        courseCategories: Array,
    },
    data() {
        return {
            filter: new VForm({
                search_query: "",
                eligible_for_cpf: null,
                category_id: "",
            }),
        };
    },
    computed: {
        selectedCourseCategories: {
            set(value) {
                this.filter.category_id = value.join("|");
            },
            get() {
                return this.filter.category_id.split("|");
            },
        },
    },
};
</script>
