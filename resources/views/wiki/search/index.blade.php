@extends('layouts.app')

@section('title', 'Suche' . (request('q') ? ' - ' . request('q') : '') . ' - KI-Coding Wiki')
@section('description', 'Durchsuche die KI-Coding Knowledge Base nach Artikeln, Kategorien und Tags')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900">
            @if(request('q'))
                Suchergebnisse für "{{ request('q') }}"
            @else
                Wiki durchsuchen
            @endif
        </h1>
        @if(request('q'))
            <p class="mt-2 text-gray-600">
                {{ $totalResults ?? 0 }} Ergebnisse gefunden
                @if($searchTime ?? false)
                    in {{ number_format($searchTime, 3) }} Sekunden
                @endif
            </p>
        @else
            <p class="mt-2 text-gray-600">
                Finde Artikel, Kategorien und Tags in unserer Knowledge Base
            </p>
        @endif
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-primary p-6 mb-8">
        <form method="GET" action="{{ route('wiki.search') }}" class="space-y-4">
            <!-- Main Search -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search-query" class="sr-only">Suchbegriff</label>
                    <div class="relative">
                        <input type="text"
                               id="search-query"
                               name="q"
                               value="{{ request('q') }}"
                               class="w-full px-4 py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-lg"
                               placeholder="Artikel, Kategorien oder Tags suchen..."
                               autofocus>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-ki-primary whitespace-nowrap">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Suchen
                </button>
            </div>

            <!-- Advanced Filters -->
            <div class="border-t border-gray-200 pt-4">
                <button type="button"
                        class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900"
                        onclick="toggleAdvancedFilters()">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
                    Erweiterte Filter
                    <svg class="w-4 h-4 ml-1 transform transition-transform" id="filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div id="advanced-filters" class="hidden mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Typ</label>
                        <select name="type" id="type" class="form-input">
                            <option value="">Alle Typen</option>
                            <option value="articles" {{ request('type') === 'articles' ? 'selected' : '' }}>Artikel</option>
                            <option value="categories" {{ request('type') === 'categories' ? 'selected' : '' }}>Kategorien</option>
                            <option value="tags" {{ request('type') === 'tags' ? 'selected' : '' }}>Tags</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                        <select name="category" id="category" class="form-input">
                            <option value="">Alle Kategorien</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->slug }}"
                                        {{ request('category') === $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sortierung</label>
                        <select name="sort" id="sort" class="form-input">
                            <option value="relevance" {{ request('sort') === 'relevance' ? 'selected' : '' }}>Relevanz</option>
                            <option value="date" {{ request('sort') === 'date' ? 'selected' : '' }}>Datum</option>
                            <option value="popularity" {{ request('sort') === 'popularity' ? 'selected' : '' }}>Beliebtheit</option>
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Titel</option>
                        </select>
                    </div>

                    <!-- Author Filter -->
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                        <input type="text"
                               name="author"
                               id="author"
                               value="{{ request('author') }}"
                               class="form-input"
                               placeholder="Autor-Name">
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Datum von</label>
                        <input type="date"
                               name="date_from"
                               id="date_from"
                               value="{{ request('date_from') }}"
                               class="form-input">
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Datum bis</label>
                        <input type="date"
                               name="date_to"
                               id="date_to"
                               value="{{ request('date_to') }}"
                               class="form-input">
                    </div>
                </div>
            </div>

            <!-- Active Filters -->
            @if(request()->except(['q', 'page']))
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700">Aktive Filter:</span>
                        @foreach(request()->except(['q', 'page']) as $key => $value)
                            @if($value)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ ucfirst($key) }}: {{ $value }}
                                    <a href="{{ request()->fullUrlWithQuery([$key => null]) }}" class="ml-1 text-indigo-600 hover:text-indigo-800">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                </span>
                            @endif
                        @endforeach
                        <a href="{{ route('wiki.search', ['q' => request('q')]) }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Alle Filter entfernen
                        </a>
                    </div>
                </div>
            @endif
        </form>
    </div>

    @if(request('q'))
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Results -->
            <div class="lg:col-span-3">
                @if($results && $totalResults > 0)
                    <div class="space-y-6">
                        @foreach($results as $result)
                            <div class="bg-white rounded-lg shadow-primary border border-gray-200 p-6">
                                <!-- Result Header -->
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                   {{ $result->type === 'article' ? 'bg-primary-100 text-primary-800' :
                                                      ($result->type === 'category' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($result->type) }}
                                        </span>
                                        @if($result->type === 'article' && $result->category)
                                            <span class="text-sm text-gray-500">in {{ $result->category->name }}</span>
                                        @endif
                                    </div>
                                    @if($result->score ?? false)
                                        <span class="text-xs text-gray-400">{{ number_format($result->score * 100, 1) }}% Match</span>
                                    @endif
                                </div>

                                <!-- Result Content -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ $result->url }}" class="hover:text-primary-600">
                                        {!! $result->highlighted_title ?? $result->title !!}
                                    </a>
                                </h3>

                                @if($result->excerpt)
                                    <p class="text-gray-600 mb-3">
                                        {!! $result->highlighted_excerpt ?? $result->excerpt !!}
                                    </p>
                                @endif

                                <!-- Result Meta -->
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        @if($result->author)
                                            <span>von {{ $result->author }}</span>
                                        @endif
                                        @if($result->date)
                                            <span>{{ $result->date->format('d.m.Y') }}</span>
                                        @endif
                                        @if($result->views_count)
                                            <span>{{ $result->views_count }} Aufrufe</span>
                                        @endif
                                    </div>
                                    @if($result->tags && $result->tags->count() > 0)
                                        <div class="flex space-x-1">
                                            @foreach($result->tags->take(3) as $tag)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination disabled - using Collection instead of Paginator -->
                    {{-- @if($results->hasPages())
                        <div class="mt-8">
                            {{ $results->appends(request()->query())->links() }}
                        </div>
                    @endif --}}
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Ergebnisse gefunden</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Versuche es mit anderen Suchbegriffen oder passe deine Filter an.
                        </p>
                        <div class="mt-4 flex justify-center space-x-4">
                            <a href="{{ route('wiki.search') }}" class="btn-ki-outline-sm">
                                Filter zurücksetzen
                            </a>
                            <a href="{{ route('wiki.articles.index') }}" class="btn-ki-primary-sm">
                                Alle Artikel durchsuchen
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Popular Categories -->
                    @if($popularCategories ?? false)
                        <div class="bg-white rounded-lg shadow-primary p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Beliebte Kategorien</h3>
                            <div class="space-y-2">
                                @foreach($popularCategories as $category)
                                    <a href="{{ route('wiki.search', ['q' => request('q'), 'category' => $category->slug]) }}"
                                       class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50">
                                        <span class="text-sm font-medium text-gray-900">{{ $category->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $category->articles_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Popular Tags -->
                    @if($popularTags ?? false)
                        <div class="bg-white rounded-lg shadow-primary p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Beliebte Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags as $tag)
                                    <a href="{{ route('wiki.search', ['q' => request('q'), 'tag' => $tag->slug]) }}"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-indigo-100 hover:text-indigo-800">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Recent Searches -->
                    @if($recentSearches ?? false)
                        <div class="bg-white rounded-lg shadow-primary p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Neueste Suchen</h3>
                            <div class="space-y-2">
                                @foreach($recentSearches as $search)
                                    <a href="{{ route('wiki.search', ['q' => $search]) }}"
                                       class="block p-2 rounded-lg hover:bg-gray-50">
                                        <span class="text-sm text-gray-900">{{ $search }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- No Search Query - Show Popular Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Popular Articles -->
            <div class="bg-white rounded-lg shadow-primary p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Beliebte Artikel</h2>
                @if($popularArticles ?? false)
                    <div class="space-y-3">
                        @foreach($popularArticles as $article)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-primary-600">
                                            {{ $article->title }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $article->views_count }} Aufrufe</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Categories -->
            <div class="bg-white rounded-lg shadow-primary p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Kategorien</h2>
                @if($categories ?? false)
                    <div class="space-y-3">
                        @foreach($categories->take(5) as $category)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                         style="background-color: {{ $category->color ?? '#6366f1' }}20;">
                                        <svg class="w-4 h-4" style="color: {{ $category->color ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <a href="{{ route('wiki.categories.show', $category->slug) }}" class="hover:text-primary-600">
                                            {{ $category->name }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $category->articles_count }} Artikel</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Articles -->
            <div class="bg-white rounded-lg shadow-primary p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Neueste Artikel</h2>
                @if($recentArticles ?? false)
                    <div class="space-y-3">
                        @foreach($recentArticles as $article)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-secondary-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-primary-600">
                                            {{ $article->title }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $article->created_at->format('d.m.Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function toggleAdvancedFilters() {
    const filters = document.getElementById('advanced-filters');
    const chevron = document.getElementById('filter-chevron');

    filters.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

// Auto-submit form when select changes
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('#advanced-filters select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush
@endsection
