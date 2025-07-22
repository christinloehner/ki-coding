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
            // MODERNE SPACING für kompakteres Design
            spacing: {
                '4.5': '1.125rem',   // 18px
                '5.5': '1.375rem',   // 22px
                '6.5': '1.625rem',   // 26px
                '7.5': '1.875rem',   // 30px
                '8.5': '2.125rem',   // 34px
                '9.5': '2.375rem',   // 38px
                '15': '3.75rem',     // 60px
                '18': '4.5rem',      // 72px
                '22': '5.5rem',      // 88px
                '26': '6.5rem',      // 104px
                '30': '7.5rem',      // 120px
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            // MODERNE FONT SIZES
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                '5xl': ['3rem', { lineHeight: '1.1' }],
                '6xl': ['3.75rem', { lineHeight: '1.1' }],
                '7xl': ['4.5rem', { lineHeight: '1.1' }],
                '8xl': ['6rem', { lineHeight: '1.1' }],
                '9xl': ['8rem', { lineHeight: '1.1' }],
            },
            colors: {
                // Hauptfarben basierend auf APB Logo - INTENSIV & KONTRASTREICH für Barrierefreiheit
                primary: {
                    50: '#e8f5e9',  // Sehr helles Grün für Hintergründe
                    100: '#c8e6c9', // Heller für Info-Boxen
                    200: '#a5d6a7', // Leichte Akzente
                    300: '#81c784', // Mittlere Abstufung
                    400: '#66bb6a', // Aktive States
                    500: '#2e7d32', // LOGO GRÜN - Viel intensiver und kontrastreich (WCAG AA)
                    600: '#1b5e20', // Dunklere Variante für besseren Kontrast
                    700: '#145a1f', // Hover States
                    800: '#0d4715', // Pressed States
                    900: '#063309', // Sehr dunkle Variante
                    950: '#041f05', // Dunkelste für maximalen Kontrast
                },
                secondary: {
                    50: '#fff3e0',  // Helles Orange für Backgrounds
                    100: '#ffe0b3', // Info-Bereiche
                    200: '#ffcc80', // Leichte Akzente
                    300: '#ffb74d', // Mittlere Töne
                    400: '#ffa726', // Aktive Zustände
                    500: '#d84315', // LOGO ORANGE/BRAUN - Intensiv und kontrastreich (WCAG AA)
                    600: '#bf360c', // Dunklere Variante
                    700: '#a62d00', // Hover States
                    800: '#8f2500', // Pressed States
                    900: '#6d1b00', // Dunkle Variante
                    950: '#4a1200', // Dunkelste für Text auf hellem Grund
                },
                accent: {
                    50: '#e3f2fd',  // Helles Blau
                    100: '#bbdefb', // Info-Boxen
                    200: '#90caf9', // Leichte Akzente
                    300: '#64b5f6', // Mittlere Abstufung
                    400: '#42a5f5', // Aktive States
                    500: '#1565c0', // LOGO BLAU - Intensiv und kontrastreich (WCAG AA+)
                    600: '#0d47a1', // Dunklere Variante für besseren Kontrast
                    700: '#0a3d91', // Hover States
                    800: '#073282', // Pressed States
                    900: '#042461', // Sehr dunkel
                    950: '#021640', // Dunkelste Variante
                },
                // Ergänzende Farben für bessere Barrierefreiheit
                success: {
                    50: '#e8f5e9',
                    500: '#2e7d32', // Grün für Erfolg - hoher Kontrast
                    600: '#1b5e20',
                    700: '#145a1f',
                },
                warning: {
                    50: '#fff8e1',
                    500: '#f57c00', // Orange für Warnungen - WCAG AA
                    600: '#e65100',
                    700: '#bf360c',
                },
                error: {
                    50: '#ffebee',
                    500: '#d32f2f', // Rot für Fehler - hoher Kontrast
                    600: '#c62828',
                    700: '#b71c1c',
                },
                info: {
                    50: '#e3f2fd',
                    500: '#1565c0', // Blau für Info - hoher Kontrast
                    600: '#0d47a1',
                    700: '#0a3d91',
                },
                // Neutrale Farben mit hohem Kontrast für Barrierefreiheit
                neutral: {
                    50: '#fafafa',   // Sehr hell für Hintergründe
                    100: '#f5f5f5',  // Cards/Container
                    200: '#e5e5e5',  // Borders
                    300: '#d4d4d4',  // Disabled States
                    400: '#a3a3a3',  // Placeholder Text
                    500: '#737373',  // Secondary Text (WCAG AA auf Weiß)
                    600: '#525252',  // Body Text (WCAG AA)
                    700: '#404040',  // Headings (WCAG AA+)
                    800: '#262626',  // Dark Text (WCAG AAA)
                    900: '#171717',  // Darkest Text
                    950: '#0a0a0a',  // Maximum Contrast
                },
            },
            // MODERNE BACKGROUND PATTERNS & GRADIENTS
            backgroundImage: {
                // Logo-Farben Gradients - Modern und elegant
                'gradient-primary': 'linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%)', // Grün
                'gradient-secondary': 'linear-gradient(135deg, #d84315 0%, #bf360c 100%)', // Orange
                'gradient-accent': 'linear-gradient(135deg, #1565c0 0%, #0d47a1 100%)', // Blau
                'gradient-logo': 'linear-gradient(135deg, #2e7d32 0%, #1565c0 50%, #d84315 100%)', // Logo-Farben
                'gradient-logo-soft': 'linear-gradient(135deg, rgba(46, 125, 50, 0.8) 0%, rgba(21, 101, 192, 0.8) 50%, rgba(216, 67, 21, 0.8) 100%)',
                
                // Status Gradients
                'gradient-success': 'linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%)',
                'gradient-warning': 'linear-gradient(135deg, #f57c00 0%, #e65100 100%)',
                'gradient-error': 'linear-gradient(135deg, #d32f2f 0%, #c62828 100%)',
                'gradient-info': 'linear-gradient(135deg, #1565c0 0%, #0d47a1 100%)',
                
                // Moderne Neutral Gradients
                'gradient-dark': 'linear-gradient(135deg, #262626 0%, #171717 100%)',
                'gradient-light': 'linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%)',
                'gradient-glass': 'linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.1) 100%)',
                
                // Hero Gradients
                'gradient-hero': 'linear-gradient(135deg, rgba(46, 125, 50, 0.1) 0%, rgba(255, 255, 255, 0.8) 25%, rgba(21, 101, 192, 0.1) 100%)',
                'gradient-hero-dark': 'linear-gradient(135deg, rgba(38, 38, 38, 0.95) 0%, rgba(23, 23, 23, 1) 100%)',
                
                // Moderne Patterns
                'pattern-dots': 'radial-gradient(rgba(46, 125, 50, 0.1) 1px, transparent 1px)',
                'pattern-grid': 'linear-gradient(rgba(46, 125, 50, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(46, 125, 50, 0.05) 1px, transparent 1px)',
            },
            // MODERNE SCHATTEN - Subtiler und eleganter
            boxShadow: {
                'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'sm': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                '2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                'inner': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
                // Moderne colored shadows
                'primary': '0 10px 25px -5px rgba(46, 125, 50, 0.25), 0 10px 10px -5px rgba(46, 125, 50, 0.04)',
                'secondary': '0 10px 25px -5px rgba(216, 67, 21, 0.25), 0 10px 10px -5px rgba(216, 67, 21, 0.04)',
                'accent': '0 10px 25px -5px rgba(21, 101, 192, 0.25), 0 10px 10px -5px rgba(21, 101, 192, 0.04)',
                // Glassmorphism shadows
                'glass': '0 8px 32px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(0, 0, 0, 0.05)',
                'glass-lg': '0 16px 64px rgba(0, 0, 0, 0.15), 0 8px 32px rgba(0, 0, 0, 0.1)',
                // Modern elevation shadows
                'elevation-1': '0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24)',
                'elevation-2': '0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)',
                'elevation-3': '0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23)',
                'elevation-4': '0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22)',
                'elevation-5': '0 19px 38px rgba(0, 0, 0, 0.30), 0 15px 12px rgba(0, 0, 0, 0.22)',
            },
            // MODERNE BORDER RADIUS
            borderRadius: {
                'none': '0px',
                'sm': '0.25rem',     // 4px
                'md': '0.375rem',    // 6px  
                'lg': '0.5rem',      // 8px
                'xl': '0.75rem',     // 12px
                '2xl': '1rem',       // 16px - Modern default
                '3xl': '1.5rem',     // 24px - Sehr modern
                '4xl': '2rem',       // 32px - Hero elements
                'full': '9999px',
            },
            // BACKDROP BLUR für Glassmorphism
            backdropBlur: {
                'none': '0',
                'sm': '4px',
                'md': '12px',
                'lg': '16px',
                'xl': '24px',
                '2xl': '40px',
                '3xl': '64px',
                'glass': '12px',     // Standard Glassmorphism
                'glass-heavy': '24px', // Heavy Glassmorphism
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
