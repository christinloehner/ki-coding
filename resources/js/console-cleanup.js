/**
 * Console Cleanup - Unterdrückt bekannte harmlose Warnungen
 * Muss früh geladen werden, um SES-Warnungen abzufangen
 */

// Sofort ausführen (IIFE)
(function() {
    'use strict';
    
    // Original Console-Methoden sichern
    const originalWarn = console.warn;
    const originalLog = console.log;
    
    // Bekannte harmlose Warnungen filtern
    const suppressedWarnings = [
        'SES The \'dateTaming\' option is deprecated',
        'SES The \'mathTaming\' option is deprecated',
        'SES Removing unpermitted intrinsics',
        'Removing intrinsics.%DatePrototype%.toTemporalInstant',
        'Referrer Policy: Die weniger eingeschränkte Referrer Policy',
        'The resource was preloaded using link preload but not used within a few seconds'
    ];
    
    // Console.warn überschreiben
    console.warn = function(...args) {
        const message = args.join(' ');
        const shouldSuppress = suppressedWarnings.some(warning => 
            message.includes(warning)
        );
        
        if (!shouldSuppress) {
            originalWarn.apply(console, args);
        }
    };
    
    // Console.log für bestimmte Libraries überschreiben  
    console.log = function(...args) {
        const message = args.join(' ');
        
        // Klaro-Callback Logs unterdrücken außer in Development Mode
        if (!window.location.hostname.includes('dev') && !window.location.hostname.includes('localhost')) {
            if (message.includes('Klaro Callback:') || 
                message.includes('aktiviert') || 
                message.includes('deaktiviert')) {
                return;
            }
        }
        
        originalLog.apply(console, args);
    };
    
    // Globaler Error Handler für SES Exceptions
    window.addEventListener('error', function(event) {
        if (event.error && event.error.message && 
            (event.error.message.includes('SES_UNCAUGHT_EXCEPTION') ||
             event.error.message.includes('lockdown-install'))) {
            event.preventDefault();
            return false;
        }
    });

    // Development-Mode Indicator
    if (window.location.hostname.includes('dev') || window.location.hostname.includes('localhost')) {
        originalLog.call(console, '🚀 Development Mode - Console-Cleanup aktiv');
    }
})();