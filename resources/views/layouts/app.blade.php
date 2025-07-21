<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'KI-Coding - Kostenlose Knowledge Base für KI-Programmierung')</title>
    <meta name="description" content="@yield('description', 'Kostenlose Wissensdatenbank für KI-gestütztes Programmieren. Tutorials, Tools, Best Practices - alles gratis für die Entwickler*innen-Community.')">
    <meta name="keywords" content="@yield('keywords', 'KI-Coding, KI-gestütztes Programmieren, AI-Coding, GitHub Copilot, ChatGPT Programmierung, Claude AI, Prompt Engineering, KI-Tools für Entwickler, Machine Learning Development, Code-Generierung, AI-assisted Programming, Künstliche Intelligenz, Laravel, PHP, JavaScript, Python, Tutorials, Knowledge Base, Open Source, Community, Best Practices, Entwickler Tools, Software Engineering, Deep Learning, Neural Networks, Kostenlos, Tutorial, Programmieren lernen, AI Assistant, Coding Tools, Entwickler Community')">
    <meta name="author" content="KI-Coding">
    <meta name="generator" content="Laravel {{ app()->version() }}">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="language" content="de">
    <meta name="geo.region" content="DE">
    <meta name="geo.country" content="Germany">
    <meta name="theme-color" content="#4F46E5">
    <meta name="msapplication-TileColor" content="#4F46E5">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="referrer" content="no-referrer-when-downgrade">
    <meta name="format-detection" content="telephone=no">
    <meta name="HandheldFriendly" content="true">
    <meta name="MobileOptimized" content="width">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Structured Data -->
    @stack('schema')

    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('og_title', 'KI-Coding - Kostenlose Knowledge Base für KI-Programmierung')">
    <meta property="og:description" content="@yield('og_description', 'Kostenlose Wissensdatenbank für KI-gestütztes Programmieren. Tutorials, Tools, Best Practices - alles gratis für die Entwickler-Community.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/apb-logo-512.png'))">
    <meta property="og:image:width" content="@yield('og_image_width', '512')">
    <meta property="og:image:height" content="@yield('og_image_height', '512')">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'KI-Coding Logo')">
    <meta property="og:site_name" content="KI-Coding">
    <meta property="og:locale" content="de_DE">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="article:author" content="@yield('article_author', 'KI-Coding Team')">
    <meta property="article:publisher" content="https://www.ki-coding.de">
    @if(isset($article) && $article->published_at)
        <meta property="article:published_time" content="{{ $article->published_at->toISOString() }}">
    @endif
    @if(isset($article) && $article->updated_at)
        <meta property="article:modified_time" content="{{ $article->updated_at->toISOString() }}">
    @endif
    @if(isset($article) && $article->tags)
        @foreach($article->tags as $tag)
            <meta property="article:tag" content="{{ $tag->name }}">
        @endforeach
    @endif

    <!-- Twitter Card Tags (behalten für Sharing-Funktionalität) -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', 'KI-Coding - Kostenlose Knowledge Base für KI-Programmierung')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Kostenlose Wissensdatenbank für KI-gestütztes Programmierung. Tutorials, Tools, Best Practices - alles gratis für die Entwickler-Community.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/ki-coding-social.jpg'))">
    <meta name="twitter:image:alt" content="@yield('twitter_image_alt', 'KI-Coding Knowledge Base')">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Local Fonts (DSGVO compliant) -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    
    <!-- FontAwesome (DSGVO compliant) -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional styles -->
    @stack('styles')

    <!-- Google reCAPTCHA -->
    <!-- reCAPTCHA wird durch Klaro Cookie Consent geladen -->

    <!-- Global Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "KI-Coding",
        "description": "Kostenlose Knowledge Base für KI-gestütztes Programmieren",
        "url": "{{ config('app.url') }}",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": {
                "@@type": "EntryPoint",
                "urlTemplate": "{{ config('app.url') }}/wiki/search?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "KI-Coding",
            "url": "{{ config('app.url') }}",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('images/apb-logo-512.png') }}",
                "width": 512,
                "height": 512
            },
            "sameAs": [
                "https://github.com/christinloehner/ki-coding"
            ]
        }
    }
    </script>
    
    <!-- Additional head content -->
    @stack('head')
