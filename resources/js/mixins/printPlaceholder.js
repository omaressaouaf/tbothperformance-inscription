export default {
    methods: {
        printPlaceholder(attr) {
            return (
                __("Enter") +
                " " +
                __("validation.attributes." + attr)
            );
        },
    },
};
