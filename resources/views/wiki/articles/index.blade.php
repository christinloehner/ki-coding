@extends('layouts.app')

@section('title', 'KI-Programmierung Artikel - Kostenlose Tutorials | GitHub Copilot, ChatGPT & AI-Coding')
@section('description', 'Entdecke alle KI-Programmierung Artikel und Tutorials. GitHub Copilot, ChatGPT, Claude AI, Prompt Engineering. Kostenlose Anleitungen für Entwickler*innen.')
@section('keywords', 'KI-Programmierung Artikel, GitHub Copilot Tutorial, ChatGPT Coding, AI-Coding Anleitung, Claude AI Programmierung, Prompt Engineering, KI-Tools Entwickler, Machine Learning Tutorial, Kostenlos lernen')
@section('robots', 'index, follow')

@section('og_title', 'KI-Programmierung Artikel - Kostenlose Tutorials')
@section('og_description', 'Alle KI-Programmierung Artikel: GitHub Copilot, ChatGPT, Claude AI Tutorials. Kostenlose Anleitungen für KI-gestütztes Programmieren.')
@section('og_type', 'website')
@section('og_image', asset('images/articles-social.jpg'))

@section('twitter_title', 'KI-Programmierung Artikel - Kostenlose Tutorials')
@section('twitter_description', 'GitHub Copilot, ChatGPT, Claude AI Tutorials. Kostenlose KI-Programmierung Anleitungen für Entwickler*innen.')

@section('content')
<!-- Hero Header -->
<x-hero-header 
    title="Alle Artikel" 
    subtitle="Durchsuche alle verfügbaren Artikel in unserer umfassenden Wissensdatenbank für KI-gestütztes Programmieren."
    gradient="gradient-forest">
    <x-slot name="actions">
        @auth
            @can('create', App\Models\Article::class)
                <a href="{{ route('wiki.articles.create') }}" class="btn-ki-primary-inverse">
                    <i class="fas fa-plus mr-2"></i>Neuen Artikel erstellen
                </a>
            @endcan
        @endauth
    </x-slot>
</x-hero-header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form method="GET" action="{{ route('wiki.articles.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="form-label">Suche</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Artikel suchen..."
                    class="form-input"
                >
            </div>

            <!-- Category -->
            <div>
                <label class="form-label">Kategorie</label>
                <select name="category" class="form-input">
                    <option value="">Alle Kategorien</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="form-label">Sortierung</label>
                <select name="sort" class="form-input">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Neueste</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Beliebt</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Alphabetisch</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-end">
                <button type="submit" class="btn-ki-primary">
                    Filtern
                </button>
            </div>
        </form>
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <article class="card hover:shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('wiki.articles.show', $article->slug) }}'">
                <div class="card-body">
                    <div class="flex items-center mb-3">
                        <span class="badge badge-secondary mr-2">{{ $article->category->name ?? 'Unkategorisiert' }}</span>
                        <span class="text-sm text-gray-500">{{ $article->published_at->format('d.m.Y') }}</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900 hover:text-primary-600 transition-colors duration-300">
                        {{ $article->title }}
                    </h3>
                    <p class="text-gray-600 mb-4">{{ $article->excerpt }}</p>
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
                    @if($article->tags->count() > 0)
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($article->tags as $tag)
                                <span class="badge badge-secondary">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Artikel gefunden</h3>
                <p class="text-gray-500 mb-6">Es wurden keine Artikel gefunden, die deinen Suchkriterien entsprechen.</p>
                <a href="{{ route('wiki.index') }}" class="btn-ki-primary">
                    Zur Wiki-Startseite
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection