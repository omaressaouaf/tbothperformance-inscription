<template>
    <div
        v-if="
            hasSlot('secondary-actions') ||
            hasSlot('actions') ||
            (exportTable && !simpleTable)
        "
        class="actions flex flex-col items-start gap-2 space-y-2 lg:flex-row lg:justify-end lg:space-y-0 mb-5 xl:mt-8 2xl:mt-0"
    >
        <slot name="secondary-actions" />
        <Dropdown v-if="exportTable && !simpleTable && !inTimeTracking">
            <template #trigger>
                <button class="btn btn-outline-secondary">
                    <FileTextIcon class="w-4 h-4 me-2" /> {{ __("Export") }}
                    <ChevronDownIcon class="w-4 h-4 ms-auto sm:ms-2" />
                </button>
            </template>
            <template #content>
                <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                    <DropdownLink
                        v-for="(format, index) in supportedExportFormats"
                        :key="index"
                        :href="
                            route('admin.bulk.export', {
                                table: tableToExport,
                                format: format,
                                page: pagination.currentPage,
                                ...exportQueryParams,
                            })
                        "
                        anchor
                        :title="format"
                        icon="FileTextIcon"
                        class="uppercase"
                    />
                </div>
            </template>
        </Dropdown>
        <slot name="actions" />
    </div>
    <div v-if="hasSlot('alerts')">
        <slot name="alerts" />
    </div>
    <div class="datalist flex flex-col">
        <div class="-my-2 sm:-mx-6 lg:-mx-8">
            <div class="w-full py-2 align-middle inline-block sm:px-6 lg:px-8">
                <div class="w-full sm:rounded-lg">
                    <div
                        class="flex flex-wrap sm:flex-nowrap items-start justify-between mt-2 px-1 max-w-full min-w-full"
                    >
                        <select
                            v-if="paginate && !simpleTable"
                            v-model="perPage"
                            class="w-20 form-select mt-3 sm:mt-0"
                        >
                            <option
                                v-for="perPage in [5, 10, 25, 50]"
                                :key="perPage"
                                :value="perPage"
                            >
                                {{ perPage }}
                            </option>
                        </select>

                        <div
                            v-if="
                                hasSlot('bottom-actions') ||
                                (search && !simpleTable)
                            "
                            class="bottom-actions flex flex-col items-end gap-2 xl:items-start space-y-2 xl:flex-row xl:justify-end xl:space-y-0 w-full sm:w-auto mt-3 sm:mt-0 sm:ms-auto"
                        >
                            <slot name="bottom-actions" />
                            <button
                                v-if="hasSlot('filters-box')"
                                @click="showFiltersBox = !showFiltersBox"
                                class="btn btn-outline-secondary"
                            >
                                <FilterIcon class="w-4 h-4 me-2" />
                                {{ __("Filters") }}
                            </button>
                            <Dropdown
                                v-if="sorts?.length"
                                width="72"
                                :id="`sort-dropdown-${componentId}`"
                            >
                                <template #trigger>
                                    <button class="btn btn-outline-secondary">
                                        {{ __("Sort by") }}
                                        <ChevronDownIcon class="w-4 h-4 ms-2" />
                                    </button>
                                </template>
                                <template #content>
                                    <div
                                        class="p-4 border-t border-black border-opacity-5 dark:border-dark-3"
                                    >
                                        <div
                                            v-for="(sort, index) in sorts"
                                            :key="index"
                                            class="flex items-center justify-between space-y-2"
                                        >
                                            <span>
                                                {{ __(sort.text) }}
                                            </span>
                                            <div class="flex gap-1 ms-1">
                                                <DropdownLink
                                                    anchor
                                                    href="javascript:;"
                                                    @click="
                                                        handleSelectSort(
                                                            sort.column
                                                        )
                                                    "
                                                    icon="ChevronUpIcon"
                                                    class="btn"
                                                    :class="[
                                                        sortIsActive(
                                                            sort.column
                                                        )
                                                            ? 'btn-secondary'
                                                            : 'btn-outline-secondary',
                                                    ]"
                                                />
                                                <DropdownLink
                                                    anchor
                                                    href="javascript:;"
                                                    @click="
                                                        handleSelectSort(
                                                            sort.column,
                                                            true
                                                        )
                                                    "
                                                    icon="ChevronDownIcon"
                                                    class="btn"
                                                    :class="[
                                                        sortIsActive(
                                                            sort.column,
                                                            true
                                                        )
                                                            ? 'btn-secondary'
                                                            : 'btn-outline-secondary',
                                                    ]"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </Dropdown>
                            <div
                                v-if="search && !simpleTable"
                                class="w-56 relative text-gray-700 dark:text-gray-300"
                            >
                                <form @submit.prevent="applyFilters">
                                    <input
                                        v-model="filter.search_query"
                                        :placeholder="__('Search')"
                                        class="form-control w-56 pr-10 placeholder-theme-13"
                                    />
                                    <button>
                                        <SearchIcon
                                            class="cursor-pointer w-4 h-4 absolute my-auto inset-y-0 me-3 right-0"
                                        />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="hasSlot('filters-box')"
                        class="my-7 form-card rounded-lg shadow-sm"
                        :class="{ hidden: !showFiltersBox }"
                    >
                        <p class="form-card-header">
                            {{ __("Precise filters") }} :
                            <span
                                v-if="showFiltersBoxNote"
                                class="text-primary-11 text-xs ms-1"
                            >
                                <AlertCircleIcon class="w-4 h-4" />
                                {{
                                    __(
                                        "To search for multiple values separate terms with | . e.g : (Name1|Name2...)"
                                    )
                                }}
                            </span>
                        </p>
                        <div class="form-card-body max-w-fit">
                            <slot name="filters-box" />
                        </div>
                    </div>
                    <FloatingScroll
                        on-hover
                        class="table-wrapper"
                        :class="$attrs.class"
                    >
                        <table class="min-w-full px-2">
                            <thead>
                                <slot name="headings" />
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <slot v-if="tableHasRows" />
                                <Tr v-else>
                                    <Td colspan="20" class="text-center">{{
                                        __("No entries were found")
                                    }}</Td>
                                </Tr>
                            </tbody>
                        </table>
                    </FloatingScroll>
                    <div
                        v-if="length || paginate"
                        class="flex flex-wrap sm:flex-nowrap items-center justify-between mt-2 px-1 py-4"
                    >
                        <div
                            v-if="length"
                            class="hidden md:block text-gray-600"
                        >
                            {{
                                __("Showing :from to :to of :rows entries", {
                                    from: paginate
                                        ? pagination?.from || 0
                                        : $slots.default()[0].children.length
                                        ? 1
                                        : 0,
                                    to: paginate
                                        ? pagination?.to || 0
                                        : $slots.default()[0].children.length ||
                                          total,
                                    rows: paginate
                                        ? pagination?.total || 0
                                        : $slots.default()[0].children.length ||
                                          total,
                                })
                            }}
                        </div>
                        <Pagination
                            v-if="paginate"
                            :links="pagination?.links"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import cash from "cash-dom";
