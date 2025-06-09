import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-900', 'dark:text-yellow-200',
        'bg-purple-100', 'text-purple-800', 'dark:bg-purple-900', 'dark:text-purple-200',
        'bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-200',
        'bg-gray-100', 'text-gray-800', 'dark:bg-gray-900', 'dark:text-gray-200',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['SF Pro Display', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
