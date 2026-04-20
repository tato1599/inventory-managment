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
            colors: {
                "secondary": "#944a00",
                "on-surface-variant": "#58413f",
                "primary-container": "#8e1b1b",
                "primary": "#6c0008",
                "on-primary-fixed": "#410003",
                "on-secondary-container": "#663100",
                "surface-container": "#eeeef0",
                "inverse-surface": "#2f3132",
                "on-primary-container": "#ff9e95",
                "on-secondary-fixed-variant": "#713700",
                "surface": "#f9f9fb",
                "error-container": "#ffdad6",
                "outline": "#8c716e",
                "outline-variant": "#e0bfbc",
                "secondary-fixed": "#ffdcc5",
                "inverse-primary": "#ffb3ac",
                "tertiary-container": "#004e72",
                "tertiary": "#003651",
                "surface-dim": "#d9dadc",
                "primary-fixed": "#ffdad6",
                "on-tertiary": "#ffffff",
                "surface-tint": "#ac322e",
                "secondary-container": "#fc8f34",
                "surface-container-high": "#e8e8ea",
                "on-error-container": "#93000a",
                "on-error": "#ffffff",
                "on-tertiary-fixed": "#001e2f",
                "primary-fixed-dim": "#ffb3ac",
                "surface-container-low": "#f3f3f5",
                "surface-container-highest": "#e2e2e4",
                "on-surface": "#1a1c1d",
                "background": "#f9f9fb",
                "on-background": "#1a1c1d",
                "surface-bright": "#f9f9fb",
                "tertiary-fixed": "#c9e6ff",
                "inverse-on-surface": "#f0f0f2",
                "surface-variant": "#e2e2e4",
                "tertiary-fixed-dim": "#95cdf7",
                "on-tertiary-container": "#87bfe8",
                "secondary-fixed-dim": "#ffb783",
                "error": "#ba1a1a",
                "on-primary-fixed-variant": "#8b1919",
                "on-secondary-fixed": "#301400",
                "on-primary": "#ffffff",
                "on-secondary": "#ffffff",
                "surface-container-lowest": "#ffffff",
                "on-tertiary-fixed-variant": "#004b6f"
            },
            borderRadius: {
                "DEFAULT": "0.125rem",
                "lg": "0.25rem",
                "xl": "0.5rem",
                "full": "0.75rem"
            },
        },
    },

    plugins: [forms, typography, daisyui],
};
