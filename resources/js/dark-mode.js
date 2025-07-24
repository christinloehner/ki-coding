/**
 * Dark Mode Implementation for KI-Coding
 * 
 * Features:
 * - System preference detection
 * - Manual toggle with localStorage persistence
 * - Smooth transitions
 * - Accessibility support
 * - Barrierefreie Kontraste (WCAG 2.1 AA)
 */

class DarkModeManager {
    constructor() {
        this.prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        this.currentTheme = this.getStoredTheme();
        
        this.init();
    }

    /**
     * Initialize dark mode
     */
    init() {
        // Get current theme from DOM (already set by inline script to prevent FOUC)
        const currentThemeFromDOM = document.documentElement.getAttribute('data-theme') || 'light';
        this.currentTheme = currentThemeFromDOM;
        
        // Only update icons, don't set theme (already set by inline script)
        this.updateToggleIcons(this.currentTheme === 'dark');
        
        // Bind events
        this.bindEvents();
        
        // Listen for system preference changes
        this.prefersDarkScheme.addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                this.applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    /**
     * Get stored theme preference or detect system preference
     * @returns {string} 'light', 'dark', or 'auto'
     */
    getStoredTheme() {
        const storedTheme = localStorage.getItem('theme');
        
        if (storedTheme) {
            return storedTheme;
        }
        
        // If no stored preference, use system preference
        return this.prefersDarkScheme.matches ? 'dark' : 'light';
    }

    /**
     * Apply theme to the document
     * @param {string} theme - 'light' or 'dark'
     */
    applyTheme(theme) {
        const html = document.documentElement;
        
        if (theme === 'dark') {
            html.setAttribute('data-theme', 'dark');
            this.updateToggleIcons(true);
        } else {
            html.setAttribute('data-theme', 'light');
            this.updateToggleIcons(false);
        }
        
        this.currentTheme = theme;
        
        // Store preference
        localStorage.setItem('theme', theme);
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme: theme }
        }));
    }

    /**
     * Toggle between light and dark mode
     */
    toggle() {
        const newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        this.applyTheme(newTheme);
        
        // Add smooth transition class temporarily
        document.body.classList.add('theme-transitioning');
        setTimeout(() => {
            document.body.classList.remove('theme-transitioning');
        }, 300);
    }

    /**
     * Update toggle button icons
     * @param {boolean} isDark - Current dark mode state
     */
    updateToggleIcons(isDark) {
        // Desktop icons
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');
        
        // Mobile icons
        const sunIconMobile = document.getElementById('sunIconMobile');
        const moonIconMobile = document.getElementById('moonIconMobile');
        
        if (isDark) {
            // Show moon icon (dark mode is active)
            if (sunIcon) sunIcon.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
            if (sunIconMobile) sunIconMobile.classList.add('hidden');
            if (moonIconMobile) moonIconMobile.classList.remove('hidden');
        } else {
            // Show sun icon (light mode is active)
            if (sunIcon) sunIcon.classList.remove('hidden');
            if (moonIcon) moonIcon.classList.add('hidden');
            if (sunIconMobile) sunIconMobile.classList.remove('hidden');
            if (moonIconMobile) moonIconMobile.classList.add('hidden');
        }
    }

    /**
     * Bind toggle button events
     */
    bindEvents() {
        // Desktop toggle
        const desktopToggle = document.getElementById('darkModeToggle');
        if (desktopToggle) {
            desktopToggle.addEventListener('click', () => this.toggle());
            
            // Keyboard accessibility
            desktopToggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggle();
                }
            });
        }

        // Mobile toggle
        const mobileToggle = document.getElementById('darkModeToggleMobile');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => this.toggle());
            
            // Keyboard accessibility
            mobileToggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggle();
                }
            });
        }
    }

    /**
     * Get current theme
     * @returns {string} Current theme ('light' or 'dark')
     */
    getCurrentTheme() {
        return this.currentTheme;
    }

    /**
     * Check if dark mode is active
     * @returns {boolean} Is dark mode active
     */
    isDarkMode() {
        return this.currentTheme === 'dark';
    }
}

// Add smooth transition CSS for theme switching
const transitionCSS = `
    .theme-transitioning * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease !important;
    }
`;

// Inject transition CSS
const styleSheet = document.createElement('style');
styleSheet.textContent = transitionCSS;
document.head.appendChild(styleSheet);

// Initialize dark mode when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.darkModeManager = new DarkModeManager();
    });
} else {
    window.darkModeManager = new DarkModeManager();
}

// Export for use in other modules
window.DarkModeManager = DarkModeManager;