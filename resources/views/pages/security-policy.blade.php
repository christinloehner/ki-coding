@extends('layouts.app')

@section('title', 'Security Policy - Responsible Disclosure | KI-Coding')
@section('description', 'KI-Coding Security Policy: Responsible Disclosure Guidelines, Vulnerability Reporting Process, Security Best Practices. Hilf uns, die Plattform sicher zu halten.')
@section('keywords', 'Security Policy, Responsible Disclosure, Vulnerability Reporting, Security Guidelines, KI-Coding Security, Bug Bounty, Ethical Hacking, Information Security, Cyber Security')
@section('robots', 'index, follow')

@section('og_title', 'KI-Coding Security Policy - Responsible Disclosure Guidelines')
@section('og_description', 'Unsere Security Policy für verantwortungsvolle Offenlegung von Sicherheitslücken. Erfahre, wie Du Vulnerabilities sicher melden kannst.')
@section('og_type', 'website')
@section('og_image', asset('images/ki-coding-social.jpg'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-red-50 via-white to-orange-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-red-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 mb-6">
                Security Policy
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Wir nehmen die Sicherheit unserer Plattform sehr ernst. Diese Richtlinie beschreibt, 
                wie Du verantwortungsvoll Sicherheitslücken melden kannst.
            </p>
        </div>
    </div>
</section>

<!-- Reporting Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-6">
                Sicherheitslücken melden
            </h2>
            <div class="prose prose-lg max-w-none">
                <p class="text-lg text-gray-600 mb-8">
                    Wenn Du eine Sicherheitslücke in unserer KI-Coding Plattform entdeckst, 
                    bitten wir Dich, diese verantwortungsvoll zu melden. Wir schätzen die Arbeit 
                    von Sicherheitsforschern und der Community, die uns dabei helfen, unsere Plattform sicherer zu machen.
                </p>
            </div>
        </div>

        <!-- Contact Methods -->
        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <div class="bg-gradient-to-br from-red-50 to-white p-8 rounded-xl shadow-sm border border-red-100">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">E-Mail Kontakt</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Für kritische Sicherheitslücken kontaktiere uns direkt per E-Mail:
                </p>
                <div class="bg-white p-4 rounded-lg border border-red-200">
                    <code class="text-red-600 font-mono">security@ki-coding.de</code>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    Bitte verwende eine verschlüsselte Verbindung und gebe sensible Details nur bei Bedarf preis.
                </p>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-white p-8 rounded-xl shadow-sm border border-orange-100">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Kontaktformular</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Für weniger kritische Sicherheitsfragen kannst Du auch unser Kontaktformular verwenden:
                </p>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    Kontakt aufnehmen
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Guidelines -->
        <div class="mb-16">
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-8">
                Responsible Disclosure Guidelines
            </h2>
            
            <div class="bg-gradient-to-r from-green-50 to-blue-50 p-8 rounded-xl mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    Erwünschtes Verhalten
                </h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Melde Sicherheitslücken vertraulich über die oben genannten Kanäle
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Gebe uns ausreichend Zeit zur Behebung (mindestens 90 Tage)
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Stelle detaillierte Informationen zur Reproduktion bereit
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Vermeide den Zugriff auf fremde Daten oder die Störung des Services
                    </li>
                </ul>
            </div>

            <div class="bg-gradient-to-r from-red-50 to-orange-50 p-8 rounded-xl">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    Unerwünschtes Verhalten
                </h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Öffentliche Disclosure vor koordinierter Veröffentlichung
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Zugriff auf Nutzerdaten oder Manipulation von Systemen
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Social Engineering gegen unsere Mitarbeiter*innen oder Nutzer*innen
                    </li>
                    <li class="flex items-start">
                        <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        DDoS-Attacken oder andere störende Aktivitäten
                    </li>
                </ul>
            </div>
        </div>

        <!-- Scope -->
        <div class="mb-16">
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-8">
                Scope und Prioritäten
            </h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-red-200">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.348 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Kritisch</h3>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li>• Remote Code Execution</li>
                        <li>• SQL Injection</li>
                        <li>• Authentication Bypass</li>
                        <li>• Privilege Escalation</li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-orange-200">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Hoch</h3>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li>• Cross-Site Scripting (XSS)</li>
                        <li>• CSRF Vulnerabilities</li>
                        <li>• Information Disclosure</li>
                        <li>• File Upload Issues</li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-yellow-200">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Medium</h3>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li>• Brute Force Protection</li>
                        <li>• Rate Limiting Issues</li>
                        <li>• Security Headers</li>
                        <li>• Input Validation</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Response Process -->
        <div class="mb-16">
            <h2 class="text-3xl font-display font-bold text-gray-900 mb-8">
                Unser Antwortprozess
            </h2>
            
            <div class="space-y-6">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                        <span class="text-white font-semibold text-sm">1</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Bestätigung (24-48 Stunden)</h3>
                        <p class="text-gray-600">Wir bestätigen den Erhalt Deiner Meldung und bewerten die Kritikalität.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                        <span class="text-white font-semibold text-sm">2</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Erste Analyse (1-7 Tage)</h3>
                        <p class="text-gray-600">Unser Team reproduziert und bewertet die Sicherheitslücke.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                        <span class="text-white font-semibold text-sm">3</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Behebung (7-90 Tage)</h3>
                        <p class="text-gray-600">Entwicklung und Deployment der Sicherheitsbehebung, abhängig von der Komplexität.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                        <span class="text-white font-semibold text-sm">4</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Koordinierte Veröffentlichung</h3>
                        <p class="text-gray-600">Nach der Behebung koordinieren wir gemeinsam die öffentliche Bekanntgabe.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legal Notice -->
        <div class="bg-gray-50 p-8 rounded-xl">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-4">
                Rechtliche Hinweise
            </h2>
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-600 mb-4">
                    Wir werden keine rechtlichen Schritte gegen Sicherheitsforscher*innen einleiten, 
                    die sich an diese Richtlinien halten und in gutem Glauben handeln.
                </p>
                <p class="text-gray-600">
                    Diese Policy gilt nur für unsere eigenen Systeme und Services unter ki-coding.de. 
                    Für Drittanbieter-Services oder -Integrationen wende Dich bitte direkt an die entsprechenden Anbieter.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-red-600 to-orange-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-6">
            Hilf uns, sicher zu bleiben
        </h2>
        <p class="text-xl text-red-100 mb-8 max-w-2xl mx-auto">
            Deine Unterstützung hilft uns dabei, eine sichere Plattform für alle Nutzer*innen zu gewährleisten. 
            Vielen Dank für Deinen Beitrag zur Sicherheit unserer Community.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="mailto:security@ki-coding.de" 
               class="bg-white text-red-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                Sicherheitslücke melden
            </a>
            <a href="{{ route('contact') }}" 
               class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-red-600 transition-all duration-300">
                Allgemeine Anfrage
            </a>
        </div>
    </div>
</section>
@endsection

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "KI-Coding Security Policy",
    "description": "Responsible Disclosure Guidelines und Security Policy für die KI-Coding Plattform",
    "url": "{{ route('security-policy') }}",
    "mainEntity": {
        "@@type": "Article",
        "headline": "Security Policy - Responsible Disclosure",
        "description": "Richtlinien für die verantwortungsvolle Meldung von Sicherheitslücken",
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
                "name": "Security Policy",
                "item": "{{ route('security-policy') }}"
            }
        ]
    }
}
</script>
@endpush