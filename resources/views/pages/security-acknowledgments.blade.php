@extends('layouts.app')

@section('title', 'Security Acknowledgments - Hall of Fame | KI-Coding')
@section('description', 'KI-Coding Security Acknowledgments: Danksagungen an Sicherheitsforscher*innen und Ethical Hacker*innen, die uns bei der Verbesserung der Plattformsicherheit geholfen haben.')
@section('keywords', 'Security Acknowledgments, Hall of Fame, Security Researchers, Ethical Hackers, Bug Bounty, Security Contributors, KI-Coding Security, Vulnerability Reporting')
@section('robots', 'index, follow')

@section('og_title', 'KI-Coding Security Acknowledgments - Hall of Fame')
@section('og_description', 'Unsere Danksagungen an alle Sicherheitsforscher*innen, die zur Verbesserung der KI-Coding Plattformsicherheit beigetragen haben.')
@section('og_type', 'website')
@section('og_image', asset('images/ki-coding-social.jpg'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-50 via-white to-blue-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 mb-6">
                Security Acknowledgments
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Wir danken allen Sicherheitsforscher*innen und Ethical Hacker*innen, 
                die zur Verbesserung der Sicherheit unserer Plattform beigetragen haben.
            </p>
        </div>
    </div>
</section>

<!-- Thank You Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-6">
                Vielen Dank!
            </h2>
            <div class="prose prose-lg max-w-none text-center">
                <p class="text-lg text-gray-600 mb-8">
                    Die Sicherheit unserer KI-Coding Plattform ist nur dank der Unterstützung 
                    der Security-Community möglich. Hier würdigen wir alle, die verantwortungsvoll 
                    Sicherheitslükcken gemeldet und uns dabei geholfen haben, unsere Plattform sicherer zu machen.
                </p>
            </div>
        </div>

        <!-- Hall of Fame Placeholder -->
        <div class="bg-gradient-to-br from-green-50 to-blue-50 p-12 rounded-xl text-center mb-16">
            <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-4">
                Hall of Fame
            </h3>
            <p class="text-lg text-gray-600 mb-8">
                Diese Sektion wird in Zukunft alle Sicherheitsforscher*innen auflisten, 
                die verantwortungsvoll Vulnerabilities in unserer Plattform gemeldet haben.
            </p>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 max-w-lg mx-auto">
                <p class="text-gray-500 italic">
                    Noch keine Einträge vorhanden.
                </p>
                <p class="text-sm text-gray-400 mt-2">
                    Sei die erste Person, die einen Sicherheitsbeitrag leistet!
                </p>
            </div>
        </div>

        <!-- Recognition Levels -->
        <div class="mb-16">
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-8 text-center">
                Anerkennungsstufen
            </h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-red-50 to-white p-8 rounded-xl shadow-sm border border-red-100 text-center">
                    <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Kritische Sicherheitslücken</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>• Prominente Erwähnung</li>
                        <li>• Detaillierte Würdigung</li>
                        <li>• Link zum Profil (optional)</li>
                        <li>• Besondere Anerkennung</li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-white p-8 rounded-xl shadow-sm border border-orange-100 text-center">
                    <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Wichtige Sicherheitslücken</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>• Namentliche Erwähnung</li>
                        <li>• Kurze Beschreibung</li>
                        <li>• Dankesnotiz</li>
                        <li>• Hall of Fame Eintrag</li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-white p-8 rounded-xl shadow-sm border border-green-100 text-center">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Allgemeine Sicherheitslücken</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>• Listeneintrag</li>
                        <li>• Name oder Alias</li>
                        <li>• Datum der Meldung</li>
                        <li>• Dankeshäufchen</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- How to Get Listed -->
        <div class="mb-16">
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-8 text-center">
                So wirst Du hier aufgeführt
            </h2>
            
            <div class="bg-gradient-to-r from-blue-50 to-green-50 p-8 rounded-xl">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Automatische Anerkennung
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                Melde eine gültige Sicherheitslücke über unsere <a href="{{ route('security-policy') }}" class="text-blue-600 hover:text-blue-800 underline">Security Policy</a>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                Warte auf unsere Bestätigung und Behebung
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                Nach der koordinierten Veröffentlichung wirst Du hier aufgeführt
                            </li>
                        </ul>
                    </div>
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <a href="{{ route('security-policy') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Security Policy lesen
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attribution Preferences -->
        <div class="mb-16">
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-8 text-center">
                Attribution-Präferenzen
            </h2>
            
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <p class="text-gray-600 mb-6">
                    Bei der Meldung einer Sicherheitslücke kannst Du angeben, wie Du in unseren 
                    Acknowledgments aufgeführt werden möchtest:
                </p>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900">Mögliche Angaben:</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Echter Name
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Pseudonym/Handle
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Firmenname
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Website/Social Media Link
                            </li>
                        </ul>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900">Datenschutz:</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Anonyme Auflistung möglich
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Keine Auflistung auf Wunsch
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin "round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Nachträgliche Änderungen möglich
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-8 rounded-xl text-center">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-4">
                Fragen zu den Acknowledgments?
            </h2>
            <p class="text-gray-600 mb-6">
                Falls Du Fragen zur Auflistung hast oder Änderungen an Deinem Eintrag wünschst, 
                kontaktiere uns gerne.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="mailto:security@ki-coding.de" 
                   class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Security Team kontaktieren
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-blue-500 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition-colors">
                    Allgemeine Anfrage
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-4">
                Security-Statistiken
            </h2>
            <p class="text-lg text-gray-600">
                Transparenz über unsere Sicherheitsbemühungen
            </p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">0</div>
                <div class="text-sm text-gray-600">Gemeldete Vulnerabilities</div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">0</div>
                <div class="text-sm text-gray-600">Behobene Sicherheitslücken</div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">N/A</div>
                <div class="text-sm text-gray-600">Durchschnittliche Behebungszeit</div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-red-600 mb-2">0</div>
                <div class="text-sm text-gray-600">Security Researcher</div>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">
                Statistiken werden regelmäßig aktualisiert. Stand: {{ date('d.m.Y') }}
            </p>
        </div>
    </div>
</section>
@endsection

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "KI-Coding Security Acknowledgments",
    "description": "Hall of Fame und Danksagungen an Sicherheitsforscher*innen, die zur KI-Coding Plattformsicherheit beigetragen haben",
    "url": "{{ route('security-acknowledgments') }}",
    "mainEntity": {
        "@@type": "Article",
        "headline": "Security Acknowledgments - Hall of Fame",
        "description": "Anerkennung für Sicherheitsforscher*innen und ihre Beiträge zur Plattformsicherheit",
        "author": {
            "@@type": "Organization",
            "name": "KI-Coding"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "KI-Coding",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('images/apb-logo-512.png') }}"
            }
        },
        "datePublished": "2025-07-23",
        "dateModified": "2025-07-23"
    },
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
                "name": "Security Acknowledgments",
                "item": "{{ route('security-acknowledgments') }}"
            }
        ]
    },
    "about": {
        "@@type": "Thing",
        "name": "Information Security",
        "description": "Anerkennung von Beiträgen zur Cybersecurity und Plattformsicherheit"
    }
}
</script>
@endpush