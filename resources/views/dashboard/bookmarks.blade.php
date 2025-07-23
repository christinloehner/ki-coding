@extends('layouts.app')

@section('title', 'Meine Lesezeichen - Dashboard')
@section('description', 'Übersicht aller Artikel, die du als Lesezeichen gespeichert hast')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-700">Dashboard</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">Meine Lesezeichen</span>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    Meine Lesezeichen
                </h1>
                <p class="mt-2 text-gray-600">
                    Alle Artikel, die du gespeichert hast ({{ $bookmarkedArticles->total() }} Artikel)
                </p>
            </div>
            <a href="{{ route('wiki.index') }}" class="btn-ki-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Neue Artikel entdecken
            </a>
        </div>
    </div>

    @if($bookmarkedArticles->count() > 0)
        <!-- Artikel Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($bookmarkedArticles as $article)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow group">
                    <div class="p-6">
                        <!-- Article Header -->
                        <div class="flex items-start justify-between mb-4">
                            <span class="badge badge-secondary">{{ $article->category->name ?? 'Allgemein' }}</span>
                            <button onclick="removeBookmark('{{ $article->slug }}')" 
                                    class="text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity" 
                                    title="Lesezeichen entfernen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-primary-600 transition-colors">
                                {{ $article->title }}
                            </a>
                        </h3>

                        <!-- Excerpt -->
                        @if($article->excerpt)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $article->excerpt }}</p>
                        @endif

                        <!-- Article Meta -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <div class="flex items-center space-x-4">
                                <span>von {{ $article->user->name ?? 'Unbekannt' }}</span>
                                <span>{{ $article->reading_time ?? 5 }} min</span>
                                <span title="Kommentare"><i class="fas fa-comment text-gray-400 mr-1"></i>{{ $article->comments_count ?? 0 }}</span>
                                <span title="Likes"><i class="fas fa-heart text-gray-400 mr-1"></i>{{ $article->likes_count ?? 0 }}</span>
                                <span>{{ $article->views_count ?? 0 }} Aufrufe</span>
                            </div>
                        </div>

                        <!-- Bookmark Date -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                Gemerkt am {{ $article->bookmarked_at ? \Carbon\Carbon::parse($article->bookmarked_at)->format('d.m.Y') : 'N/A' }}
                            </span>
                            <a href="{{ route('wiki.articles.show', $article->slug) }}" 
                               class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                Lesen →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($bookmarkedArticles->hasPages())
            <div class="flex justify-center">
                {{ $bookmarkedArticles->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Noch keine Lesezeichen</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                Du hast noch keine Artikel als Lesezeichen gespeichert. Entdecke interessante Artikel und merke sie dir für später.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('wiki.index') }}" class="btn-ki-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Wiki durchstöbern
                </a>
                <a href="{{ route('dashboard') }}" class="btn-ki-outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Zurück zum Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

<script>
function removeBookmark(articleSlug) {
    if (!confirm('Lesezeichen entfernen?')) {
        return;
    }
    
    fetch(`/wiki/articles/${articleSlug}/bookmark`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Seite neu laden um die Änderung anzuzeigen
            window.location.reload();
        } else {
            alert('Fehler beim Entfernen des Lesezeichens: ' + (data.message || 'Unbekannter Fehler'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Entfernen des Lesezeichens. Bitte versuche es erneut.');
    });
}
</script>

@endsection