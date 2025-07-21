@extends('layouts.app')

@section('title', 'KI-Coding: Kostenlose KI-Programmierung Knowledge Base | Tutorials & Tools 2025')
@section('description', 'Lerne KI-gestütztes Programmieren kostenlos! Umfassende Tutorials zu GitHub Copilot, ChatGPT, Claude AI & mehr. Open Source Community. 100% gratis für alle Entwickler.')
@section('keywords', 'KI Programmierung lernen, GitHub Copilot Tutorial, ChatGPT Programmierung, Claude AI Coding, Prompt Engineering lernen, KI Tools Entwickler, AI Assistant Programmierung, Kostenlose KI Tutorials, Machine Learning Coding, AI-gestützte Entwicklung, Open Source KI Tools, Programmieren mit KI, AI Coding Guide, Entwickler KI Training')
@section('robots', 'index, follow, max-image-preview:large')

@section('og_title', 'KI-Coding: Kostenlose KI-Programmierung lernen | Tutorials & Tools 2025')
@section('og_description', 'Lerne KI-gestütztes Programmieren kostenlos! Umfassende Tutorials zu GitHub Copilot, ChatGPT, Claude AI & mehr. 100% gratis für alle Entwickler.')
@section('og_type', 'website')
@section('og_image', asset('images/ki-coding-social.jpg'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_alt', 'KI-Coding - Kostenlose Knowledge Base für KI-Programmierung mit Tutorials und Tools')

@section('twitter_title', 'KI-Coding: Kostenlose KI-Programmierung lernen')
@section('twitter_description', 'Lerne KI-gestütztes Programmieren kostenlos! GitHub Copilot, ChatGPT, Claude AI Tutorials. 100% gratis Open Source Community.')
@section('twitter_image', asset('images/ki-coding-social.jpg'))
@section('twitter_image_alt', 'KI-Coding Knowledge Base für KI-Programmierung')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-ki-hero overflow-hidden">
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <span class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-bold animate-ki-fade-in shadow-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    100% Kostenlos & Community-driven
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-high-contrast mb-6 animate-ki-fade-in">
                KI-Coding 
                <span class="bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                    Knowledge Base
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-medium-contrast mb-8 max-w-3xl mx-auto leading-relaxed animate-ki-slide-up">
                Deine umfassende, kostenlose Wissensdatenbank für KI-gestütztes Programmieren. 
                Tutorials, Tools, Best Practices und Community-Wissen – alles für Entwickler*innen.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-ki-slide-up">
                <a href="{{ route('wiki.index') }}" class="btn-ki-primary focus-ki">
                    Knowledge Base entdecken
                </a>
                <a href="{{ route('contact') }}" class="btn-ki-outline focus-ki">
                    Beitrag vorschlagen
                </a>
            </div>
            
            <!-- Open Source Badges -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center mt-8 animate-ki-slide-up">
                <span class="inline-flex items-center px-3 py-1 bg-accent-100 text-accent-800 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Open Source
                </span>
                <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Community-driven
                </span>
                <span class="inline-flex items-center px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Immer aktuell
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">
                Warum unsere Knowledge Base?
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Kostenlos. Open Source. Community-driven. Alles was du brauchst für KI-gestütztes Programmieren.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-ki-modern bg-gradient-ki-soft-green animate-ki-scale-in">
                <div class="w-14 h-14 bg-gradient-to-r from-green-600 to-green-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-high-contrast mb-4">100% Kostenlos</h3>
                <p class="text-medium-contrast">
                    Alle Inhalte, Tutorials und Tools sind komplett kostenlos verfügbar. 
                    Keine versteckten Kosten, keine Premium-Accounts. Alles für die Community.
                </p>
            </div>

            <div class="card-ki-modern bg-gradient-ki-soft-blue animate-ki-scale-in" style="animation-delay: 0.2s;">
                <div class="w-14 h-14 bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-high-contrast mb-4">Open Source</h3>
                <p class="text-medium-contrast">
                    Transparenz und Offenheit stehen im Mittelpunkt. Jeder kann beitragen, 
                    verbessern und das Wissen frei nutzen und weiterentwickeln.
                </p>
            </div>

            <div class="card-ki-modern bg-gradient-ki-soft-orange animate-ki-scale-in" style="animation-delay: 0.4s;">
                <div class="w-14 h-14 bg-gradient-to-r from-accent-600 to-accent-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-high-contrast mb-4">Community-driven</h3>
                <p class="text-medium-contrast">
                    Lerne mit und von anderen Entwickler*innen. Teile deine Erfahrungen, 
                    diskutiere Lösungen und profitiere vom kollektiven Wissen der Community.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Knowledge Base Preview -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-primary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">
                Entdecke die Knowledge Base
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Drei umfassende Themenbereiche mit allem, was du für KI-gestütztes Programmieren brauchst.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">KI-Tools für Entwickler*innen</h3>
                <p class="text-gray-600 text-center mb-6">
                    Umfassende Guides zu GitHub Copilot, VS Code Extensions, KI-Assistenten und mehr.
                </p>
                <div class="text-center">
                    <span class="text-2xl font-bold text-green-600">Kostenlos</span>
                    <span class="text-gray-500 ml-2">für alle</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Prompt Engineering</h3>
                <p class="text-gray-600 text-center mb-6">
                    Lerne effektive Kommunikation mit KI-Modellen. Templates, Patterns und Best Practices.
                </p>
                <div class="text-center">
                    <span class="text-2xl font-bold text-green-600">Kostenlos</span>
                    <span class="text-gray-500 ml-2">für alle</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Automatisierung & Workflows</h3>
                <p class="text-gray-600 text-center mb-6">
                    Entwickle intelligente Workflows. CI/CD-Pipelines, automatische Code-Reviews und mehr.
                </p>
                <div class="text-center">
                    <span class="text-2xl font-bold text-green-600">Kostenlos</span>
                    <span class="text-gray-500 ml-2">für alle</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('wiki.index') }}" class="btn-ki-primary focus-ki">
                Knowledge Base entdecken
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-ki-primary relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-primary-600/20 to-secondary-600/20"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-8">
            <svg class="w-16 h-16 text-black mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </div>
        <h2 class="text-3xl md:text-4xl font-display font-bold text-black mb-6">
            Bereit für die Zukunft des Programmierens?
        </h2>
        <p class="text-xl text-black/90 mb-8 max-w-2xl mx-auto">
            Starte noch heute deine KI-Coding Journey. Entdecke kostenlose Ressourcen, 
            teile dein Wissen mit der Community und profitiere von Open Source.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('wiki.index') }}" 
               class="bg-white text-primary-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-neutral-50 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus-ki">
                Knowledge Base entdecken
            </a>
            <a href="{{ route('contact') }}" 
               class="border-2 border-white text-black px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300 focus-ki">
                Beitrag vorschlagen
            </a>
        </div>
    </div>
