@extends('layouts.app')

@section('title', 'Über KI-Coding: Kostenlose Open Source Community für KI-Programmierung | Mission & Vision')
@section('description', 'Erfahre mehr über KI-Coding: Unsere Mission für kostenloses KI-Programmierung Wissen, Community-driven Open Source Approach. 100% gratis GitHub Copilot & ChatGPT Tutorials.')
@section('keywords', 'KI-Coding Über uns, Open Source KI Community, Kostenlose KI Programmierung, Community-driven Development, KI Tutorials Mission, GitHub Copilot Community, ChatGPT Programmierung Team, AI Coding Vision, Entwickler Community, Programming Education')
@section('robots', 'index, follow, max-image-preview:large')

@section('og_title', 'Über KI-Coding: Kostenlose Open Source Community für KI-Programmierung')
@section('og_description', 'Unsere Mission für kostenloses KI-Programmierung Wissen, Community-driven Open Source Approach. 100% gratis GitHub Copilot & ChatGPT Tutorials.')
@section('og_type', 'website')
@section('og_image', asset('images/ki-coding-social.jpg'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_alt', 'KI-Coding Team: Open Source Community für kostenlose KI-Programmierung')

@section('twitter_title', 'Über KI-Coding: Kostenlose KI-Programmierung Community')
@section('twitter_description', 'Mission für kostenloses KI-Wissen. Community-driven Open Source. 100% gratis GitHub Copilot & ChatGPT Tutorials.')
@section('twitter_image', asset('images/ki-coding-social.jpg'))
@section('twitter_image_alt', 'KI-Coding Open Source Community')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-50 via-white to-secondary-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 mb-6">
                Über KI-Coding
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Wir sind eine engagierte Community, die kostenloses Wissen im Bereich KI-gestütztes Programmieren bereitstellt. 
                Für Entwickler*innen von Entwickler*innen – transparent, zugänglich und immer kostenlos.
            </p>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-6">
                    Unsere Mission
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Wir glauben, dass Wissen frei zugänglich sein sollte. Unsere Mission ist es, eine umfassende, 
                    kostenlose Knowledge Base für KI-gestütztes Programmieren aufzubauen – von der Community für die Community.
                </p>
                <p class="text-lg text-gray-600 mb-8">
                    Durch transparente Tutorials, Open Source Tools und Community-driven Content schaffen wir 
                    eine Plattform, die jede*r Entwickler*in hilft, KI-Technologien zu verstehen und erfolgreich einzusetzen.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">100% Kostenlos</h3>
                            <p class="text-gray-600">Alle Inhalte sind frei zugänglich – keine versteckten Kosten oder Premium-Accounts.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-secondary-600 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Community-driven</h3>
                            <p class="text-gray-600">Inhalte werden von der Community erstellt, geprüft und kontinuierlich verbessert.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Open Source</h3>
                            <p class="text-gray-600">Vollständige Transparenz – du kannst alle Inhalte einsehen, nutzen und verbessern.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-primary-50 to-secondary-50 p-8 rounded-xl">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-display font-bold text-gray-900 mb-4">
                        Open Source Knowledge
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Wir demokratisieren das Wissen über KI-Programmierung und machen es 
                        für jeden zugänglich – unabhängig von Budget oder Hintergrund.
                    </p>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">100%</div>
                            <div class="text-sm text-gray-600">Kostenlos</div>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">∞</div>
                            <div class="text-sm text-gray-600">Community-Beiträge</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">
                Unsere Werte
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Diese Prinzipien leiten uns bei allem, was wir tun
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Open Source</h3>
                <p class="text-gray-600">
                    Transparenz und Offenheit stehen im Mittelpunkt – jeder kann beitragen und das Wissen frei nutzen.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Kostenlose Bildung</h3>
                <p class="text-gray-600">
                    Bildung sollte frei zugänglich sein – wir demokratisieren das Wissen über KI-Programmierung.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Transparenz</h3>
                <p class="text-gray-600">
                    Vollständige Offenheit über unsere Inhalte, Methoden und Ziele – keine versteckten Absichten.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Community</h3>
                <p class="text-gray-600">
                    Eine starke, unterstützende Community, die gemeinsam lernt und Wissen teilt.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Community Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">
                Unsere Community
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Entwickler aus verschiedenen Bereichen, die ihr Wissen teilen und gemeinsam die KI-Zukunft gestalten
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-primary-50 to-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Frontend-Entwickler*innen</h3>
                <p class="text-primary-600 font-medium mb-4">Community Contributors</p>
                <p class="text-gray-600 mb-4">
                    Teilen ihr Wissen über KI-Tools in der Frontend-Entwicklung und moderne UI/UX-Patterns.
                </p>
                <div class="flex space-x-4 justify-center">
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-secondary-50 to-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4l4 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Backend-Entwickler*innen</h3>
                <p class="text-secondary-600 font-medium mb-4">Community Contributors</p>
                <p class="text-gray-600 mb-4">
                    Bringen Expertise in Server-Side KI-Integration und API-Design für KI-Anwendungen ein.
                </p>
                <div class="flex space-x-4 justify-center">
                    <a href="#" class="text-gray-400 hover:text-secondary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-secondary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-primary-50 to-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">DevOps-Expert*innen</h3>
                <p class="text-primary-600 font-medium mb-4">Community Contributors</p>
                <p class="text-gray-600 mb-4">
                    Teilen Automation-Strategien und Best Practices für KI-Workflows in der Entwicklung.
                </p>
                <div class="flex space-x-4 justify-center">
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-secondary-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-6">
            Werde Teil unserer Community
        </h2>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Schließe dich hunderten von Entwickler*innen an, die bereits kostenlos von unserem 
            Open Source Wissen profitieren. Teile deine Erfahrungen und lerne von anderen.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('wiki.index') }}" 
               class="bg-white text-primary-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                Knowledge Base entdecken
            </a>
            <a href="{{ route('contact') }}" 
               class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300">
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
    "@@type": "Organization",
    "name": "KI-Coding",
    "alternateName": "KI-Coding.de",
    "description": "Kostenlose, Open Source Knowledge Base für KI-gestütztes Programmieren. Community-driven Tutorials, Tools und Best Practices.",
    "url": "{{ config('app.url') }}",
    "logo": {
        "@@type": "ImageObject",
        "url": "{{ asset('images/icon-512x512.png') }}",
        "width": 512,
        "height": 512
    },
    "foundingDate": "2024",
    "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Postfach 1119",
        "addressLocality": "Vöhringen",
        "postalCode": "72187",
        "addressCountry": "DE"
    },
    "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "+49",
        "contactType": "Customer Service",
        "email": "info@ki-coding.de",
        "availableLanguage": "German"
    },
    "sameAs": [
        "https://github.com/christinloehner/ki-coding"
    ],
    "makesOffer": [
        {
            "@@type": "EducationalOrganization",
            "name": "Kostenlose KI-Programmierung Tutorials",
            "description": "GitHub Copilot, ChatGPT, Claude AI und Prompt Engineering Tutorials",
            "provider": {
                "@@type": "Organization",
                "name": "KI-Coding"
            },
            "offers": {
                "@@type": "Offer",
                "price": "0",
                "priceCurrency": "EUR",
                "description": "100% kostenlose Bildungsinhalte"
            }
        }
    ],
    "knowsAbout": [
        "KI-Programmierung",
        "GitHub Copilot",
        "ChatGPT",
        "Claude AI",
        "Prompt Engineering",
        "Machine Learning",
        "AI-assisted Programming",
        "Open Source Development"
    ],
    "memberOf": {
        "@@type": "Organization",
        "name": "Open Source Community"
    },
    "audience": {
        "@@type": "Audience",
        "audienceType": "Entwickler, Programmierer, Software Engineers, Studierende"
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
                "name": "Über uns",
                "item": "{{ route('about') }}"
            }
        ]
    },
    "mainEntityOfPage": {
        "@@type": "WebPage",
        "@id": "{{ route('about') }}"
    }
}
</script>
@endpush