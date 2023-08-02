import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                indigo: {
                    100: "#e6e8ff",
                    300: "#b2b7ff",
                    400: "#7886d7",
                    500: "#6574cd",
                    600: "#5661b3",
                    800: "#2f365f",
                    900: "#191e38"
                }
            },
        },
    },

    plugins: [forms],
};
