import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                montserrat: ['Montserrat', ...defaultTheme.fontFamily.sans],
                league: ['"League Gothic"', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                show: {
                    '0%, 49.99%': { opacity: '0', zIndex: '1' },
                    '50%, 100%': { opacity: '1', zIndex: '5' },
                },
            },
            animation: {
                show: 'show 0.6s step-end forwards',
            }
        },
    },

    plugins: [forms],
};
