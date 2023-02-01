<template>
    <Dropdown
        @dropdownOpened="handleDropdownOpened"
        class="w-full"
        :id="`select-dropdown-${componentId}`"
    >
        <template #trigger>
            <button
                type="button"
                class="btn btn-outline-secondary bg-white w-full flex items-center hover:bg-transparent dark:hover:bg-dark-2 dark:hover:border-dark-2 dark:bg-dark-2 dark:border-dark-2"
                @keydown.down.exact.prevent="selectNextOption"
                @keydown.up.exact.prevent="selectPreviousOption"
            >
                <Component v-if="icon" :is="icon" class="w-4 h-4 me-2" />
                <span v-if="selectedOption">
                    <template v-if="customLabel">
                        {{ customLabel(selectedOption) }}
                    </template>
                    <template v-else>
                        {{ label ? selectedOption[label] : selectedOption }}
                    </template>
                </span>
                <span v-else>
                    {{ defaultOptionText || __("Choose an option") }}
                </span>
                <span class="ms-auto">
                    <ChevronDownIcon class="w-4 h-4 ms-2" />
                </span>
            </button>
        </template>
        <template #content>
            <div
                class="p-2 overflow-y-auto max-h-96 has-cool-scrollbar relative"
                :id="`select-dropdown-menu-content-${componentId}`"
            >
                <div v-if="showSearch" class="sticky top-0 mb-2 z-10">
                    <div class="input-group">
                        <input
                            v-model="searchQuery"
                            :placeholder="__('Search') + '...'"
                            :id="`select-dropdown-search-${componentId}`"
                            class="form-control"
                            @keydown.down.exact.prevent="selectFirstOption"
                        />
                        <div v-if="!internalSearch" class="input-group-text">
                            <button
                                :class="{ invisible: loading }"
                                :disabled="loading"
                                @click="handleReset"
                                type="button"
                            >
                                <Tippy :content="__('Reset')">
                                    <RefreshCwIcon class="w-4 h-4" />
                                </Tippy>
                            </button>
                        </div>
                        <LoadingIcon
                            v-if="loading"
                            icon="oval"
                            class="w-4 h-4 absolute my-auto inset-y-0 me-3 end-0"
                        />
                    </div>
                </div>
                <div class="mb-2">
                    <slot name="after-search" />
                </div>
                <div ref="optionsContainer">
                    <DropdownLink
                        anchor
                        href="javascript:;"
                        :active="!selectedOption"
                        @click="handleSelect(null, true)"
                        @keydown.down.exact.prevent="selectNextOption"
                        @keydown.up.exact.prevent="selectPreviousOption"
                    >
                        {{ defaultOptionText || __("Choose an option") }}
                    </DropdownLink>

                    <DropdownLink
                        v-for="(option, index) in filteredOptions"
                        :key="index"
                        v-memo="[
                            options,
                            trackBy && bindWithTrackBy ? option[trackBy] : '',
                            trackBy && bindWithTrackBy
                                ? option[trackBy] === modelValue
                                : option === modelValue,
                        ]"
                        anchor
                        href="javascript:;"
                        :active="
                            (modelValue && modelValue === option[trackBy]) ||
                            modelValue === option
                        "
                        :class="customOptionClass(option)"
                        @click="handleSelect(option, true)"
                        @keydown.down.exact.prevent="selectNextOption"
                        @keydown.up.exact.prevent="selectPreviousOption"
                    >
                        <slot
                            v-if="hasOptionSlot"
                            name="option"
                            :option="option"
                        />
                        <template v-else>
                            {{ label ? option[label] : option }}
                        </template>
                    </DropdownLink>
                </div>
                <div
                    v-if="showLoadMore && filteredOptions?.length"
                    @click="$emit('loadMore')"
                    :id="`select-dropdown-load-more-${componentId}`"
                    class="dropdown-menu-link"
                    :class="{ 'pointer-events-none': loading }"
                >
                    <span class="mx-auto font-semibold text-primary-11">
                        {{ __("Load more...") }}
                    </span>
                </div>
            </div>
        </template>
    </Dropdown>
</template>

<script>
import cash from "cash-dom";

