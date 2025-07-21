// Klaro Cookie Consent Konfiguration für KI-Coding
window.klaro = window.klaro || {};
window.klaro.version = 'v1';

window.klaroConfig = {
    // Version der Konfiguration
    version: 1,

    // Element ID für das Cookie Modal
    elementID: 'klaro',

    // Storage-Methode
    storageMethod: 'localStorage',

    // Cookie Name für die Einstellungen
    cookieName: 'ki-coding-klaro',

    // Cookie Domain
    cookieDomain: 'ki-coding.de',

    // Cookie Pfad
    cookiePath: '/',

    // Cookie Expires in Tagen
    cookieExpiresAfterDays: 365,

    // Standard Sprache
    lang: 'de',

    // Verfügbare Sprachen
    translations: {
        de: {
            // Allgemeine Übersetzungen
            consentModal: {
                title: 'Cookie-Einstellungen für KI-Coding',
                description: 'Wir verwenden Cookies, um dir die bestmögliche Nutzung unserer Website zu ermöglichen. Einige sind notwendig für den Betrieb der Website, andere helfen uns, diese Website zu verbessern.',
                privacyPolicy: {
                    name: 'Datenschutzerklärung',
                    text: 'Weitere Informationen findest du in unserer {privacyPolicy}.',
                }
            },
            consentNotice: {
                changeDescription: 'Seit Ihrem letzten Besuch haben sich Änderungen ergeben. Bitte aktualisieren Sie Ihre Einwilligung.',
                description: 'Wir verwenden Cookies und andere Tracking-Technologien, um Ihr Browsing-Erlebnis auf unserer Website zu verbessern, Ihnen personalisierte Inhalte und gezielte Anzeigen anzuzeigen, unseren Website-Traffic zu analysieren und zu verstehen, woher unsere Besucher kommen.',
                learnMore: 'Mehr erfahren',
                testing: 'Test-Modus!'
            },
            ok: 'Alle akzeptieren',
            save: 'Einstellungen speichern',
            decline: 'Alle ablehnen',
            close: 'Schließen',
            acceptAll: 'Alle akzeptieren',
            acceptSelected: 'Auswahl akzeptieren',
            purposes: {
                essential: 'Notwendig',
                analytics: 'Analyse',
                marketing: 'Marketing',
                performance: 'Performance'
            }
        }
    },

    // Services (Cookie-Kategorien)
    services: [
        {
            name: 'essential',
            title: 'Notwendige Cookies',
            description: 'Diese Cookies sind für das ordnungsgemäße Funktionieren der Website erforderlich und können nicht deaktiviert werden.',
            purposes: ['essential'],
            required: true,
            optOut: false,
            onlyOnce: true,
            cookies: [
                {
                    name: 'XSRF-TOKEN',
                    description: 'CSRF-Schutz für Formulare'
                },
                {
                    name: /ki-coding.*session/,
                    description: 'Session-Cookie für die Website-Funktionalität'
                },
                {
                    name: 'ki-coding-klaro',
                    description: 'Speichert Ihre Cookie-Einstellungen'
                }
            ]
        },
        {
            name: 'matomo',
            title: 'Matomo Analytics',
            description: 'Matomo Analytics hilft uns zu verstehen, wie Besucher mit unserer Website interagieren. Alle Daten werden datenschutzkonform und anonym verarbeitet.',
            purposes: ['analytics'],
            required: false,
            optOut: true,
            onAccept: function() {
                // Matomo Tracking Code laden
                if (!window._paq) {
                    window._paq = window._paq || [];
                    window._paq.push(["setDoNotTrack", true]);
                    window._paq.push(['trackPageView']);
                    window._paq.push(['enableLinkTracking']);
                    
                    (function() {
                        var u="https://matomo.christin-loehner.de/";
                        window._paq.push(['setTrackerUrl', u+'matomo.php']);
                        window._paq.push(['setSiteId', '9']);
                        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                        g.async=true; 
                        g.src=u+'matomo.js'; 
                        s.parentNode.insertBefore(g,s);
                    })();
                    
                    // Noscript Element für Matomo hinzufügen
                    if (!document.querySelector('noscript img[src*="matomo.christin-loehner.de"]')) {
                        const noscript = document.createElement('noscript');
                        const img = document.createElement('p');
                        img.innerHTML = '<img referrerpolicy="no-referrer-when-downgrade" src="https://matomo.christin-loehner.de/matomo.php?idsite=9&amp;rec=1" style="border:0;" alt="" />';
                        noscript.appendChild(img);
                        document.head.appendChild(noscript);
                    }
                }
                console.log('Matomo Analytics aktiviert');
            },
            onDecline: function() {
                // Matomo deaktivieren
                if (window._paq) {
                    window._paq = undefined;
                }
                // Matomo Script entfernen falls vorhanden
                const matomoScripts = document.querySelectorAll('script[src*="matomo.js"], script[src*="matomo.christin-loehner.de"]');
                matomoScripts.forEach(script => {
                    if (script.parentNode) {
                        script.parentNode.removeChild(script);
                    }
                });
                // Noscript Element entfernen
                const noscriptElements = document.querySelectorAll('noscript img[src*="matomo.christin-loehner.de"]');
                noscriptElements.forEach(img => {
                    const noscript = img.closest('noscript');
                    if (noscript && noscript.parentNode) {
                        noscript.parentNode.removeChild(noscript);
                    }
                });
                console.log('Matomo Analytics deaktiviert');
            },
            cookies: [
                {
                    name: '_pk_id',
                    description: 'Matomo - Benutzeridentifikation'
                },
                {
                    name: '_pk_ses',
                    description: 'Matomo - Session-Tracking'
                },
                {
                    name: /^_pk_/,
                    description: 'Matomo - Analytics Cookies'
                }
            ]
        },
        {
            name: 'performance',
            title: 'Performance-Cookies',
            description: 'Diese Cookies sammeln Informationen darüber, wie die Website genutzt wird, um die Leistung zu verbessern.',
            purposes: ['performance'],
            required: false,
            optOut: true,
            cookies: [
                {
                    name: '_gat',
                    description: 'Google Analytics - Rate Limiting'
                }
            ]
        },
        {
            name: 'recaptcha',
            title: 'Google reCAPTCHA',
            description: 'Schutz vor Spam und Missbrauch durch reCAPTCHA von Google. Erforderlich für Kontaktformulare.',
            purposes: ['essential'],
            required: false,
            optOut: true,
            onAccept: function() {
                // reCAPTCHA Script laden
                if (!window.grecaptcha && !document.querySelector('script[src*="recaptcha"]')) {
                    const script = document.createElement('script');
                    script.src = 'https://www.google.com/recaptcha/api.js';
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                }
                console.log('reCAPTCHA aktiviert');
            },
            onDecline: function() {
                // reCAPTCHA entfernen
                const recaptchaElements = document.querySelectorAll('.g-recaptcha');
                recaptchaElements.forEach(el => {
                    el.style.display = 'none';
                });
                console.log('reCAPTCHA deaktiviert');
            },
            cookies: [
                {
                    name: '_GRECAPTCHA',
                    description: 'Google reCAPTCHA - Anti-Spam Schutz'
                },
                {
                    name: /^_ga.*$/,
                    description: 'Google reCAPTCHA - Analytics'
                }
            ]
        }
    ],

    // Callback-Funktionen
    callback: function(consent, service) {
        console.log('Klaro Callback:', consent, service);
    },

    // Styling-Optionen
    styling: {
        theme: ['light'],
    },

    // Weitere Optionen
    mustConsent: false,
    acceptAll: true,
    hideDeclineAll: false,
    hideLearnMore: false,
    noticeAsModal: false,
    disablePoweredBy: true,
    embedded: false,
    groupByPurpose: true,
    storageMethod: 'cookie',
    htmlTexts: true,
    
    // Privacy Policy Link
    privacyPolicy: '/privacy'
};