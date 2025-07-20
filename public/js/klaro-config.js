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
            name: 'analytics',
            title: 'Analyse-Cookies',
            description: 'Diese Cookies helfen uns zu verstehen, wie Besucher mit unserer Website interagieren, indem sie Informationen anonym sammeln und melden.',
            purposes: ['analytics'],
            required: false,
            optOut: true,
            onAccept: function() {
                // Hier würde Google Analytics aktiviert werden
                console.log('Analytics Cookies akzeptiert');
            },
            onDecline: function() {
                // Hier würde Google Analytics deaktiviert werden
                console.log('Analytics Cookies abgelehnt');
            },
            cookies: [
                {
                    name: '_ga',
                    description: 'Google Analytics - Hauptcookie'
                },
                {
                    name: '_gid',
                    description: 'Google Analytics - Session-basierte Identifikation'
                },
                {
                    name: /^_ga_/,
                    description: 'Google Analytics 4 - Property-spezifische Cookies'
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
            name: 'matomo',
            title: 'Matomo Analytics',
            description: 'Datenschutzfreundliche Website-Analyse mit Matomo. Hilft uns, die Website zu verbessern, ohne Ihre Privatsphäre zu verletzen.',
            purposes: ['analytics'],
            required: false,
            optOut: true,
            onAccept: function() {
                // Matomo Script aktivieren
                const matomoScript = document.querySelector('script[data-name="matomo"]');
                if (matomoScript && matomoScript.type === 'text/plain') {
                    // Script von text/plain zu application/javascript ändern
                    matomoScript.type = 'application/javascript';
                    // Script-Inhalt in neuem Script ausführen
                    const newScript = document.createElement('script');
                    newScript.innerHTML = matomoScript.innerHTML;
                    document.head.appendChild(newScript);
                    
                    // NoScript Image anzeigen für Nutzer ohne JS
                    const noscriptElement = document.getElementById('matomo-noscript');
                    if (noscriptElement) {
                        noscriptElement.style.display = 'block';
                    }
                }
                
                // Opt-User back in, falls bereits opted out
                if (window._paq) {
                    window._paq.push(['forgetUserOptOut']);
                }
                console.log('Matomo Analytics aktiviert');
            },
            onDecline: function() {
                // Matomo tracking komplett stoppen
                if (window._paq) {
                    window._paq.push(['optUserOut']);
                    window._paq.push(['requireConsent']);
                }
                
                // NoScript Image verstecken
                const noscriptElement = document.getElementById('matomo-noscript');
                if (noscriptElement) {
                    noscriptElement.style.display = 'none';
                }
                
                console.log('Matomo Analytics deaktiviert');
            },
            cookies: [
                {
                    name: '_pk_id.*',
                    description: 'Matomo - Visitor ID Cookie'
                },
                {
                    name: '_pk_ses.*',
                    description: 'Matomo - Session Cookie'
                },
                {
                    name: '_pk_ref.*',
                    description: 'Matomo - Referrer Information'
                },
                {
                    name: '_pk_cvar.*',
                    description: 'Matomo - Custom Variables'
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