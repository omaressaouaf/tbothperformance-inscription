export default {
    methods: {
        __(key, replace, locale = null) {
            const selectedLocale = locale || window._locale;
            let translation,
                translationNotFound = true;

            try {
                translation = key
                    .split(".")
                    .reduce(
                        (t, i) => t[i] || null,
                        window._translations[selectedLocale].php
                    );

                if (translation) {
                    translationNotFound = false;
                }
            } catch (e) {
                translation = key;
            }

            if (translationNotFound) {
                translation = window._translations[selectedLocale]["json"][key]
                    ? window._translations[selectedLocale]["json"][key]
                    : key;
            }

            _.forEach(replace, (value, key) => {
                translation = translation.replace(":" + key, value);
            });

            return translation;
        },
        __choice(key, number, replace, locale = null) {
            const selectedLocale = locale || window._locale;
            let translation,
                translationNotFound = true;

            try {
                translation = key
                    .split(".")
                    .reduce(
                        (t, i) => t[i] || null,
                        window._translations[selectedLocale].php
                    );

                if (translation) {
                    translationNotFound = false;
                }
            } catch (e) {
                translation = key;
            }

            if (translationNotFound) {
                translation = window._translations[selectedLocale]["json"][key]
                    ? window._translations[selectedLocale]["json"][key]
                    : key;
            }

            let choice = translation.split("|")[number];

            _.forEach(replace, (value, key) => {
                choice = choice.replace(":" + key, value);
            });

            return choice;
        },
    },
};
