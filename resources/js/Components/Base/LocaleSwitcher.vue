<template>
    <Dropdown>
        <template #trigger>
            <button
                class="notification me-5 flex items-end"
                role="button"
                aria-expanded="false"
            >
                <img
                    :src="`/images/${_locale}.png`"
                    class="w-5 h-5 rounded"
                    :alt="_locale"
                />
            </button>
        </template>
        <template #content>
            <div
                class="p-2 border-t border-black border-opacity-5 dark:border-dark-3"
            >
                <DropdownLink
                    v-for="(localeProperties, localeCode) in _supportedLocales"
                    href="#"
                    :key="localeCode"
                    hreflang="{{ localeCode }}"
                    anchor
                    @click.prevent="handleSwitchLocale(localeCode)"
                >
                    <img
                        :src="`/images/${localeCode}.png`"
                        class="w-5 h-5 me-2 rounded"
                        :alt="__(localeProperties['native'])"
                    />
                    {{ __(localeProperties["native"]) }}
                </DropdownLink>
            </div>
        </template>
    </Dropdown>
    <form
        :action="route('locale.switch')"
        ref="localeForm"
        method="POST"
        class="hidden"
    >
        <input type="hidden" name="_method" value="PUT" />
        <input name="locale" :value="selectedLocale" />
    </form>
</template>
<script>
export default {
    data() {
        return {
            selectedLocale: window._locale,
        };
    },
    methods: {
        handleSwitchLocale(localeCode) {
            if (localeCode === this._locale) return;

            this.selectedLocale = localeCode;

            this.$nextTick(() => {
                this.$refs.localeForm.submit();
            });
        },
    },
};
</script>
