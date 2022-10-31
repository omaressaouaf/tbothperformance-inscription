const primaryColors = require("@left4code/tw-starter/dist/js/colors");
const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    darkMode: "class",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        borderColor: (theme) => ({
            ...theme("colors"),
            DEFAULT: primaryColors.gray["300"],
        }),
        colors: {
            ...primaryColors,
            primary: {
                ...primaryColors.primary,
                1: "#378079",
            },
            white: "white",
            black: "black",
            current: "current",
            transparent: "transparent",
            red: colors.red,
            green: colors.green,
            blue: colors.blue,
            orange: colors.orange,
            amber: colors.amber,
            yellow: colors.yellow,
            theme: {
                1: "#134254",
                2: "#94A1B7",
                3: "#275262",
                4: "#DEE7EF",
                5: "#61C0B8",
                6: "#134254",
                7: "#7486A2",
                8: "#134254",
                9: "#1e5469",
                10: "#F0F5FF",
                11: "#61C0B8",
                12: "#A8B6DB",
                13: "#7E8BB4",
                14: "#275262",
                15: "#FBC500",
                16: "#E63B1F",
                17: "#B8F1E1",
                18: "#FFE7D9",
                19: "#DBDFF9",
                20: "#13B176",
                21: "#CE3131",
                22: "#203f90",
                23: "#D6E1F5",
                24: "#378079",
                25: "#2F5AD8",
                26: "#547FFC",
                27: "#16379B",
                28: "#2F50B5",
                29: "#F78B00",
                30: "#15379D",
                31: "#DEE5F5",
                32: "#8DA9BE",
                33: "#607F96",
                34: "#b7c3e6",
                35: "#1b389f",
                36: "#183295",
                37: "#ce313115",
                38: "#31ce8515",
                39: "#6C62A5",
            },
        },
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            container: {
                center: true,
            },
            maxWidth: {
                "1/4": "25%",
                "1/2": "50%",
                "3/4": "75%",
            },
            strokeWidth: {
                0.5: 0.5,
                1.5: 1.5,
                2.5: 2.5,
            },
            screens: {
                print: { raw: "print" },
                // => @media print { ... }
            },
            transitionProperty: {
                width: "width",
            },
        },
    },

    plugins: [require("tailwindcss-rtl")],
};
