import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import daisyui from "daisyui";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                headline: ['Public Sans', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                label: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                "DEFAULT": "0.5rem",
                "lg": "0.75rem",
                "xl": "1rem",
                "full": "9999px"
            },
        },
    },

    daisyui: {
        themes: [
            {
                light: {
                    "primary": "#4f46e5",
                    "secondary": "#0ea5e9",
                    "accent": "#f59e0b",
                    "neutral": "#1e293b",
                    "base-100": "#ffffff",
                    "base-200": "#f8fafc",
                    "base-300": "#f1f5f9",
                    "base-content": "#1e293b",
                    "info": "#06b6d4",
                    "success": "#10b981",
                    "warning": "#f59e0b",
                    "error": "#ef4444",
                    "--rounded-box": "1rem",
                    "--rounded-btn": "0.75rem",
                },
                dark: {
                    "primary": "#6366f1",
                    "secondary": "#38bdf8",
                    "accent": "#fbbf24",
                    "neutral": "#1e293b",
                    "base-100": "#0f172a",
                    "base-200": "#1e293b",
                    "base-300": "#334155",
                    "base-content": "#f1f5f9",
                    "info": "#06b6d4",
                    "success": "#10b981",
                    "warning": "#f59e0b",
                    "error": "#ef4444",
                    "--rounded-box": "1rem",
                    "--rounded-btn": "0.75rem",
                },
            },
        ],
    },

    plugins: [forms, typography, daisyui],
};
