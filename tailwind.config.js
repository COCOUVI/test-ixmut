import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },

            backgroundImage: {
                logo: "url('/images/Logo IMUXT.png')",
                "logo-ligth": "url('/images/Logo IMUXT (Blanc).png')",
                favicon: "url('/images/favicon.png')",
            },

            colors: {
                primary: {
                    DEFAULT: "#ffffff",
                    light: "#f5f5f5",
                    dark: "#e5e5e5",
                },
                dark: {
                    DEFAULT: "#164f63",
                    light: "#236b83",
                    dark: "#0f3b4a",
                },
            },

            keyframes: {
                blink: {
                    "0%, 100%": { opacity: "1" },
                    "50%": { opacity: "0" },
                },
                pulseZoom: {
                    "0%, 100%": { transform: "scale(1)" },
                    "50%": { transform: "scale(1.1)" },
                },
                slideFadeIn: {
                    "0%": { opacity: "0", transform: "translateX(100px)" },
                    "100%": { opacity: "1", transform: "translateX(0)" },
                },
                float: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-10px)" },
                },
            },

            animation: {
                blink: "blink 1s infinite",
                pulseZoom: "pulseZoom 2s ease-in-out infinite",
                slideFadeIn: "slideFadeIn 0.8s ease-out forwards",
                float: "float 3s ease-in-out infinite",
            },
        },
    },

    plugins: [forms],
};