export default {
    props: {
        options: {
            type: Array,
            required: true,
        },
        modelValue: {
            type: [String, Number, Array, Object],
            default: "",
        },
        trackBy: String,
        bindWithTrackBy: {
            type: Boolean,
            default: true,
        },
        label: String,
        customLabel: Function,
        customSearchPattern: {
            type: Function,
            default: function () {
                return null;
            },
        },
        icon: String,
        showLoadMore: Boolean,
        showSearch: {
            type: Boolean,
            default: true,
        },
        internalSearch: {
            type: Boolean,
            default: true,
        },
        loading: Boolean,
        reset: Boolean,
        defaultOptionText: String,
        closeOnSelect: {
            type: Boolean,
            default: true,
        },
        customOptionClass: {
            type: Function,
            default: function () {
                return "";
            },
        },
    },
    data() {
        return {
            componentId: _.uniqueId(),
            filteredOptions: [],
            selectedOption: null,
            searchQuery: "",
        };
    },
    watch: {
        options: {
            handler() {
                this.filteredOptions = this.allOptions;
                this.presetSelectedOption();
            },
            immediate: true,
        },
        modelValue: {
            handler: "presetSelectedOption",
            immediate: true,
        },
        searchQuery: _.debounce(function (query) {
            if (!this.internalSearch) {
                return this.$emit("searchChange", query);
            }

            this.filteredOptions = this.allOptions.filter((option) => {
                return query
                    .toLowerCase()
                    .split(" ")
                    .every((word) =>
                        this.trackBy
                            ? (this.customSearchPattern(option) &&
                                  this.customSearchPattern(option)
                                      .toLowerCase()
                                      .includes(word)) ||
                              Object.values(option).some((propertyValue) => {
                                  return (
                                      ["string", "number"].includes(
                                          typeof propertyValue
                                      ) &&
                                      String(propertyValue)
                                          .toLowerCase()
                                          .includes(word)
                                  );
                              })
                            : option.toLowerCase().includes(word)
                    );
            });
        }, 800),
        reset() {
            if (this.reset) {
                this.handleReset();
            }
        },
    },
    computed: {
        allOptions() {
            // Sanitize options from nullable and repeated objects
            const nonNullableOptions = this.options.filter((option) => option);

            return this.trackBy
                ? _.uniqBy(nonNullableOptions, this.trackBy)
                : nonNullableOptions;
        },
        hasOptionSlot() {
            return this.$slots["option"];
        },
        selectedOptionIndex() {
            return this.filteredOptions.findIndex((option) => {
                return this.bindWithTrackBy && this.trackBy
                    ? option[this.trackBy] == this.modelValue
                    : option == this.selectedOption;
            });
        },
    },
    methods: {
        handleReset() {
            if (this.searchQuery) {
                this.searchQuery = "";
            } else {
                this.$emit("searchChange", "");
            }
        },
        scrollToSelectedOption() {
            const dropdownMenuContent = document.querySelector(
                `#select-dropdown-menu-content-${this.componentId}`
            );
            const selectedOptionNode =
                this.$refs.optionsContainer.children[
                    this.selectedOptionIndex + 1
                ];

            dropdownMenuContent.scrollTop =
                selectedOptionNode.offsetTop -
                dropdownMenuContent.offsetTop -
                200;
        },
        handleDropdownOpened() {
            this.scrollToSelectedOption();
        },
        selectFirstOption() {
            const firstOption = this.filteredOptions[0];
            const firstOptionNode = this.$refs.optionsContainer.children[1];

            if (firstOption) {
                firstOptionNode.focus();
                this.handleSelect(firstOption);
            }
        },
        selectNextOption() {
            const nextOption =
                this.filteredOptions[this.selectedOptionIndex + 1];
            const nextOptionNode =
                this.$refs.optionsContainer.children[
                    this.selectedOptionIndex + 2
                ];

            if (nextOption) {
                nextOptionNode.focus();
                this.handleSelect(nextOption);
            }
        },
        selectPreviousOption() {
            const previousOption =
                this.filteredOptions[this.selectedOptionIndex - 1];
            const previousOptionNode =
                this.$refs.optionsContainer.children[this.selectedOptionIndex];

            if (previousOptionNode) previousOptionNode.focus();
            this.handleSelect(previousOption);
        },
        presetSelectedOption() {
            // Only preselect if the selected option is not set yet (on page access , or modal open)
            // this condition was causing a bug that's why it's commented but at the same time it used to resolve another bug which we haven't figure it out yet
            // if (
            //     this.selectedOption === null ||
            //     typeof this.selectedOption === "undefined"
            // ) {
            if (this.trackBy) {
                if (!this.bindWithTrackBy && this.modelValue) {
                    this.selectedOption = this.allOptions.find(
                        (option) =>
                            option[this.trackBy] ===
                            this.modelValue[this.trackBy]
                    );
                    return;
                }

                this.selectedOption = this.allOptions.find(
                    (option) => option[this.trackBy] === this.modelValue
                );
            } else {
                this.selectedOption = this.modelValue;
            }
            // }

            if (
                this.modelValue === null ||
                this.modelValue === "" ||
                typeof this.modelValue === "undefined"
            ) {
                this.selectedOption = null;
            }
        },
        handleSelect(option, hideDropdown = false) {
            this.$nextTick(() => {
                this.$emit(
                    "update:modelValue",
                    option
                        ? this.trackBy && this.bindWithTrackBy
                            ? option[this.trackBy]
                            : option
                        : null
                );

                if (hideDropdown && this.closeOnSelect) {
                    cash(`#select-dropdown-${this.componentId}`).dropdown(
                        "hide"
                    );
                }
            });
        },
    },
};
</script>
