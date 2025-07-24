/**
 * Category Colors Helper for Dark Mode
 * Handles dynamic category colors with proper dark mode support
 */

class CategoryColorManager {
    constructor() {
        this.init();
    }

    /**
     * Initialize category color management
     */
    init() {
        this.applyDynamicColors();
        
        // Listen for theme changes
        document.addEventListener('themeChanged', () => {
            this.applyDynamicColors();
        });
    }

    /**
     * Apply dynamic category colors with dark mode support
     */
    applyDynamicColors() {
        const categoryElements = document.querySelectorAll('[data-category-color]');
        
        categoryElements.forEach(element => {
            const color = element.dataset.categoryColor;
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            this.applyCategoryColor(element, color, isDark);
        });
    }

    /**
     * Apply category color to element with dark mode adjustments
     * @param {HTMLElement} element - Target element
     * @param {string} color - Hex color code
     * @param {boolean} isDark - Is dark mode active
     */
    applyCategoryColor(element, color, isDark) {
        if (!color || !element) return;

        // Convert hex to RGB
        const rgb = this.hexToRgb(color);
        if (!rgb) return;

        // Apply background with appropriate opacity
        const bgOpacity = isDark ? 0.25 : 0.125;
        element.style.backgroundColor = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${bgOpacity})`;

        // Apply text color with dark mode adjustments
        if (element.classList.contains('category-color-text') || element.querySelector('.category-color-text')) {
            const textElement = element.classList.contains('category-color-text') 
                ? element 
                : element.querySelector('.category-color-text');
                
            if (textElement) {
                if (isDark) {
                    // Make colors brighter and less saturated for dark mode
                    const adjustedColor = this.adjustColorForDarkMode(color);
                    textElement.style.color = adjustedColor;
                } else {
                    textElement.style.color = color;
                }
            }
        }
    }

    /**
     * Convert hex color to RGB
     * @param {string} hex - Hex color code
     * @returns {Object|null} RGB object or null
     */
    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    /**
     * Adjust color for better visibility in dark mode
     * @param {string} color - Original hex color
     * @returns {string} Adjusted hex color
     */
    adjustColorForDarkMode(color) {
        const rgb = this.hexToRgb(color);
        if (!rgb) return color;

        // Convert to HSL for better color manipulation
        const hsl = this.rgbToHsl(rgb.r, rgb.g, rgb.b);
        
        // Increase lightness and decrease saturation for dark mode
        hsl.l = Math.min(0.8, hsl.l + 0.3); // Increase lightness
        hsl.s = Math.max(0.4, hsl.s - 0.1); // Decrease saturation slightly
        
        // Convert back to RGB and then to hex
        const adjustedRgb = this.hslToRgb(hsl.h, hsl.s, hsl.l);
        return this.rgbToHex(adjustedRgb.r, adjustedRgb.g, adjustedRgb.b);
    }

    /**
     * Convert RGB to HSL
     */
    rgbToHsl(r, g, b) {
        r /= 255;
        g /= 255;
        b /= 255;

        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
        let h, s, l = (max + min) / 2;

        if (max === min) {
            h = s = 0;
        } else {
            const d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);

            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }

        return { h, s, l };
    }

    /**
     * Convert HSL to RGB
     */
    hslToRgb(h, s, l) {
        let r, g, b;

        if (s === 0) {
            r = g = b = l;
        } else {
            const hue2rgb = (p, q, t) => {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1/6) return p + (q - p) * 6 * t;
                if (t < 1/2) return q;
                if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
                return p;
            };

            const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            const p = 2 * l - q;
            r = hue2rgb(p, q, h + 1/3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1/3);
        }

        return {
            r: Math.round(r * 255),
            g: Math.round(g * 255),
            b: Math.round(b * 255)
        };
    }

    /**
     * Convert RGB to hex
     */
    rgbToHex(r, g, b) {
        return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.categoryColorManager = new CategoryColorManager();
    });
} else {
    window.categoryColorManager = new CategoryColorManager();
}

// Export for use in other modules
window.CategoryColorManager = CategoryColorManager;