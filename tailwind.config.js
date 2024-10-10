import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import daisyui from "daisyui";

/** @type {import("tailwindcss").Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography, daisyui],

    daisyui: {
        themes: [
            {
                ffhausen: {
                    primary: "#1f2937",
                    "primary-focus": "#374151",
                    "primary-content": "#ffffff",

                    secondary: "#ffffff",
                    "secondary-focus": "#f9fafb",
                    "secondary-content": "#374151",

                    accent: "#D31F2A",
                    "accent-focus": "#a71b27",
                    "accent-content": "#ffffff",

                    neutral: "#d4d4d8",
                    "neutral-focus": "#e5e5e5",
                    "neutral-content": "#27272a",

                    "base-100": "#F1F2F5",
                    "base-200": "#e5e7eb",
                    "base-300": "#d1d5db",
                    "base-content": "#111827",

                    info: "#1c92f2",
                    success: "#009485",
                    warning: "#ff9900",
                    error: "#ff5724",

                    "--rounded-box": "1rem",
                    "--rounded-btn": ".5rem",
                    "--rounded-badge": "1.9rem",

                    "--animation-btn": ".25s",
                    "--animation-input": ".2s",

                    "--btn-text-case": "uppercase",
                    "--navbar-padding": ".5rem",
                    "--border-btn": "1px",
                },
            },
        ],
    },
};
