import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                display: ['Barlow Condensed', 'system-ui', 'sans-serif'],
                sans: ['DM Sans', 'system-ui', 'sans-serif'],
            },
            colors: {
                brand: {
                    dark: '#0A1A3A',
                    'dark-80': '#13254E',
                    'dark-60': '#203864',
                    accent: '#FF6A3A',
                    'accent-80': '#E3552B',
                    'accent-60': '#CC4824',
                    light: '#F4F5F7',
                    white: '#FFFFFF',
                    gray: '#D8DDE4',
                },
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.6s ease-out',
                'fade-in-down': 'fadeInDown 0.6s ease-out',
                'slide-in-right': 'slideInRight 0.5s ease-out',
                'pulse-accent': 'pulseAccent 2s ease-in-out infinite',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                pulseAccent: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
            },
            backgroundImage: {
                'industrial-grid': 'linear-gradient(to right, rgba(10, 26, 58, 0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(10, 26, 58, 0.03) 1px, transparent 1px)',
                'diagonal-stripes': 'repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 106, 58, 0.03) 10px, rgba(255, 106, 58, 0.03) 20px)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};

