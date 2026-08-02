import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    /*
     * Opt-in dark mode. The app is designed as a single dark-framed theme, so
     * `dark:` variants must never fire off the OS preference — nothing here
     * ships a matching dark palette.
     */
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                /* Ink & canvas: the deep green the app is framed in. */
                'cthulhu-green': {
                     50: '#F4F7F0',
                    100: '#DDEBDA',
                    200: '#ADBC9F',
                    300: '#80AF81',
                    350: '#508D4E',
                    400: '#436850',
                    500: '#2F5540',
                    600: '#1A5319',
                    700: '#1A4A38',
                    800: '#12372A',
                    900: '#0C251C',
                    950: '#071711',
                },
                /* Card surfaces: aged paper, the investigator's dossier. */
                parchment: {
                     50: '#FDFBF3',
                    100: '#F7F2E1',
                    200: '#EDE4CB',
                    300: '#DCD1AF',
                    400: '#C3B58B',
                    500: '#A2946B',
                },
                /* Brass: accents, focus rings, the "important" marker. */
                'cthulhu-yellow': {
                    200: '#F5E7B2',
                    300: '#EFD48A',
                    400: '#F9D689',
                    500: '#D9A93F',
                    600: '#E0A75E',
                    700: '#A87A33',
                },
                /* Blood: danger, damage, madness. */
                'cthulhu-blood': {
                    200: '#FF204E',
                    300: '#D81E48',
                    400: '#A0153E',
                    500: '#7E1140',
                    600: '#5D0E41',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Limelight', ...defaultTheme.fontFamily.serif],
            },
            boxShadow: {
                card: '0 1px 2px rgb(7 23 17 / 0.28), 0 8px 24px -12px rgb(7 23 17 / 0.45)',
                raised: '0 2px 4px rgb(7 23 17 / 0.3), 0 18px 40px -18px rgb(7 23 17 / 0.6)',
            },
        },
    },

    plugins: [forms],
};
