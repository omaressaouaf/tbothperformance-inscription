<template>
    <li>
        <a
            @click="toggleShowContent"
            href="javascript:;"
            :class="[
                `${menuClassPrefix}menu`,
                active ? `${menuClassPrefix}menu--active` : '',
            ]"
        >
            <MenuTippy :content="title" class="flex w-full">
                <div :class="`${menuClassPrefix}menu__icon`">
                    <Component :is="icon" class="w-6 h-6"/>
                </div>
                <div :class="`${menuClassPrefix}menu__title`">
                    {{ title }}
                    <div
                        :class="[
                            `${menuClassPrefix}menu__sub-icon`,
                            showContent ? 'transform rotate-180' : '',
                        ]"
                    >
                        <ChevronDownIcon />
                    </div>
                </div>
            </MenuTippy>
        </a>
        <SlideUpDown>
            <ul v-if="showContent">
                <slot />
            </ul>
        </SlideUpDown>
    </li>
</template>

<script>
export default {
    props: {
        icon: String,
        title: String,
        active: Boolean,
        collapseLevel: Number,
    },
    data() {
        return {
            componentId: _.uniqueId(),
            showContent: false,
        };
    },
    inject: ["menuClassPrefix"],
    methods: {
        toggleShowContent() {
            this.showContent = !this.showContent;

            this.$nextTick(() => {
                if (this.showContent) {
                    this.$emitter.emit("close-all-menu-collapse", {
                        componentId: this.componentId,
                        collapseLevel: this.collapseLevel,
                    });
                }
            });
        },
    },
    mounted() {
        if (this.active) {
            this.showContent = true;
        }

        this.$emitter.on(
            "close-all-menu-collapse",
            ({ componentId, collapseLevel }) => {
                if (
                    this.componentId != componentId &&
                    this.collapseLevel == collapseLevel
                ) {
                    this.showContent = false;
                }
            }
        );
    },
};
</script>
