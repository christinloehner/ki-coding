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
    <meta property="og:description" content="@yield('og_description', 'Kostenlose Wissensdatenbank für KI-gestütztes Programmieren. Tutorials, Tools, Best Practices - alles gratis für die Entwickler*innen-Community.')">
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
    <meta name="twitter:description" content="@yield('twitter_description', 'Kostenlose Wissensdatenbank für KI-gestütztes Programmierung. Tutorials, Tools, Best Practices - alles gratis für die Entwickler*innen-Community.')">
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

    <!-- MODERNER HEADER - Glassmorphism & Kompakt -->
    <header class="glass-card-header sticky top-0 z-40 border-none shadow-glass-lg" x-data="{ mobileMenuOpen: false }">
        <div class="modern-container">
            <div class="flex justify-between items-center h-14">
                <!-- MODERNES LOGO -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 focus-accessible px-2 py-1 rounded-xl" aria-label="KI-Coding Startseite">
                        <img src="{{ asset('images/apb-logo-512.png') }}" alt="KI-Coding - Kostenlose KI-Programmierung Tutorials" class="h-10 w-10">
                        <span class="text-xl font-display font-bold bg-gradient-logo bg-clip-text text-transparent">KI-Coding</span>
                    </a>
                </div>

                <!-- MODERNE DESKTOP NAVIGATION - Kompakter -->
                <nav class="hidden md:flex space-x-1" role="navigation" aria-label="Hauptnavigation">
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

                <!-- MODERNE AUTH LINKS -->
                <div class="hidden md:flex items-center space-x-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn-ki-outline-sm">
                            Anmelden
                        </a>
                        <a href="{{ route('register') }}" class="btn-ki-primary-sm">
                            Registrieren
                        </a>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-medium-contrast hover:text-high-contrast focus-ki" aria-label="Benutzermenü öffnen" aria-expanded="false" x-bind:aria-expanded="open" aria-haspopup="true">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-3 w-56 modern-card-compact py-2 z-50 animate-modern-scale" role="menu" aria-labelledby="user-menu-button">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-50 hover:text-primary-700 rounded-lg mx-2 transition-colors" role="menuitem">
                                    <i class="fas fa-tachometer-alt w-4 mr-3" aria-hidden="true"></i>Dashboard
                                </a>
                                @can('view users')
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-secondary-50 hover:text-secondary-700 rounded-lg mx-2 transition-colors" role="menuitem">
                                    <i class="fas fa-users w-4 mr-3" aria-hidden="true"></i>User Management
                                </a>
                                @endcan
                                
                                @can('edit all articles')
                                <a href="{{ route('admin.articles.index') }}" class="block px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-accent-50 hover:text-accent-700 rounded-lg mx-2 transition-colors" role="menuitem">
                                    <i class="fas fa-newspaper w-4 mr-3" aria-hidden="true"></i>Article Management
                                </a>
                                @endcan
                                
                                @hasrole('admin')
                                <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-warning-50 hover:text-warning-700 rounded-lg mx-2 transition-colors" role="menuitem">
                                    <i class="fas fa-shield-alt w-4 mr-3" aria-hidden="true"></i>Role Management
                                </a>
                                @endhasrole
                                
                                <div class="border-t border-neutral-100 my-2 mx-2"></div>
                                
                                <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.show.id', Auth::user()->id) }}" class="block px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 rounded-lg mx-2 transition-colors" role="menuitem">
                                    <i class="fas fa-user w-4 mr-3" aria-hidden="true"></i>Mein Profil
                                </a>
                                <a href="{{ route('profile.edit-profile') }}" class="block px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 rounded-lg mx-2 transition-colors">
                                    <i class="fas fa-edit w-4 mr-3"></i>Profil bearbeiten
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 rounded-lg mx-2 transition-colors">
                                    <i class="fas fa-cog w-4 mr-3"></i>Passwort ändern
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block mx-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-error-600 hover:bg-error-50 rounded-lg transition-colors">
                                        <i class="fas fa-sign-out-alt w-4 mr-3"></i>Abmelden
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- MODERNER MOBILE MENU BUTTON -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 rounded-xl focus-accessible transition-all" aria-label="Mobilmenü" aria-expanded="false" x-bind:aria-expanded="mobileMenuOpen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="sr-only" x-text="mobileMenuOpen ? 'Menü schließen' : 'Menü öffnen'"></span>
                </button>
            </div>
        </div>

        <!-- MODERNE MOBILE NAVIGATION -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden glass-card mt-2 mx-4 rounded-2xl animate-modern-scale" role="navigation" aria-label="Mobile Navigation">
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link @if(request()->routeIs('home')) active @endif" aria-current="@if(request()->routeIs('home')) page @endif">
                    <i class="fas fa-home w-4 mr-3" aria-hidden="true"></i>Home
                </a>
                <a href="{{ route('wiki.index') }}" class="mobile-nav-link @if(request()->routeIs('wiki.*')) active @endif" aria-current="@if(request()->routeIs('wiki.*')) page @endif">
                    <i class="fas fa-book w-4 mr-3" aria-hidden="true"></i>Wiki
                </a>
                <a href="{{ route('faq') }}" class="mobile-nav-link @if(request()->routeIs('faq')) active @endif" aria-current="@if(request()->routeIs('faq')) page @endif">
                    <i class="fas fa-question-circle w-4 mr-3" aria-hidden="true"></i>FAQ
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link @if(request()->routeIs('about')) active @endif">
                    <i class="fas fa-info-circle w-4 mr-3"></i>Über uns
                </a>
                <a href="{{ route('contact') }}" class="mobile-nav-link @if(request()->routeIs('contact')) active @endif">
                    <i class="fas fa-envelope w-4 mr-3"></i>Kontakt
                </a>
                
                
                <div class="border-t border-neutral-200 mt-3 pt-3">
                @guest
                    <a href="{{ route('login') }}" class="mobile-nav-link">
                        <i class="fas fa-sign-in-alt w-4 mr-3"></i>Anmelden
                    </a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">
                        <i class="fas fa-user-plus w-4 mr-3"></i>Registrieren
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                        <i class="fas fa-tachometer-alt w-4 mr-3"></i>Dashboard
                    </a>
                    <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.show.id', Auth::user()->id) }}" class="mobile-nav-link">
                        <i class="fas fa-user w-4 mr-3"></i>Mein Profil
                    </a>
                    <a href="{{ route('profile.edit-profile') }}" class="mobile-nav-link">
                        <i class="fas fa-edit w-4 mr-3"></i>Profil bearbeiten
                    </a>
                    <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                        <i class="fas fa-cog w-4 mr-3"></i>Passwort ändern
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="w-full text-left font-medium text-error-600 hover:text-error-700 px-1 py-2 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt w-4 mr-3"></i>Abmelden
                        </button>
                    </form>
                @endguest
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- MODERNER FOOTER - Kompakter und eleganter -->
    <footer class="bg-gradient-dark text-white">
        <div class="modern-container modern-section-compact">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- MODERNE COMPANY INFO -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('images/apb-logo-512.png') }}" alt="KI-Coding Logo" class="h-10 w-10">
                        <span class="text-2xl font-display font-bold bg-gradient-logo bg-clip-text text-transparent">KI-Coding</span>
                    </div>
                    <p class="text-neutral-300 mb-6 leading-relaxed">
                        Kostenlose Wissensdatenbank für KI-gestütztes Programmieren. 
                        Tutorials, Tools, Best Practices - alles gratis für die Entwickler*innen-Community.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://github.com/christinloehner/ki-coding" class="p-2 bg-neutral-800 hover:bg-primary-600 rounded-xl transition-all duration-300 hover:scale-110" aria-label="GitHub" target="_blank" rel="noopener noreferrer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- MODERNE QUICK LINKS -->
                <div>
                    <h3 class="text-lg font-display font-semibold mb-4 text-white">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-neutral-300 hover:text-primary-400 transition-colors font-medium">Home</a></li>
                        <li><a href="{{ route('wiki.index') }}" class="text-neutral-300 hover:text-primary-400 transition-colors font-medium">Wiki</a></li>
                        <li><a href="{{ route('faq') }}" class="text-neutral-300 hover:text-primary-400 transition-colors font-medium">FAQ</a></li>
                        <li><a href="{{ route('about') }}" class="text-neutral-300 hover:text-primary-400 transition-colors font-medium">Über uns</a></li>
                        <li><a href="{{ route('contact') }}" class="text-neutral-300 hover:text-primary-400 transition-colors font-medium">Kontakt</a></li>
                    </ul>
                </div>

                <!-- MODERNE LEGAL LINKS -->
                <div>
                    <h3 class="text-lg font-display font-semibold mb-4 text-white">Rechtliches</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('privacy') }}" class="text-neutral-300 hover:text-secondary-400 transition-colors font-medium">Datenschutz</a></li>
                        <li><a href="{{ route('imprint') }}" class="text-neutral-300 hover:text-secondary-400 transition-colors font-medium">Impressum</a></li>
                        <li><a href="#" onclick="klaro.show(); return false;" class="text-neutral-300 hover:text-secondary-400 transition-colors font-medium">Cookie-Einstellungen</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-neutral-700 mt-10 pt-6 text-center">
                <p class="text-neutral-400 font-medium">&copy; {{ date('Y') }} KI-Coding. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </footer>

    <!-- Klaro Cookie Consent -->
    <script src="{{ asset('js/klaro-config.js') }}"></script>
    <script defer src="{{ asset('js/klaro.js') }}"></script>
    
    
    <!-- MODERNER NACH-OBEN BUTTON -->
    <button id="scrollToTopBtn" 
            class="fixed bottom-6 right-6 w-14 h-14 glass-card hover:glass-card-dark bg-primary-500/20 hover:bg-primary-500/80 backdrop-blur-lg border border-primary-300/30 hover:border-primary-400/50 rounded-full shadow-2xl hover:shadow-primary transition-all duration-300 z-50 opacity-0 invisible translate-y-4 hover:scale-110 active:scale-95 focus:outline-none focus:ring-4 focus:ring-primary-200/50"
            aria-label="Nach oben scrollen"
            title="Nach oben scrollen">
        <svg class="w-6 h-6 text-primary-700 hover:text-white transition-colors duration-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <script>
        // MODERNER SCROLL-TO-TOP BUTTON mit sanften Animationen
        document.addEventListener('DOMContentLoaded', function() {
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');
            let isVisible = false;
            
            // Show/Hide button based on scroll position
            function toggleScrollButton() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 150 && !isVisible) {
                    // Show button with modern animation
                    scrollToTopBtn.style.opacity = '1';
                    scrollToTopBtn.style.visibility = 'visible';
                    scrollToTopBtn.style.transform = 'translateY(0)';
                    isVisible = true;
                } else if (scrollTop <= 150 && isVisible) {
                    // Hide button with smooth animation
                    scrollToTopBtn.style.opacity = '0';
                    scrollToTopBtn.style.visibility = 'hidden';
                    scrollToTopBtn.style.transform = 'translateY(16px)';
                    isVisible = false;
                }
            }
            
            // Smooth scroll to top function
            function scrollToTop() {
                const scrollDuration = 600;
                const scrollHeight = window.pageYOffset;
                const scrollStep = Math.PI / (scrollDuration / 15);
                const cosParameter = scrollHeight / 2;
                let scrollCount = 0;
                let scrollMargin;
                
                function step() {
                    setTimeout(() => {
                        if (window.pageYOffset !== 0) {
                            scrollMargin = cosParameter - cosParameter * Math.cos(scrollCount);
                            window.scrollTo(0, scrollHeight - scrollMargin);
                            scrollCount += scrollStep;
                            step();
                        }
                    }, 15);
                }
                step();
            }
            
            // Event listeners
            window.addEventListener('scroll', toggleScrollButton);
            scrollToTopBtn.addEventListener('click', scrollToTop);
            
            // Keyboard accessibility
            scrollToTopBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    scrollToTop();
                }
            });
            
            // Initial check on page load
            toggleScrollButton();
        });
    </script>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>