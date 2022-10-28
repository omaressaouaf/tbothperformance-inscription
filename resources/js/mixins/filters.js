export default {
    computed: {
        filtersAreApplied() {
            const filterParams = route().params.filter;

            return (
                _.isObject(filterParams) &&
                _.some([this.filter.data()], filterParams)
            );
        },
    },
    methods: {
        applyFilters() {
            let queryParams = {
                ...route().params,
                filter: _.isObject(route().params.filter)
                    ? { ...route().params.filter, ...this.filter.data() }
                    : { ...this.filter.data() },
            };

            Object.keys(queryParams.filter).forEach((key) => {
                if (
                    typeof queryParams.filter[key] === "undefined" ||
                    queryParams.filter[key] === null ||
                    (typeof queryParams.filter[key] === "string" &&
                        queryParams.filter[key].trim() === "")
                ) {
                    delete queryParams.filter[key];
                }
            });

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
        resetFilters() {
            this.filter.reset();
            this.applyFilters();
        },
        setFiltersFromQuery() {
            Object.keys(this.filter.data()).forEach((key) => {
                this.filter[key] =
                    _.isObject(route().params.filter) &&
                    route().params?.filter[key]
                        ? route().params.filter[key]
                        : this.filter[key];
            });
        },
    },
    mounted() {
        this.setFiltersFromQuery();

        if (this.filtersAreApplied && !this.inlineFilter) {
            this.$emitter.emit("show-filters-box");
        }
    },
};
