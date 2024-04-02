const theme = require('./theme.json');
const tailpress = require("@jeffreyvr/tailwindcss-tailpress");
// const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './*.{php,js}',
        './*/*.{php,js}',
        './*/*/*.{php,js}',
        './*/*/*/*.{php,js}',
        './woocommerce/myaccount/*.php',
    ],
    theme: {
        container: {
            padding: {
                DEFAULT: '1rem',
                sm: '2rem',
                lg: '0rem'
            },
        },
        extend: {
            colors: {
                main: '#faf8f0',
                dark: '#1F2937',
                primary: '#1e5c46',
                secondary: '#8d021c',
                secondaryLight: '#C00326',
                input: '#9f763c',
                inputFill: '#FCF6E6',
            },
            // colors: tailpress.colorMapper(tailpress.theme('settings.color.palette', theme)),
            fontSize: tailpress.fontSizeMapper(tailpress.theme('settings.typography.fontSizes', theme))
        },
        screens: {
            'xs': '480px',
            'sm': '600px',
            'md': '782px',
            'lg': tailpress.theme('settings.layout.contentSize', theme),
            'xl': tailpress.theme('settings.layout.wideSize', theme),
            '2xl': '1440px'
        }
    },
    plugins: [
        tailpress.tailwind,
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ]
};