</section>
@endsection

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": ["WebPage", "CollectionPage"],
    "name": "KI-Coding: Kostenlose Knowledge Base für KI-Programmierung",
    "description": "Lerne KI-gestütztes Programmieren kostenlos! Umfassende Tutorials zu GitHub Copilot, ChatGPT, Claude AI & mehr. Open Source Community.",
    "url": "{{ route('home') }}",
    "mainEntity": {
        "@@type": "ItemList",
        "name": "KI-Programmierung Tutorials",
        "description": "Kostenlose Tutorials und Tools für KI-gestütztes Programmieren",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "KI-Tools für Entwickler*innen",
                "description": "GitHub Copilot, VS Code Extensions, KI-Assistenten",
                "url": "{{ route('wiki.index') }}#ki-tools"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "Prompt Engineering",
                "description": "Effektive Kommunikation mit KI-Modellen, Templates, Patterns",
                "url": "{{ route('wiki.index') }}#prompt-engineering"
            },
            {
                "@@type": "ListItem",
                "position": 3,
                "name": "Automatisierung & Workflows",
                "description": "Intelligente Workflows, CI/CD-Pipelines, Code-Reviews",
                "url": "{{ route('wiki.index') }}#automation"
            }
        ]
    },
    "breadcrumb": {
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ route('home') }}"
            }
        ]
    },
    "publisher": {
        "@@type": "Organization",
        "name": "KI-Coding",
        "url": "{{ config('app.url') }}",
        "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('images/icon-512x512.png') }}"
        }
    }
}
</script>
@endpush

@push('head')
<style>
.bg-grid-pattern {
    background-image: 
        linear-gradient(to right, rgba(99, 102, 241, 0.1) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(99, 102, 241, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}
</style>
@endpush