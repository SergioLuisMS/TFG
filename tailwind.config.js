import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                rojo: '#d23e5d',
                granate: '#872829',
                azul: '#317080',
                verde: '#7ebdb3',
                negro: '#1d1d1b',
            },
        },
    },
    plugins: [],
}