</head>
<body class="font-sans antialiased bg-subtle text-high-contrast">
    <!-- Skip to main content -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-primary-600 text-white px-4 py-2 rounded-md z-50">
        Skip to main content
    </a>

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-subtle-border sticky top-0 z-40" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 focus-ki">
                        <img src="{{ asset('images/apb-logo-512.png') }}" alt="KI-Coding Logo" class="h-8 w-8">
                        <span class="text-xl font-display font-bold text-high-contrast">KI-Coding</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) active @endif">
                        Home
                    </a>
                    <a href="{{ route('wiki.index') }}" class="nav-link @if(request()->routeIs('wiki.*')) active @endif">
                        Wiki
                    </a>
                    <a href="{{ route('faq') }}" class="nav-link @if(request()->routeIs('faq')) active @endif">
                        FAQ
                    </a>
                    <a href="{{ route('about') }}" class="nav-link @if(request()->routeIs('about')) active @endif">
                        Über uns
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link @if(request()->routeIs('contact')) active @endif">
                        Kontakt
                    </a>
                </nav>

                <!-- Auth Links -->
                <div class="hidden md:flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="btn-ki-outline-sm focus-ki">
                            Anmelden
                        </a>
                        <a href="{{ route('register') }}" class="btn-ki-primary-sm focus-ki">
                            Registrieren
                        </a>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-medium-contrast hover:text-high-contrast focus-ki">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Dashboard
                                </a>
                                @can('view users')
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    User Management
                                </a>
                                @endcan
                                
                                @can('edit all articles')
                                <a href="{{ route('admin.articles.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Article Management
                                </a>
                                @endcan
                                
                                @hasrole('admin')
                                <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Role Management
                                </a>
                                @endhasrole
                                <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.show.id', Auth::user()->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Mein Profil
                                </a>
                                <a href="{{ route('profile.edit-profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-edit mr-2"></i>Profil bearbeiten
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Passwort ändern
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Abmelden
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 hover:text-gray-900 focus-ki">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white border-t border-subtle-border">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link @if(request()->routeIs('home')) active @endif">
                    Home
                </a>
                <a href="{{ route('wiki.index') }}" class="mobile-nav-link @if(request()->routeIs('wiki.*')) active @endif">
                    Wiki
                </a>
                <a href="{{ route('faq') }}" class="mobile-nav-link @if(request()->routeIs('faq')) active @endif">
                    FAQ
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link @if(request()->routeIs('about')) active @endif">
                    Über uns
                </a>
                <a href="{{ route('contact') }}" class="mobile-nav-link @if(request()->routeIs('contact')) active @endif">
                    Kontakt
                </a>
                
                @guest
                    <a href="{{ route('login') }}" class="mobile-nav-link">
                        Anmelden
                    </a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">
                        Registrieren
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                        Dashboard
                    </a>
                    <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.show.id', Auth::user()->id) }}" class="mobile-nav-link">
                        <i class="fas fa-user mr-2"></i>Mein Profil
                    </a>
                    <a href="{{ route('profile.edit-profile') }}" class="mobile-nav-link">
                        <i class="fas fa-edit mr-2"></i>Profil bearbeiten
                    </a>
                    <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                        <i class="fas fa-cog mr-2"></i>Passwort ändern
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="w-full text-left text-gray-700 hover:text-gray-900">
                            Abmelden
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('images/apb-logo-512.png') }}" alt="KI-Coding Logo" class="h-8 w-8">
                        <span class="text-xl font-display font-bold">KI-Coding</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Kostenlose Wissensdatenbank für KI-gestütztes Programmieren. 
                        Tutorials, Tools, Best Practices - alles gratis für die Entwickler*innen-Community.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://github.com/christinloehner/ki-coding" class="text-gray-400 hover:text-white" aria-label="GitHub" target="_blank" rel="noopener noreferrer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="{{ route('wiki.index') }}" class="text-gray-400 hover:text-white">Wiki</a></li>
                        <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-white">FAQ</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white">Über uns</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Kontakt</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Rechtliches</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white">Datenschutz</a></li>
                        <li><a href="{{ route('imprint') }}" class="text-gray-400 hover:text-white">Impressum</a></li>
                        <li><a href="#" onclick="klaro.show(); return false;" class="text-gray-400 hover:text-white">Cookie-Einstellungen</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} KI-Coding. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </footer>

    <!-- Klaro Cookie Consent -->
    <script src="{{ asset('js/klaro-config.js') }}"></script>
    <script defer src="{{ asset('js/klaro.js') }}"></script>
    
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>