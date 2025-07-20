@extends('layouts.app')

@section('title', 'FAQ: Häufig gestellte Fragen | KI-Coding - Kostenlose KI-Programmierung lernen')
@section('description', 'Häufig gestellte Fragen zur KI-Coding Knowledge Base. Alles über Registrierung, kostenlose Nutzung, Community-Beiträge, GitHub Copilot Tutorials und mehr.')
@section('keywords', 'KI-Coding FAQ, KI Programmierung Fragen, GitHub Copilot Hilfe, ChatGPT Tutorial Fragen, Kostenlose KI Tutorials, Open Source Community, Prompt Engineering Hilfe, AI Coding Support, Entwickler Hilfe, Programming AI Questions')
@section('robots', 'index, follow, max-image-preview:large')

@section('og_title', 'FAQ: Häufig gestellte Fragen | KI-Coding')
@section('og_description', 'Häufig gestellte Fragen zur KI-Coding Knowledge Base. Alles über kostenlose Nutzung, Community-Beiträge und KI-Programmierung.')
@section('og_type', 'website')
@section('og_image', asset('images/faq-social.jpg'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_alt', 'KI-Coding FAQ - Häufig gestellte Fragen zur KI-Programmierung')

@section('twitter_title', 'KI-Coding FAQ: Häufig gestellte Fragen')
@section('twitter_description', 'Alles über kostenlose KI-Programmierung Tutorials, GitHub Copilot, ChatGPT, Community-Beiträge und mehr.')
@section('twitter_image', asset('images/faq-social.jpg'))
@section('twitter_image_alt', 'KI-Coding FAQ Knowledge Base')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-50 via-white to-secondary-50 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 mb-6">
                Häufig gestellte Fragen
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Hier findest du Antworten auf die häufigsten Fragen rund um die KI-Coding Knowledge Base. 
                Falls deine Frage nicht dabei ist, kontaktiere uns gerne!
            </p>
            <div class="text-sm text-gray-500">
                Zuletzt aktualisiert: {{ date('d.m.Y') }}
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Allgemeine Fragen -->
        <div class="mb-12">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Allgemeine Fragen
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Was ist KI-Coding.de?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            KI-Coding.de ist eine kostenlose, Open Source Knowledge Base für KI-gestütztes Programmieren. 
                            Wir sammeln Tutorials, Best Practices, Tools und Tipps rund um das Thema Künstliche Intelligenz 
                            in der Softwareentwicklung. Die Plattform ist community-driven und für alle Entwickler*innen 
                            kostenlos zugänglich.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Ist die Nutzung wirklich komplett kostenlos?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            Ja, absolut! Alle Inhalte, Tutorials und Tools sind komplett kostenlos verfügbar. 
                            Es gibt keine versteckten Kosten, keine Premium-Accounts oder kostenpflichtigen Features. 
                            Das Projekt wird aus Leidenschaft für die Community betrieben und ist Open Source.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Was bedeutet "Open Source" in diesem Kontext?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Open Source bedeutet Transparenz und Offenheit. Konkret heißt das:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Der Quellcode der Plattform ist öffentlich einsehbar</li>
                            <li>Jeder kann Verbesserungen vorschlagen und beitragen</li>
                            <li>Alle Inhalte können frei genutzt und weiterentwickelt werden</li>
                            <li>Die Community kann das Projekt mitgestalten</li>
                            <li>Keine Vendor-Lock-ins oder proprietäre Abhängigkeiten</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Für wen ist diese Knowledge Base gedacht?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Unsere Knowledge Base richtet sich an alle, die sich für KI-gestütztes Programmieren interessieren:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Entwickler*innen aller Erfahrungsstufen (Anfänger bis Experten)</li>
                            <li>Studierende der Informatik oder verwandter Fächer</li>
                            <li>Projektmanager und Tech-Leads, die KI-Tools verstehen möchten</li>
                            <li>Unternehmen, die KI in ihre Entwicklungsprozesse integrieren wollen</li>
                            <li>Alle, die neugierig auf die Zukunft der Softwareentwicklung sind</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account & Registrierung -->
        <div class="mb-12">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Account & Registrierung
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 5 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Brauche ich einen Account, um die Knowledge Base zu nutzen?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            Nein! Du kannst alle Artikel, Tutorials und Ressourcen ohne Registrierung lesen und nutzen. 
                            Ein Account ist nur erforderlich, wenn du selbst Inhalte beitragen, kommentieren oder 
                            personalisierte Features wie Bookmarks nutzen möchtest.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Welche Daten werden bei der Registrierung benötigt?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Wir halten die Registrierung so einfach wie möglich. Benötigt werden nur:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>E-Mail-Adresse (für Benachrichtigungen und Passwort-Reset)</li>
                            <li>Username (öffentlich sichtbar)</li>
                            <li>Name (optional, für bessere Kommunikation)</li>
                            <li>Passwort (sicher gehashed gespeichert)</li>
                        </ul>
                        <p class="text-gray-600 mt-3">
                            Alle weiteren Profilinformationen sind optional und können später ergänzt werden.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 7 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Wie kann ich mein Passwort zurücksetzen?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            Auf der Login-Seite findest du einen "Passwort vergessen?" Link. Klicke darauf, gib deine 
                            E-Mail-Adresse ein, und du erhältst eine E-Mail mit einem sicheren Link zum Zurücksetzen 
                            deines Passworts. Der Link ist 60 Minuten gültig.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inhalte & Beiträge -->
        <div class="mb-12">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Inhalte & Beiträge
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 8 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Wie kann ich selbst Artikel schreiben?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Nach der Registrierung kannst du sofort Artikel schreiben:
                        </p>
                        <ol class="text-gray-600 space-y-1 list-decimal list-inside">
                            <li>Registriere dich und logge dich ein</li>
                            <li>Gehe zum Wiki-Bereich</li>
                            <li>Klicke auf "Artikel schreiben"</li>
                            <li>Wähle eine passende Kategorie</li>
                            <li>Schreibe deinen Artikel in Markdown</li>
                            <li>Füge Tags hinzu für bessere Findbarkeit</li>
                            <li>Speichere als Entwurf oder veröffentliche direkt</li>
                        </ol>
                        <p class="text-gray-600 mt-3">
                            Wir haben einen integrierten Markdown-Editor mit Live-Preview für einfaches Schreiben.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 9 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Welche Themen sind erwünscht?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Wir freuen uns über alle Inhalte rund um KI-gestütztes Programmieren:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Tutorials zu KI-Tools (GitHub Copilot, ChatGPT, Claude, etc.)</li>
                            <li>Best Practices für Prompt Engineering</li>
                            <li>Code-Reviews mit KI-Assistenten</li>
                            <li>Automatisierung von Entwicklungsprozessen</li>
                            <li>Tool-Vergleiche und Empfehlungen</li>
                            <li>Erfahrungsberichte aus der Praxis</li>
                            <li>Zukunftsausblicke und Trends</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ Item 10 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Wird mein Artikel vor der Veröffentlichung geprüft?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            Neue Nutzer müssen ihre ersten Artikel zur Moderation einreichen. Nach einigen 
                            qualitativ hochwertigen Beiträgen erhältst du "Author"-Status und kannst direkt 
                            veröffentlichen. Dies stellt die Qualität der Knowledge Base sicher und hilft 
                            neuen Autor*innen beim Einstieg.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 11 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Kann ich Artikel von anderen bearbeiten?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            Ja! Das ist einer der Vorteile einer wiki-artigen Plattform. Jeder registrierte Nutzer 
                            kann Verbesserungsvorschläge machen oder Fehler korrigieren. Größere Änderungen werden 
                            dem ursprünglichen Autor zur Bestätigung vorgelegt. Alle Änderungen sind nachverfolgbar 
                            und können rückgängig gemacht werden.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technische Fragen -->
        <div class="mb-12">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Technische Fragen
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 12 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Welche Technologien werden verwendet?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Die Plattform ist mit modernen Web-Technologien gebaut:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Backend: Laravel 10 (PHP)</li>
                            <li>Frontend: Tailwind CSS + Alpine.js</li>
                            <li>Datenbank: MySQL</li>
                            <li>Suche: Meilisearch</li>
                            <li>Markdown-Processing: Custom Parser</li>
                            <li>Deployment: Docker + CI/CD</li>
                        </ul>
                        <p class="text-gray-600 mt-3">
                            Alles Open Source und auf GitHub verfügbar!
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 13 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Ist die Seite mobilfreundlich?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            Ja, definitiv! Die gesamte Plattform ist responsive designed und funktioniert optimal 
                            auf Smartphones, Tablets und Desktop-Computern. Alle Features sind auf allen Geräten 
                            verfügbar und die Bedienung ist touch-optimiert.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 14 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Wie funktioniert die Suche?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Wir verwenden eine erweiterte Volltext-Suche mit mehreren Features:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Volltextsuche in Artikeln, Titeln und Beschreibungen</li>
                            <li>Kategorien- und Tag-basierte Filterung</li>
                            <li>Auto-Suggest während der Eingabe</li>
                            <li>Sortierung nach Relevanz, Datum oder Popularität</li>
                            <li>Erweiterte Suche mit mehreren Kriterien</li>
                        </ul>
                        <p class="text-gray-600 mt-3">
                            Die Suche ist sehr schnell und findet auch Teilwörter und ähnliche Begriffe.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datenschutz & Sicherheit -->
        <div class="mb-12">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Datenschutz & Sicherheit
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 15 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Wie werden meine Daten geschützt?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Datenschutz ist für uns oberste Priorität:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>SSL-Verschlüsselung für alle Verbindungen</li>
                            <li>Passwörter werden gehashed und gesalzen gespeichert</li>
                            <li>Minimale Datensammlung (nur was nötig ist)</li>
                            <li>DSGVO-konforme Datenverarbeitung</li>
                            <li>Keine Weitergabe an Dritte</li>
                            <li>Server in Deutschland gehostet</li>
                        </ul>
                        <p class="text-gray-600 mt-3">
                            Du kannst deine Daten jederzeit einsehen, ändern oder löschen lassen.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 16 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Werden Cookies verwendet?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Ja, aber verantwortungsvoll und transparent:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Notwendige Cookies für Login und Sicherheit</li>
                            <li>Optionale Cookies nur mit deiner Einwilligung</li>
                            <li>Cookie-Banner mit granularer Kontrolle</li>
                            <li>Jederzeit änderbare Einstellungen</li>
                            <li>Keine Tracking-Cookies ohne Zustimmung</li>
                        </ul>
                        <p class="text-gray-600 mt-3">
                            Du kannst deine Cookie-Einstellungen jederzeit über den Footer verwalten.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 17 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Was passiert mit meinen Inhalten, wenn ich meinen Account lösche?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">
                            Deine persönlichen Daten werden gelöscht, aber deine veröffentlichten Artikel bleiben 
                            für die Community verfügbar (anonymisiert). Du kannst auch wählen, all deine Inhalte 
                            mit zu löschen. Diese Entscheidung ist während des Löschvorgangs wählbar und kann 
                            nicht rückgängig gemacht werden.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Community & Support -->
        <div class="mb-12">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Community & Support
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 18 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Wie kann ich bei Problemen Hilfe bekommen?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Es gibt mehrere Wege, Hilfe zu bekommen:
                        </p>
                        <ol class="text-gray-600 space-y-1 list-decimal list-inside">
                            <li>Diese FAQ durchsuchen</li>
                            <li>Kontaktformular auf der Website nutzen</li>
                            <li>E-Mail an support@ki-coding.de</li>
                            <li>Community-Forum (für allgemeine Fragen)</li>
                            <li>GitHub Issues (für technische Probleme)</li>
                        </ol>
                        <p class="text-gray-600 mt-3">
                            Wir antworten normalerweise innerhalb von 24 Stunden.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 19 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Gibt es Community-Richtlinien?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Ja, wir haben einfache aber wichtige Richtlinien:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Respektvoller Umgang miteinander</li>
                            <li>Konstruktive und hilfreiche Beiträge</li>
                            <li>Keine Spam, Werbung oder Off-Topic Inhalte</li>
                            <li>Quellenangaben bei verwendeten Materialien</li>
                            <li>Kein Plagiat oder Urheberrechtsverletzungen</li>
                            <li>Qualität vor Quantität</li>
                        </ul>
                        <p class="text-gray-600 mt-3">
                            Verstöße werden moderiert, bei schweren Fällen kann der Account gesperrt werden.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 20 -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Wie kann ich zur Weiterentwicklung der Plattform beitragen?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600 mb-3">
                            Es gibt viele Möglichkeiten zu helfen:
                        </p>
                        <ul class="text-gray-600 space-y-1 list-disc list-inside">
                            <li>Qualitativ hochwertige Artikel schreiben</li>
                            <li>Bestehende Artikel verbessern und korrigieren</li>
                            <li>Feedback und Verbesserungsvorschläge geben</li>
                            <li>Bugs melden (GitHub Issues)</li>
                            <li>Code beitragen (Pull Requests)</li>
                            <li>Die Plattform weiterempfehlen</li>
                            <li>Übersetungsarbeit (zukünftig geplant)</li>
                        </ul>
                        <p class="text-gray-600 mt-3">
                            Jeder Beitrag ist wertvoll und hilft der gesamten Community!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontakt CTA -->
        <div class="bg-primary-50 rounded-xl p-8 text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                Deine Frage war nicht dabei?
            </h3>
            <p class="text-gray-600 mb-6">
                Kein Problem! Wir helfen gerne weiter und ergänzen häufig gestellte Fragen in diese FAQ.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" 
                   class="bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                    Kontakt aufnehmen
                </a>
                <a href="{{ route('wiki.index') }}" 
                   class="border border-primary-600 text-primary-600 px-6 py-3 rounded-lg font-semibold hover:bg-primary-600 hover:text-white transition-colors">
                    Zur Knowledge Base
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('.faq-icon');
    
    if (content.classList.contains('hidden')) {
        // Open
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
        
        // Smooth slide down effect
        content.style.maxHeight = '0px';
        content.style.overflow = 'hidden';
        content.style.transition = 'max-height 0.3s ease-out';
        
        // Trigger reflow
        content.offsetHeight;
        
        content.style.maxHeight = content.scrollHeight + 'px';
        
        // Clean up after animation
        setTimeout(() => {
            content.style.maxHeight = 'none';
            content.style.overflow = 'visible';
        }, 300);
    } else {
        // Close
        content.style.maxHeight = content.scrollHeight + 'px';
        content.style.overflow = 'hidden';
        content.style.transition = 'max-height 0.3s ease-out';
        
        // Trigger reflow
        content.offsetHeight;
        
        content.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
        
        setTimeout(() => {
            content.classList.add('hidden');
            content.style.maxHeight = 'none';
            content.style.overflow = 'visible';
        }, 300);
    }
}

// Close all FAQs when pressing Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.faq-content:not(.hidden)').forEach(content => {
            const button = content.previousElementSibling;
            toggleFaq(button);
        });
    }
});
</script>
@endpush

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "name": "KI-Coding FAQ",
    "description": "Häufig gestellte Fragen zur KI-Coding Knowledge Base",
    "url": "{{ route('faq') }}",
    "mainEntity": [
        {
            "@@type": "Question",
            "name": "Was ist KI-Coding.de?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "KI-Coding.de ist eine kostenlose, Open Source Knowledge Base für KI-gestütztes Programmieren. Wir sammeln Tutorials, Best Practices, Tools und Tipps rund um das Thema Künstliche Intelligenz in der Softwareentwicklung. Die Plattform ist community-driven und für alle Entwickler*innen kostenlos zugänglich."
            }
        },
        {
            "@@type": "Question",
            "name": "Ist die Nutzung wirklich komplett kostenlos?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Ja, absolut! Alle Inhalte, Tutorials und Tools sind komplett kostenlos verfügbar. Es gibt keine versteckten Kosten, keine Premium-Accounts oder kostenpflichtigen Features. Das Projekt wird aus Leidenschaft für die Community betrieben und ist Open Source."
            }
        },
        {
            "@@type": "Question",
            "name": "Was bedeutet 'Open Source' in diesem Kontext?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Open Source bedeutet Transparenz und Offenheit. Der Quellcode der Plattform ist öffentlich einsehbar, jeder kann Verbesserungen vorschlagen und beitragen, alle Inhalte können frei genutzt und weiterentwickelt werden, die Community kann das Projekt mitgestalten und es gibt keine Vendor-Lock-ins oder proprietäre Abhängigkeiten."
            }
        },
        {
            "@@type": "Question",
            "name": "Brauche ich einen Account, um die Knowledge Base zu nutzen?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Nein! Du kannst alle Artikel, Tutorials und Ressourcen ohne Registrierung lesen und nutzen. Ein Account ist nur erforderlich, wenn du selbst Inhalte beitragen, kommentieren oder personalisierte Features wie Bookmarks nutzen möchtest."
            }
        },
        {
            "@@type": "Question",
            "name": "Wie kann ich selbst Artikel schreiben?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Nach der Registrierung kannst du sofort Artikel schreiben: Registriere dich und logge dich ein, gehe zum Wiki-Bereich, klicke auf 'Artikel schreiben', wähle eine passende Kategorie, schreibe deinen Artikel in Markdown, füge Tags hinzu für bessere Findbarkeit und speichere als Entwurf oder veröffentliche direkt. Wir haben einen integrierten Markdown-Editor mit Live-Preview für einfaches Schreiben."
            }
        },
        {
            "@@type": "Question",
            "name": "Wie werden meine Daten geschützt?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Datenschutz ist für uns oberste Priorität: SSL-Verschlüsselung für alle Verbindungen, Passwörter werden gehashed und gesalzen gespeichert, minimale Datensammlung (nur was nötig ist), DSGVO-konforme Datenverarbeitung, keine Weitergabe an Dritte und Server in Deutschland gehostet. Du kannst deine Daten jederzeit einsehen, ändern oder löschen lassen."
            }
        }
    ],
    "breadcrumb": {
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ route('home') }}"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "FAQ",
                "item": "{{ route('faq') }}"
            }
        ]
    },
    "publisher": {
        "@@type": "Organization",
        "name": "KI-Coding",
        "url": "{{ config('app.url') }}"
    },
    "inLanguage": "de-DE"
}
</script>
@endpush