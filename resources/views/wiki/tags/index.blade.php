@extends('layouts.app')

@section('title', 'Tags - KI-Coding Wiki')
@section('description', 'Entdecke alle Tags und Schlagwörter der KI-Coding Knowledge Base')

@section('content')
<!-- Hero Header -->
<x-hero-header 
    title="Wiki-Tags" 
    subtitle="Entdecke alle Schlagwörter, finde verwandte Inhalte und navigiere durch unsere strukturierte Knowledge Base."
    gradient="gradient-sunset">
    <x-slot name="actions">
        @can('create', App\Models\Tag::class)
            <a href="{{ route('wiki.tags.create') }}" class="btn-ki-primary-inverse">
                <i class="fas fa-tag mr-2"></i>Neuen Tag erstellen
            </a>
        @endcan
    </x-slot>
</x-hero-header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Gesamt Tags</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tags->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-secondary-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Artikel mit Tags</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalArticles ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-accent-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Beliebtester Tag</p>
                    @if($mostPopularTag ?? false)
                        <p class="text-lg font-semibold text-gray-900">{{ $mostPopularTag->name }}</p>
                        <p class="text-sm text-gray-500">{{ $mostPopularTag->articles_count }} Artikel</p>
                    @else
                        <p class="text-lg font-semibold text-gray-900">-</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Ø Artikel/Tag</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $averageArticlesPerTag ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tag Cloud -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tag Cloud</h2>
        @if($tags->count() > 0)
            <div class="tag-cloud">
                @foreach($tags as $tag)
                    @php
                        $count = $tag->articles_count ?? 0;
                        $size = min(max($count / 2 + 1, 1), 4); // Scale between 1 and 4
                        $opacity = min(max($count / 5 + 0.6, 0.6), 1); // Scale opacity
                    @endphp
                    <a href="{{ route('wiki.tags.show', $tag->slug) }}"
                       class="inline-block m-1 px-3 py-1 rounded-full text-primary-600 hover:text-primary-800 hover:bg-primary-50 transition-colors"
                       style="font-size: {{ $size * 0.8 + 0.7 }}rem; opacity: {{ $opacity }};"
                       title="{{ $tag->name }}: {{ $count }} Artikel">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Noch keine Tags verfügbar.</p>
        @endif
    </div>

    <!-- Filter and Sort -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Sortierung:</label>
                <select name="sort" class="form-input" onchange="this.form.submit()">
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="count" {{ request('sort') === 'count' ? 'selected' : '' }}>Anzahl Artikel</option>
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Neueste</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Tags suchen..."
                       class="form-input">
                <button type="submit" class="btn-ki-outline-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Tags List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tags as $tag)
            <div class="bg-white rounded-lg shadow-primary border border-gray-200 p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                <a href="{{ route('wiki.tags.show', $tag->slug) }}" class="hover:text-primary-600">
                                    {{ $tag->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500">{{ $tag->articles_count ?? 0 }} Artikel</p>
                        </div>
                    </div>
                    @can('update', $tag)
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('wiki.tags.edit', $tag->slug) }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    @endcan
                </div>

                @if($tag->description)
                    <p class="text-gray-600 mb-4">{{ $tag->description }}</p>
                @endif

                <!-- Recent Articles -->
                @if($tag->articles && $tag->articles->count() > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Neueste Artikel</h4>
                        <div class="space-y-2">
                            @foreach($tag->articles()->published()->latest()->take(3)->get() as $article)
                                <div class="flex items-center justify-between text-sm">
                                    <a href="{{ route('wiki.articles.show', $article->slug) }}"
                                       class="text-primary-600 hover:text-primary-800 truncate">
                                        {{ $article->title }}
                                    </a>
                                    <span class="text-gray-400 text-xs ml-2">{{ $article->published_at->format('d.m.Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Related Tags -->
                @if($tag->related_tags && $tag->related_tags->count() > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Verwandte Tags</h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach($tag->related_tags->take(3) as $related)
                                <a href="{{ route('wiki.tags.show', $related->slug) }}"
                                   class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 hover:bg-primary-100 hover:text-primary-800">
                                    {{ $related->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                    <a href="{{ route('wiki.tags.show', $tag->slug) }}" class="btn-ki-outline-sm">
                        Alle Artikel anzeigen
                    </a>
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ $tag->articles()->published()->sum('views_count') ?? 0 }} Aufrufe
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Noch keine Tags</h3>
                    <p class="mt-1 text-sm text-gray-500">Erstelle den ersten Tag für deine Knowledge Base.</p>
                    @can('create', App\Models\Tag::class)
                        <div class="mt-6">
                            <a href="{{ route('wiki.tags.create') }}" class="btn-ki-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Ersten Tag erstellen
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tags->hasPages())
        <div class="mt-8">
            {{ $tags->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.form-input-sm {
    @apply px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm;
}

.tag-cloud {
    line-height: 2;
    text-align: center;
}

.tag-cloud a {
    display: inline-block;
    margin: 0.2rem;
    padding: 0.3rem 0.8rem;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    color: var(--color-primary-600);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.tag-cloud a:hover {
    background-color: var(--color-primary-50);
    border-color: var(--color-primary-300);
    transform: translateY(-1px);
}
</style>
@endpush
@endsection
