@extends('layouts.app')

@section('title', 'Datenschutzerklärung - KI-Coding')
@section('description', 'Datenschutzerklärung der KI-Coding GmbH. Informationen über die Erhebung, Verarbeitung und Nutzung personenbezogener Daten gemäß DSGVO.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-50 via-white to-secondary-50 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 mb-6">
                Datenschutzerklärung
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Wir nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Diese Datenschutzerklärung informiert Sie über die Art, den Umfang und die Zwecke der Erhebung und Verwendung personenbezogener Daten.
            </p>
            <div class="text-sm text-gray-500">
                Stand: {{ date('d.m.Y') }}
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">
            
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">1. Verantwortlicher</h2>
            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <p class="mb-2"><strong>KI-Coding.de</strong></p>
                <p class="mb-2">c/o Christin Löhner</p>
                <p class="mb-2">Postfach 1119</p>
                <p class="mb-2">72187 Vöhringen</p>
                <p class="mb-2">Deutschland</p>
                <p class="mb-2">E-Mail: <a href="mailto:datenschutz@ki-coding.de" class="text-primary-600 hover:text-primary-700">datenschutz@ki-coding.de</a></p>
            </div>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">2. Datenschutzbeauftragte*r</h2>
            <p class="text-gray-600 mb-8">
                Unser Datenschutzbeauftragter steht Ihnen bei Fragen zum Datenschutz zur Verfügung:
            </p>
            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <p class="mb-2"><strong>Christin Löhner</strong></p>
                <p class="mb-2">E-Mail: <a href="mailto:datenschutz@ki-coding.de" class="text-primary-600 hover:text-primary-700">datenschutz@ki-coding.de</a></p>
            </div>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">3. Erhebung und Verarbeitung personenbezogener Daten</h2>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-4">3.1 Automatische Datenerhebung</h3>
            <p class="text-gray-600 mb-6">
                Beim Besuch unserer Website werden automatisch Informationen allgemeiner Natur erfasst. Diese Informationen (Server-Logfiles) beinhalten:
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                <li>Browsertyp und Browserversion</li>
                <li>Verwendetes Betriebssystem</li>
                <li>Referrer URL</li>
                <li>Hostname des zugreifenden Rechners</li>
                <li>Uhrzeit der Serveranfrage</li>
                <li>IP-Adresse</li>
            </ul>
            <p class="text-gray-600 mb-8">
                Diese Daten sind nicht bestimmten Personen zuordenbar. Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen. Wir behalten uns vor, diese Daten nachträglich zu prüfen, wenn uns konkrete Anhaltspunkte für eine rechtswidrige Nutzung bekannt werden.
            </p>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">3.2 Kontaktformular</h3>
            <p class="text-gray-600 mb-6">
                Wenn Sie unser Kontaktformular nutzen, werden die von Ihnen eingegebenen Daten an uns übermittelt und gespeichert. Diese Daten sind:
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                <li>Vor- und Nachname</li>
                <li>E-Mail-Adresse</li>
                <li>Unternehmen (optional)</li>
                <li>Telefonnummer (optional)</li>
                <li>Interesse für Service</li>
                <li>Nachrichteninhalt</li>
                <li>Zeitpunkt der Anfrage</li>
            </ul>
            <p class="text-gray-600 mb-8">
                Die Verarbeitung dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. a DSGVO (Einwilligung) und Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse). Unser berechtigtes Interesse besteht in der Bearbeitung Ihrer Anfrage und der Geschäftsanbahnung.
            </p>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">3.3 E-Mail-Verkehr</h3>
            <p class="text-gray-600 mb-8">
                Wenn Sie per E-Mail mit uns in Kontakt treten, werden Ihre Daten zur Bearbeitung der Anfrage und für den Fall von Anschlussfragen gespeichert. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">4. Cookies und Cookie-Management</h2>
            <p class="text-gray-600 mb-6">
                Unsere Website verwendet Cookies. Cookies sind kleine Textdateien, die auf Ihrem Computer gespeichert werden und die Ihr Browser speichert. Sie dienen dazu, unser Angebot nutzerfreundlicher, effektiver und sicherer zu machen.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-4">4.1 Cookie-Consent-Management</h3>
            <p class="text-gray-600 mb-6">
                Wir verwenden das Cookie-Consent-Management-System "Klaro" zur rechtskonformen Verwaltung von Cookies. Über dieses System können Sie Ihre Einwilligung zu verschiedenen Cookie-Kategorien erteilen oder verweigern.
            </p>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-yellow-800 font-semibold mb-2">Cookie-Einstellungen verwalten:</p>
                <p class="text-yellow-700 mb-2">
                    Sie können Ihre Cookie-Einstellungen jederzeit über den Link "Cookie-Einstellungen" im Footer dieser Website anpassen.
                </p>
                <button onclick="klaro.show(); return false;" 
                        class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition-colors">
                    Cookie-Einstellungen öffnen
                </button>
            </div>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-4">4.2 Cookie-Kategorien</h3>
            <p class="text-gray-600 mb-6">
                Wir verwenden verschiedene Arten von Cookies:
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                <li><strong>Notwendige Cookies:</strong> Diese sind für das ordnungsgemäße Funktionieren der Website erforderlich und können nicht deaktiviert werden. Sie umfassen Session-Cookies, CSRF-Schutz und Cookie-Einstellungen.</li>
                <li><strong>Analyse-Cookies:</strong> Diese helfen uns zu verstehen, wie Besucher mit unserer Website interagieren (nur mit Ihrer Einwilligung).</li>
                <li><strong>Performance-Cookies:</strong> Diese sammeln Informationen zur Verbesserung der Website-Leistung (nur mit Ihrer Einwilligung).</li>
                <li><strong>Funktionale Cookies:</strong> Diese ermöglichen erweiterte Funktionen wie reCAPTCHA (nur mit Ihrer Einwilligung).</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-4">4.3 Browser-Einstellungen</h3>
            <p class="text-gray-600 mb-8">
                Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und einzeln über deren Annahme entscheiden oder die Annahme von Cookies für bestimmte Fälle oder generell ausschließen. Beachten Sie jedoch, dass bei einer Deaktivierung von Cookies die Funktionalität unserer Website eingeschränkt sein kann.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">5. Externe Services und Drittanbieter</h2>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-4">5.1 Google reCAPTCHA</h3>
            <p class="text-gray-600 mb-4">
                Zum Schutz vor Spam und Missbrauch verwenden wir auf bestimmten Formularen (wie dem Kontaktformular) Google reCAPTCHA v2. Dieser Service wird nur geladen, wenn Sie Ihre Einwilligung über unser Cookie-Consent-System erteilen.
            </p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800 font-semibold mb-2">Wichtiger Hinweis zu reCAPTCHA:</p>
                <ul class="text-blue-700 space-y-1 text-sm">
                    <li>• <strong>Anbieter:</strong> Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland</li>
                    <li>• <strong>Zweck:</strong> Schutz vor automatisierten Anfragen (Bots) und Spam</li>
                    <li>• <strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. a DSGVO (Einwilligung)</li>
                    <li>• <strong>Datentransfer:</strong> Möglicherweise in die USA (angemessenes Schutzniveau durch EU-US Data Privacy Framework)</li>
                    <li>• <strong>Weitere Informationen:</strong> <a href="https://policies.google.com/privacy" class="underline" target="_blank" rel="noopener">Google Datenschutzerklärung</a></li>
                </ul>
            </div>
            <p class="text-gray-600 mb-8">
                Bei der Nutzung von reCAPTCHA können Daten wie Ihre IP-Adresse, Browser-Informationen und Ihr Verhalten auf der Website an Google übertragen werden. Sie können reCAPTCHA über unsere Cookie-Einstellungen deaktivieren, beachten Sie jedoch, dass dann bestimmte Formulare möglicherweise nicht verfügbar sind.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-4">5.2 Lokale Assets und DSGVO-Konformität</h3>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-800 font-semibold mb-2">DSGVO-konforme Implementierung:</p>
                <ul class="text-green-700 space-y-1 text-sm">
                    <li>• <strong>Schriften:</strong> Alle Webfonts (Google Fonts) werden lokal gehostet - keine Verbindung zu Google-Servern</li>
                    <li>• <strong>Icons:</strong> FontAwesome wird lokal bereitgestellt - keine CDN-Verbindungen</li>
                    <li>• <strong>Externe Inhalte:</strong> Werden nur nach Ihrer expliziten Einwilligung geladen</li>
                    <li>• <strong>IP-Schutz:</strong> Ihre IP-Adresse wird nicht an externe Dienste übertragen (außer bei reCAPTCHA mit Einwilligung)</li>
                </ul>
            </div>
            <p class="text-gray-600 mb-8">
                Wir haben technische Maßnahmen implementiert, um die Problematik des LG München Urteils vom 20.01.2022 (Az. 3 O 17493/20) bezüglich Google Fonts zu vermeiden. Alle typographischen Ressourcen werden von unseren eigenen Servern bereitgestellt.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">6. Weitergabe an Dritte</h2>
            <p class="text-gray-600 mb-8">
                Eine Übermittlung Ihrer persönlichen Daten an Dritte zu anderen als den im Folgenden aufgeführten Zwecken findet nicht statt. Wir geben Ihre persönlichen Daten nur an Dritte weiter, wenn:
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-8 space-y-2">
                <li>Sie Ihre ausdrückliche Einwilligung dazu erteilt haben,</li>
                <li>die Weitergabe zur Erfüllung eines Vertrags erforderlich ist,</li>
                <li>die Weitergabe zur Erfüllung einer rechtlichen Verpflichtung erforderlich ist,</li>
                <li>die Weitergabe zur Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen erforderlich ist.</li>
            </ul>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">7. Speicherdauer</h2>
            <p class="text-gray-600 mb-8">
                Wir speichern personenbezogene Daten nur so lange, wie es für die Erfüllung der jeweiligen Zwecke erforderlich ist oder soweit dies durch gesetzliche Aufbewahrungsfristen vorgeschrieben ist. Nach Wegfall des jeweiligen Zwecks bzw. nach Ablauf der gesetzlichen Aufbewahrungsfristen werden die entsprechenden Daten routinemäßig gelöscht.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">8. Ihre Rechte</h2>
            <p class="text-gray-600 mb-6">
                Sie haben gegenüber uns folgende Rechte hinsichtlich der Sie betreffenden personenbezogenen Daten:
            </p>
            <ul class="list-disc list-inside text-gray-600 mb-8 space-y-2">
                <li><strong>Recht auf Auskunft</strong> (Art. 15 DSGVO)</li>
                <li><strong>Recht auf Berichtigung</strong> (Art. 16 DSGVO)</li>
                <li><strong>Recht auf Löschung</strong> (Art. 17 DSGVO)</li>
                <li><strong>Recht auf Einschränkung der Verarbeitung</strong> (Art. 18 DSGVO)</li>
                <li><strong>Recht auf Widerspruch</strong> (Art. 21 DSGVO)</li>
                <li><strong>Recht auf Datenübertragbarkeit</strong> (Art. 20 DSGVO)</li>
                <li><strong>Recht auf Widerruf der Einwilligung</strong> (Art. 7 Abs. 3 DSGVO)</li>
                <li><strong>Recht auf Beschwerde</strong> bei einer Aufsichtsbehörde (Art. 77 DSGVO)</li>
            </ul>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">9. Datensicherheit</h2>
            <p class="text-gray-600 mb-8">
                Wir verwenden geeignete technische und organisatorische Sicherheitsmaßnahmen, um Ihre Daten gegen zufällige oder vorsätzliche Manipulationen, teilweisen oder vollständigen Verlust, Zerstörung oder gegen den unbefugten Zugriff Dritter zu schützen. Unsere Sicherheitsmaßnahmen werden entsprechend der technologischen Entwicklung fortlaufend verbessert.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">10. SSL-Verschlüsselung</h2>
            <p class="text-gray-600 mb-8">
                Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte eine SSL-Verschlüsselung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von "http://" auf "https://" wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">11. Aktualität und Änderung dieser Datenschutzerklärung</h2>
            <p class="text-gray-600 mb-8">
                Diese Datenschutzerklärung ist aktuell gültig und hat den Stand {{ date('F Y') }}. Durch die Weiterentwicklung unserer Website und Angebote darüber oder aufgrund geänderter gesetzlicher beziehungsweise behördlicher Vorgaben kann es notwendig werden, diese Datenschutzerklärung zu ändern.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">12. Matomo Analytics (Website-Analyse)</h2>
            <p class="text-gray-600 mb-4">
                Diese Website nutzt Matomo, eine datenschutzfreundliche Webanalysesoftware, um die Nutzung unserer Website zu analysieren und kontinuierlich zu verbessern.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Zweck der Datenverarbeitung</h3>
            <ul class="list-disc ml-6 text-gray-600 mb-4">
                <li>Analyse des Nutzerverhaltens zur Verbesserung der Website</li>
                <li>Erstellung anonymer Statistiken über Besucherzahlen und -verhalten</li>
                <li>Technische Optimierung der Website-Performance</li>
                <li>Bereitstellung relevanter Inhalte basierend auf Nutzungsmustern</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">Verarbeitete Daten</h3>
            <ul class="list-disc ml-6 text-gray-600 mb-4">
                <li>IP-Adresse (anonymisiert durch Kürzung der letzten beiden Oktette)</li>
                <li>Informationen über den verwendeten Browser und das Betriebssystem</li>
                <li>Besuchte Seiten, Verweildauer und Absprungrate</li>
                <li>Referrer-URL (von welcher Website Sie zu uns gekommen sind)</li>
                <li>Ungefährer Standort (nur auf Länder-/Regionsebene)</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">Datenschutzmaßnahmen</h3>
            <ul class="list-disc ml-6 text-gray-600 mb-4">
                <li><strong>IP-Anonymisierung:</strong> Ihre IP-Adresse wird vor der Speicherung anonymisiert</li>
                <li><strong>Opt-Out möglich:</strong> Sie können der Datenerfassung jederzeit widersprechen</li>
                <li><strong>Lokale Speicherung:</strong> Alle Daten werden auf Servern in Deutschland gespeichert</li>
                <li><strong>Keine Datenweitergabe:</strong> Daten werden nicht an Dritte weitergegeben</li>
                <li><strong>Do Not Track respektiert:</strong> Browser-Einstellungen werden berücksichtigt</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">Rechtsgrundlage</h3>
            <p class="text-gray-600 mb-4">
                Die Datenverarbeitung erfolgt auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO) über unser Cookie-Consent-Banner. 
                Sie können Ihre Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen.
            </p>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">Speicherdauer</h3>
            <p class="text-gray-600 mb-4">
                Die erfassten Daten werden für maximal 26 Monate gespeichert und dann automatisch gelöscht. 
                Sie können die Löschung auch jederzeit über die Cookie-Einstellungen veranlassen.
            </p>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">Widerspruch und Opt-Out</h3>
            <p class="text-gray-600 mb-6">
                Sie können der Erfassung und Verarbeitung Ihrer Daten durch Matomo jederzeit widersprechen:
            </p>
            <ul class="list-disc ml-6 text-gray-600 mb-8">
                <li>Über unsere <a href="#" onclick="klaro.show(); return false;" class="text-primary-600 hover:text-primary-700 underline">Cookie-Einstellungen</a></li>
                <li>Durch Aktivierung der "Do Not Track"-Einstellung in Ihrem Browser</li>
                <li>Durch Deaktivierung von JavaScript in Ihrem Browser</li>
            </ul>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">13. Externe Links</h2>
            <p class="text-gray-600 mb-8">
                Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich.
            </p>

            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">14. Kontakt bei Datenschutzfragen</h2>
            <p class="text-gray-600 mb-8">
                Bei Fragen zum Datenschutz kannst du dich jederzeit an uns wenden:
            </p>
            <div class="bg-primary-50 p-6 rounded-lg">
                <p class="mb-2"><strong>KI-Coding.de</strong></p>
                <p class="mb-2">c/o Christin Löhner</p>
                <p class="mb-2">Postfach 1119</p>
                <p class="mb-2">72187 Vöhringen</p>
                <p class="mb-2">Deutschland</p>
                <p class="mb-2">E-Mail: <a href="mailto:datenschutz@ki-coding.de" class="text-primary-600 hover:text-primary-700">datenschutz@ki-coding.de</a></p>
            </div>

        </div>
    </div>
</section>

<!-- Back to Top -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('home') }}" 
               class="bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                Zurück zur Startseite
            </a>
            <a href="{{ route('contact') }}" 
               class="border border-primary-600 text-primary-600 px-6 py-3 rounded-lg font-semibold hover:bg-primary-600 hover:text-white transition-colors">
                Kontakt aufnehmen
            </a>
        </div>
    </div>
</section>
@endsection