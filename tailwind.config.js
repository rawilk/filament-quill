import defaultTheme from 'tailwindcss/defaultTheme';

const keys = [
    'primary',
    'danger',
    'warning',
    'success',
    'info',
    'gray',
];

const buildColors = () => {
    const colors = {};

    keys.forEach(key => {
        colors[key] = {
            50: `rgba(var(--${key}-50), <alpha-value>)`,
            100: `rgba(var(--${key}-100), <alpha-value>)`,
            200: `rgba(var(--${key}-200), <alpha-value>)`,
            300: `rgba(var(--${key}-300), <alpha-value>)`,
            400: `rgba(var(--${key}-400), <alpha-value>)`,
            500: `rgba(var(--${key}-500), <alpha-value>)`,
            600: `rgba(var(--${key}-600), <alpha-value>)`,
            700: `rgba(var(--${key}-700), <alpha-value>)`,
            800: `rgba(var(--${key}-800), <alpha-value>)`,
            900: `rgba(var(--${key}-900), <alpha-value>)`,
            950: `rgba(var(--${key}-950), <alpha-value>)`,
        };
    });

    return colors;
};

export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    safelist: [
        'ql-editor',
        'quill-content',
    ],

    theme: {
        extend: {
            colors: {
                custom: {
                    50: 'rgba(var(--c-50), <alpha-value>)',
                    100: 'rgba(var(--c-100), <alpha-value>)',
                    200: 'rgba(var(--c-200), <alpha-value>)',
                    300: 'rgba(var(--c-300), <alpha-value>)',
                    400: 'rgba(var(--c-400), <alpha-value>)',
                    500: 'rgba(var(--c-500), <alpha-value>)',
                    600: 'rgba(var(--c-600), <alpha-value>)',
                    700: 'rgba(var(--c-700), <alpha-value>)',
                    800: 'rgba(var(--c-800), <alpha-value>)',
                    900: 'rgba(var(--c-900), <alpha-value>)',
                    950: 'rgba(var(--c-950), <alpha-value>)',
                },
                ...buildColors(),
            },

            fontFamily: {
                sans: ['var(--font-family)', ...defaultTheme.fontFamily.sans],
                serif: ['var(--font-serif-family, PT Serif)', ...defaultTheme.fontFamily.serif],
                mono: ['var(--font-mono-family, ui-monospace)', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [
        require('@tailwindcss/typography'),
    ],
}
