/**
 * Early Console Cleanup - Lädt vor allen anderen Scripts
 * Unterdrückt SES-Warnungen und andere bekannte harmlose Meldungen
 */

(function() {
    'use strict';
    
    // Original Console-Methoden sichern
    const originalWarn = console.warn;
    const originalLog = console.log;
    const originalError = console.error;
    
    // Bekannte harmlose SES-Warnungen
    const sesSuppressedMessages = [
        'SES The \'dateTaming\' option is deprecated',
        'SES The \'mathTaming\' option is deprecated', 
        'SES Removing unpermitted intrinsics',
        'Removing intrinsics.%DatePrototype%.toTemporalInstant'
    ];
    
    // Referrer Policy Warnungen
    const referrerPolicyMessages = [
        'Referrer Policy: Die weniger eingeschränkte Referrer Policy',
        'no-referrer-when-downgrade'
    ];
    
    // Alle Warnungen zusammen
    const allSuppressedMessages = [...sesSuppressedMessages, ...referrerPolicyMessages];
    
    // Console.warn überschreiben
    console.warn = function(...args) {
        const message = args.join(' ');
        const shouldSuppress = allSuppressedMessages.some(suppressedMsg => 
            message.includes(suppressedMsg)
        );
        
        if (!shouldSuppress) {
            originalWarn.apply(console, args);
        }
    };
    
    // Console.log überschreiben
    console.log = function(...args) {
        const message = args.join(' ');
        
        // SES-Log Nachrichten unterdrücken
        if (allSuppressedMessages.some(suppressedMsg => message.includes(suppressedMsg))) {
            return;
        }
        
        // Klaro-Callbacks nur in Dev anzeigen
        const isDev = window.location.hostname.includes('dev') || window.location.hostname.includes('localhost');
        if (!isDev && (message.includes('Klaro Callback:') || 
                      message.includes('aktiviert') || 
                      message.includes('deaktiviert'))) {
            return;
        }
        
        originalLog.apply(console, args);
    };
    
    // Console.error für SES Exceptions überschreiben
    console.error = function(...args) {
        const message = args.join(' ');
        
        if (message.includes('SES_UNCAUGHT_EXCEPTION') || 
            message.includes('lockdown-install')) {
            return;
        }
        
        originalError.apply(console, args);
    };
    
    // Globaler Error Handler
    window.addEventListener('error', function(event) {
        if (event.error && event.error.message) {
            const msg = event.error.message;
            if (msg.includes('SES_UNCAUGHT_EXCEPTION') ||
                msg.includes('lockdown-install') ||
                allSuppressedMessages.some(suppressedMsg => msg.includes(suppressedMsg))) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        }
    }, true);
    
    // Unhandled Promise Rejections für SES
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.message) {
            const msg = event.reason.message;
            if (msg.includes('SES_UNCAUGHT_EXCEPTION') || 
                msg.includes('lockdown-install')) {
                event.preventDefault();
                return false;
            }
        }
    });
    
})();