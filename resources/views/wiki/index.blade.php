@extends('layouts.app')

@section('title', 'KI-Programmierung Wiki: ' . ($stats['articles'] ?? '100+') . ' kostenlose Tutorials | GitHub Copilot & ChatGPT')
@section('description', 'Durchsuche ' . ($stats['articles'] ?? '100+') . ' kostenlose KI-Programmierung Tutorials! GitHub Copilot, ChatGPT, Claude AI, Prompt Engineering. ' . ($stats['categories'] ?? '10+') . ' Kategorien. Lerne KI-Coding gratis.')
@section('keywords', 'KI Programmierung Wiki, GitHub Copilot Anleitung, ChatGPT Coding Tutorial, Claude AI Programmierung, Prompt Engineering Guide, KI Tools Entwickler, AI Assistant Tutorial, Machine Learning Coding, KI-gestützte Entwicklung, Open Source KI Learning, Programmieren mit AI, Coding Automation, KI Development Tools')
@section('robots', 'index, follow, max-image-preview:large')

@section('og_title', 'KI-Programmierung Wiki: ' . ($stats['articles'] ?? '100+') . ' kostenlose Tutorials')
@section('og_description', 'Durchsuche ' . ($stats['articles'] ?? '100+') . ' kostenlose KI-Programmierung Tutorials! GitHub Copilot, ChatGPT, Claude AI, Prompt Engineering. Lerne KI-Coding gratis.')
@section('og_type', 'website')
@section('og_image', asset('images/wiki-social.jpg'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_alt', 'KI-Programmierung Wiki mit ' . ($stats['articles'] ?? '100+') . ' kostenlosen Tutorials')

@section('twitter_title', 'KI-Programmierung Wiki: ' . ($stats['articles'] ?? '100+') . ' kostenlose Tutorials')
@section('twitter_description', 'GitHub Copilot, ChatGPT, Claude AI Tutorials. ' . ($stats['articles'] ?? '100+') . ' kostenlose KI-Programmierung Guides. Open Source Community.')
@section('twitter_image', asset('images/wiki-social.jpg'))
@section('twitter_image_alt', 'KI-Programmierung Wiki Knowledge Base')


@section('content')
<!-- Hero Section -->
<section class="bg-gradient-ki-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-high-contrast mb-6">
                KI-Coding 
                <span class="bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                    Wiki
                </span>
            </h1>
            <p class="text-xl text-medium-contrast mb-8 max-w-2xl mx-auto">
                Durchsuche unsere umfassende Wissensdatenbank mit Tutorials, Best Practices und Tools für KI-gestütztes Programmieren.
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-8">
                <form action="{{ route('wiki.search') }}" method="GET" class="relative" role="search">
                    <div class="relative">
                        <label for="wiki-search" class="sr-only">Wiki durchsuchen</label>
                        <input 
                            id="wiki-search"
                            type="text" 
                            name="q" 
                            placeholder="Suche nach Artikeln, Kategorien oder Tags..." 
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-lg transition-all duration-300"
                            value="{{ request('q') }}"
                            aria-describedby="search-help"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary-600 transition-colors duration-300" aria-label="Suche starten">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="search-help" class="sr-only">Durchsuche alle Wiki-Artikel, Kategorien und Tags nach relevanten Inhalten</div>
                </form>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto" role="region" aria-label="Wiki-Statistiken">
                <div class="bg-white rounded-lg p-4 shadow-primary hover:shadow-lg transition-all duration-300" role="stat">
                    <div class="text-2xl font-bold text-primary-600" aria-label="{{ $stats['articles'] }} Artikel verfügbar">{{ $stats['articles'] }}</div>
                    <div class="text-sm text-gray-600">Artikel</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-secondary hover:shadow-lg transition-all duration-300" role="stat">
                    <div class="text-2xl font-bold text-secondary-600" aria-label="{{ $stats['categories'] }} Kategorien verfügbar">{{ $stats['categories'] }}</div>
                    <div class="text-sm text-gray-600">Kategorien</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-accent hover:shadow-lg transition-all duration-300" role="stat">
                    <div class="text-2xl font-bold text-accent-600" aria-label="{{ $stats['tags'] }} Tags verfügbar">{{ $stats['tags'] }}</div>
                    <div class="text-sm text-gray-600">Tags</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-purple hover:shadow-lg transition-all duration-300" role="stat">
                    <div class="text-2xl font-bold text-purple-600" aria-label="{{ $stats['contributors'] }} Autor*innen aktiv">{{ $stats['contributors'] }}</div>
                    <div class="text-sm text-gray-600">Autor*innen</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Articles -->
@if($featuredArticles->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-display font-bold text-high-contrast mb-4">
                Empfohlene Artikel
            </h2>
            <p class="text-xl text-medium-contrast max-w-2xl mx-auto">
                Entdecke unsere am häufigsten empfohlenen und wertvollsten Artikel
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredArticles as $article)
                <article class="card hover:shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('wiki.articles.show', $article->slug) }}'">
                    <div class="card-body">
                        <div class="flex items-center mb-3">
                            <span class="badge badge-primary mr-2">Featured</span>
                            <span class="text-sm text-gray-500">{{ $article->category->name ?? 'Unkategorisiert' }}</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-gray-900 hover:text-primary-600 transition-colors duration-300">
                            {{ $article->title }}
                        </h3>
                        <p class="text-gray-600 mb-4">{{ $article->excerpt }}</p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <a href="{{ route('wiki.users.show', $article->user->id) }}" class="text-gray-600 hover:text-primary-600 transition-colors duration-300" onclick="event.stopPropagation();">
                                {{ $article->user->name }}
                            </a>
                            <div class="flex items-center space-x-3">
                                <span>{{ $article->reading_time }} min</span>
                                <span title="Kommentare">
                                    <i class="fas fa-comment text-gray-400 mr-1"></i>{{ $article->comments_count ?? 0 }}
                                </span>
                                <span title="Likes">
                                    <i class="fas fa-heart text-gray-400 mr-1"></i>{{ $article->likes_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Main Content -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Articles -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-display font-bold text-high-contrast">
                            Neueste Artikel
                        </h2>
                        <a href="{{ route('wiki.articles.index') }}" class="text-primary-600 hover:text-primary-800 transition-colors duration-300">
                            Alle anzeigen →
                        </a>
                    </div>

                    <div class="space-y-6">
                        @foreach($recentArticles as $article)
                            <article class="border-b border-gray-200 pb-6 last:border-b-0 hover:bg-gray-50 transition-colors duration-300 cursor-pointer p-2 -mx-2 rounded" onclick="window.location.href='{{ route('wiki.articles.show', $article->slug) }}'">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="badge badge-secondary mr-2">{{ $article->category->name ?? 'Unkategorisiert' }}</span>
                                            <span class="text-sm text-gray-500">{{ $article->published_at->format('d.m.Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-semibold mb-2 text-gray-900 hover:text-primary-600 transition-colors duration-300">
                                            {{ $article->title }}
                                        </h3>
                                        <p class="text-gray-600 mb-3">{{ $article->excerpt }}</p>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <span>von <a href="{{ route('wiki.users.show', $article->user->id) }}" class="text-gray-600 hover:text-primary-600 transition-colors duration-300" onclick="event.stopPropagation();">{{ $article->user->name }}</a></span>
                                            <div class="flex items-center space-x-3">
                                                <span>{{ $article->reading_time }} min</span>
                                                <span>{{ $article->views_count }} Aufrufe</span>
                                                <span title="Kommentare">
                                                    <i class="fas fa-comment text-gray-400 mr-1"></i>{{ $article->comments_count ?? 0 }}
                                                </span>
                                                <span title="Likes">
                                                    <i class="fas fa-heart text-gray-400 mr-1"></i>{{ $article->likes_count ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Categories -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Kategorien</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <div>
                                <a href="{{ route('wiki.categories.show', $category->slug) }}" class="block text-gray-700 hover:text-primary-600 font-medium transition-colors duration-300">
                                    {{ $category->name }}
                                </a>
                                @if($category->children->count() > 0)
                                    <div class="ml-4 mt-1 space-y-1">
                                        @foreach($category->children as $child)
                                            <a href="{{ route('wiki.categories.show', $child->slug) }}" class="block text-sm text-gray-600 hover:text-primary-600 transition-colors duration-300">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Beliebte Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('wiki.tags.show', $tag->slug) }}" class="badge badge-secondary hover:bg-primary-100 hover:text-primary-800 transition-all duration-300">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Popular Articles -->
                @if($popularArticles->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Beliebte Artikel</h3>
                    <div class="space-y-3">
                        @foreach($popularArticles as $article)
                            <div class="border-b border-gray-200 pb-3 last:border-b-0">
                                <h4 class="font-medium">
                                    <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-primary-600 transition-colors duration-300">
                                        {{ $article->title }}
                                    </a>
                                </h4>
                                <div class="text-sm text-gray-500 mt-1">
                                    {{ $article->views_count }} Aufrufe
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-gradient-ocean relative overflow-hidden">
    <!-- Glassmorphism Overlay -->
    <div class="absolute inset-0 glass-dark"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-display font-bold text-white mb-4 text-shadow-lg animate-fade-in">
            Werde Teil unserer Community
        </h2>
        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto animate-slide-up">
            Teile dein Wissen und hilf anderen Entwickler*innen beim Lernen von KI-gestütztem Programmieren.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up">
            @guest
                <a href="{{ route('register') }}" class="btn-ki-primary-inverse">
                    <i class="fas fa-user-plus mr-2"></i>Jetzt registrieren
                </a>
                <a href="{{ route('login') }}" class="btn-ki-outline border-white text-white hover:bg-white hover:text-primary-600">
                    <i class="fas fa-sign-in-alt mr-2"></i>Anmelden
                </a>
            @else
                @can('create', App\Models\Article::class)
                    <a href="{{ route('wiki.articles.create') }}" class="btn-ki-primary-inverse">
                        <i class="fas fa-edit mr-2"></i>Artikel schreiben
                    </a>
                @endcan
                <a href="{{ route('dashboard') }}" class="btn-ki-primary-inverse">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
            @endguest
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
</section>
@endsection

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": ["WebPage", "CollectionPage"],
    "name": "KI-Programmierung Wiki",
    "description": "Durchsuche {{ $stats['articles'] ?? '100+' }} kostenlose KI-Programmierung Tutorials! GitHub Copilot, ChatGPT, Claude AI, Prompt Engineering.",
    "url": "{{ route('wiki.index') }}",
    "mainEntity": {
        "@@type": "ItemList",
        "name": "KI-Programmierung Artikel",
        "numberOfItems": {{ $stats['articles'] ?? 100 }},
        "description": "Kostenlose Tutorials und Guides für KI-gestütztes Programmieren"
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
                "name": "Wiki",
                "item": "{{ route('wiki.index') }}"
            }
        ]
    },
    "publisher": {
        "@@type": "Organization",
        "name": "KI-Coding",
        "url": "{{ config('app.url') }}"
    },
    "inLanguage": "de-DE",
    "audience": {
        "@@type": "Audience",
        "audienceType": "Entwickler, Programmierer, Software Engineers"
    },
    "about": [
        {
            "@@type": "Thing",
            "name": "KI-Programmierung",
            "sameAs": "https://de.wikipedia.org/wiki/K%C3%BCnstliche_Intelligenz"
        },
        {
            "@@type": "Thing",
            "name": "GitHub Copilot",
            "sameAs": "https://github.com/features/copilot"
        },
        {
            "@@type": "Thing",
            "name": "Prompt Engineering"
        }
    ]
}
</script>
@endpush