import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // <-- Add this line!
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./app/Livewire/**/*.php",
    ],

    safelist: [
        {
            pattern: /(bg|text|border)-(red|green|blue|yellow|orange|purple|pink|indigo|teal|cyan|emerald|lime|amber)-(50|100|400|500|600|700)/,
        },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                iba: {
                    black: '#131011',
                    red: '#CF452C',
                    orange: '#FF8623',
                    teal: '#0095AC',
                    green: '#5C7914',
                    light: '#FFFBF7'
                }
            }, 
        },
    },

    plugins: [forms, typography],
};
