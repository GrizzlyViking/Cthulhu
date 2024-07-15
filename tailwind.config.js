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
            colors: {
                'cthulhu-green': {
                     50: '#FBFADA',
                    100: '#D6EFD8',
                    200: '#ADBC9F',
                    300: '#80AF81',
                    350: '#508D4E',
                    400: '#436850',
                    600: '#1A5319',
                    800: '#12372A'
                },
                'cthulhu-yellow': {
                    200: '#F5E7B2',
                    400: '#F9D689',
                    600: '#E0A75E',
                },
                'cthulhu-blood': {
                    200: '#FF204E',
                    400: '#A0153E',
                    600: '#5D0E41',
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
