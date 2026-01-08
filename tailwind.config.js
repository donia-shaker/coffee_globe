import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        container: {
            center: true,
            padding: "1rem",
            screens: {
                sm: "100%",
                md: "90%",
                lg: "80%",
                xl: "70%",
                "2xl": "1200px",
                "3xl": "1400px",
            },
        },
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#8E301E",
                background: "#FDF6EF",
                background_two: "#EEE0D8",
                main: "#501810",
                secondary: "#3B602B",
                bg_green: "#2D4324",
                bg_green_two: "#eff9ebeb",
            },
        },
    },

    plugins: [
        forms,
        // ✨ أضيفي هذا السطر عشان تدخلي Utilities مخصصة
        function ({ addUtilities }) {
            addUtilities({
                ".scrollbar-hidden": {
                    /* لأغلب المتصفحات */
                    "-ms-overflow-style": "none" /* IE and Edge */,
                    "scrollbar-width": "none" /* Firefox */,
                },
                ".scrollbar-hidden::-webkit-scrollbar": {
                    display: "none" /* Chrome, Safari, Opera */,
                },
            });
        },
    ],
};
