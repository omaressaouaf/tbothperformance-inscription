<script>
import { h, Fragment } from "vue";

export default {
    props: {
        contentType: {
            type: String,
            default: "text",
            validator(value) {
                return ["text", "nodes"].includes(value);
            },
        },
        maxNodes: {
            type: Number,
            default: 2,
        },
        maxChars: {
            type: Number,
            default: 100,
        },
    },
    data() {
        return {
            showMore: false,
        };
    },
    methods: {
        defaultSlotChildren() {
            const defaultSlot = this.$slots.default();

            const children =
                defaultSlot.length > 1 ? defaultSlot : defaultSlot[0].children;

            return this.contentType === "text"
                ? children
                : children.filter((child) => child.children !== "v-if");
        },
        renderShowMoreLessBtn() {
            if (this.$slots["show-more-less"]) {
                return this.$slots["show-more-less"]({
                    showMore: this.showMore,
                    toggleShowMore: () => {
                        this.showMore = !this.showMore;
                    },
                });
            }

            return h(
                "a",
                {
                    onClick: (e) => {
                        e.preventDefault();
                        this.showMore = !this.showMore;
                    },
                    href: "#",
                    class: "text-xs text-primary-11 block mt-1",
                },
                this.showMore ? __("Show less") : __("Show more")
            );
        },
        renderText() {
            const textToRender =
                this.showMore ||
                this.defaultSlotChildren().length < this.maxChars
                    ? this.defaultSlotChildren()
                    : this.defaultSlotChildren().substring(0, this.maxChars) +
                      "... ";

            return h("span", [
                textToRender,
                this.defaultSlotChildren().length > this.maxChars
                    ? this.renderShowMoreLessBtn()
                    : undefined,
            ]);
        },
        renderNodes() {
            const nodesToRender = this.showMore
                ? this.defaultSlotChildren()
                : this.defaultSlotChildren().filter(
                      (_, index) => index < this.maxNodes
                  );

            return h(Fragment, [
                nodesToRender,
                this.defaultSlotChildren().length > this.maxNodes
                    ? this.renderShowMoreLessBtn()
                    : undefined,
            ]);
        },
    },
    render() {
        if (this.contentType === "text") {
            return this.renderText();
        }

        return this.renderNodes();
    },
};
</script>