import VForm from "@/libs/vform";
import filters from "@/mixins/filters";

export default {
    mixins: [filters],
    props: {
        pagination: Object,
        tableToExport: String,
        paginate: {
            type: Boolean,
            default: true,
        },
        exportTable: {
            type: Boolean,
            default: true,
        },
        length: {
            type: Boolean,
            default: true,
        },
        search: {
            type: Boolean,
            default: true,
        },
        simpleTable: {
            type: Boolean,
            default: false,
        },
        total: Number,
        exportQueryParams: Object,
        sorts: {
            type: Array,
            default: [],
        },
        defaultSort: String,
        showFiltersBoxNote: {
            type: Boolean,
            default: true,
        },
    },
    inject: {
        inTimeTracking: {
            default: false,
        },
    },
    data() {
        return {
            componentId: _.uniqueId(),
            supportedExportFormats: [
                "xlsx",
                "csv",
                "tsv",
                "ods",
                "xls",
                "html",
            ],
            perPage: this.paginate ? route().params.perPage || 10 : undefined,
            sort: route().params.sort || this.defaultSort,
            filter: new VForm({
                search_query: route().params?.filter?.search_query,
            }),
            showFiltersBox: false,
            inlineFilter: true, //for filter mixin
        };
    },
    computed: {
        tableHasRows() {
            return (
                this.$slots.default &&
                (this.$slots.default()[0].children.length ||
                    this.$slots.default()[0].children.default)
            );
        },
    },
    watch: {
        perPage: "handleParamsChange",
        sort: "handleParamsChange",
    },
    methods: {
        sortIsActive(sort, desc = false) {
            const serializedSort = desc ? `-${sort}` : sort;

            return (
                route().params?.sort === serializedSort ||
                (this.defaultSort === serializedSort && !route().params?.sort)
            );
        },
        handleSelectSort(sort, desc = false) {
            this.sort = desc ? `-${sort}` : sort;
            cash(`#sort-dropdown-${this.componentId}`).dropdown("hide");
        },
        hasSlot(slot) {
            return this.$slots[slot];
        },
        handleParamsChange() {
            let queryParams = {
                ...route().params,
                perPage: this.perPage,
                sort: this.sort,
            };

            if (queryParams.perPage === 10) delete queryParams.perPage;
            if (this.defaultSort === queryParams.sort) delete queryParams.sort;

            this.$inertia.get(
                route(route().current(), queryParams),
                {},
                {
                    replace: true,
                    preserveState: true,
                    preserveScroll: true,
                }
            );
        },
    },
    created() {
        this.$emitter.on("show-filters-box", () => {
            this.showFiltersBox = true;
        });
    },
    beforeUnmount() {
        this.$emitter.off("show-filters-box");
    },
};
</script>
