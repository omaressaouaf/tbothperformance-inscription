export default {
    methods: {
        printPlaceholder(attr) {
            return _.capitalize(__("validation.attributes." + attr));
        },
    },
};
