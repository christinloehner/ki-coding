import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Hauptfarben basierend auf APB Logo
                primary: {
                    50: '#eef7f1',
                    100: '#d8f0de',
                    200: '#b3e2c2',
                    300: '#81d09e',
                    400: '#45bf73',
                    500: '#45a049', // Logo Grün - leicht intensiver
                    600: '#1fb854',
                    700: '#159844',
                    800: '#137a39',
                    900: '#124f2a',
                    950: '#052e16',
                },
                secondary: {
                    50: '#fdf6ed',
                    100: '#fcedd8',
                    200: '#f9d9b1',
                    300: '#f5c084',
                    400: '#f0a255',
                    500: '#d4721e', // Logo Orange - leicht intensiver
                    600: '#e04e0a',
                    700: '#bb3a09',
                    800: '#942f10',
                    900: '#762910',
                    950: '#431407',
                },
                accent: {
                    50: '#edf4ff',
                    100: '#d6e7fe',
                    200: '#b8d8fd',
                    300: '#89c2fc',
                    400: '#579df9',
                    500: '#2e85c7', // Logo Blau - leicht intensiver
                    600: '#2259e1',
                    700: '#1a47d4',
                    800: '#1b3ca6',
                    900: '#1c3582',
                    950: '#172554',
                },
                purple: {
                    50: '#f3f1ff',
                    100: '#e9e5fe',
                    200: '#d7d0fd',
                    300: '#bba9fc',
                    400: '#9d7ef9',
                    500: '#8048f4',
                    600: '#7230ea',
                    700: '#6521d5',
                    800: '#541cb1',
                    900: '#451990',
                    950: '#2e1065',
                },
                teal: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                    950: '#042f2e',
                },
                pink: {
                    50: '#fdf2f8',
                    100: '#fce7f3',
                    200: '#fbcfe8',
                    300: '#f9a8d4',
                    400: '#f472b6',
                    500: '#ec4899',
                    600: '#db2777',
                    700: '#be185d',
                    800: '#9d174d',
                    900: '#831843',
                    950: '#500724',
                },
            },
            backgroundImage: {
                'gradient-primary': 'linear-gradient(135deg, var(--tw-gradient-stops))',
                'gradient-sunset': 'linear-gradient(135deg, #f5a95e 0%, #ec4899 50%, #7c3aed 100%)',
                'gradient-ocean': 'linear-gradient(135deg, #60a5fa 0%, #14b8a6 50%, #22c55e 100%)',
                'gradient-forest': 'linear-gradient(135deg, #52c57d 0%, #0d9488 100%)',
                'gradient-dawn': 'linear-gradient(135deg, #f9c88d 0%, #52c57d 100%)',
                'gradient-dark': 'linear-gradient(135deg, #1f2937 0%, #111827 100%)',
                'gradient-night': 'linear-gradient(135deg, #4c1d95 0%, #111827 100%)',
            },
            boxShadow: {
                'primary': '0 4px 14px 0 rgba(76, 175, 80, 0.3)',
                'secondary': '0 4px 14px 0 rgba(230, 126, 34, 0.3)',
                'accent': '0 4px 14px 0 rgba(52, 152, 219, 0.3)',
                'purple': '0 4px 14px 0 rgba(139, 92, 246, 0.3)',
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
            },
            backdropBlur: {
                'glass': '10px',
            },
            typography: {
                DEFAULT: {
                    css: {
                        maxWidth: 'none',
                        color: '#1f2937',
                        h1: {
                            fontFamily: 'Poppins, sans-serif',
                            fontWeight: '700',
                        },
                        h2: {
                            fontFamily: 'Poppins, sans-serif',
                            fontWeight: '600',
                        },
                        h3: {
                            fontFamily: 'Poppins, sans-serif',
                            fontWeight: '600',
                        },
                        h4: {
                            fontFamily: 'Poppins, sans-serif',
                            fontWeight: '600',
                        },
                        a: {
                            color: '#4CAF50',
                            '&:hover': {
                                color: '#16a34a',
                            },
                        },
                        code: {
                            backgroundColor: '#f1f5f9',
                            color: '#1f2937',
                            padding: '0.25rem 0.5rem',
                            borderRadius: '0.25rem',
                            fontSize: '0.875rem',
                        },
                        'code::before': {
                            content: '""',
                        },
                        'code::after': {
                            content: '""',
                        },
                        pre: {
                            backgroundColor: '#1f2937',
                            color: '#f9fafb',
                        },
                        'pre code': {
                            backgroundColor: 'transparent',
                            color: '#f9fafb',
                        },
                        blockquote: {
                            borderLeftColor: '#4CAF50',
                        },
                    },
                },
            },
        },
    },

    plugins: [forms, typography],
};
