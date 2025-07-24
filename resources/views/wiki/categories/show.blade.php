@extends('layouts.app')

@section('title', $category->name . ' - KI-Coding Wiki | Kostenlose ' . $category->name . ' Tutorials')
@section('description', $category->description ?? 'Entdecke kostenlose ' . $category->name . ' Tutorials und Anleitungen für KI-gestütztes Programmieren. Lerne Schritt-für-Schritt mit praktischen Beispielen.')
@section('keywords', $category->name . ', KI-Programmierung, Tutorial, Anleitung, AI-Coding, ' . ($category->description ? 'Entwicklung, ' : '') . 'Kostenlos lernen, KI-Tools')
@section('robots', 'index, follow')

@section('og_title', $category->name . ' Tutorials - KI-Coding Wiki')
@section('og_description', 'Kostenlose ' . $category->name . ' Tutorials für KI-gestütztes Programmieren. Praktische Anleitungen und Best Practices.')
@section('og_type', 'website')
@section('og_image', asset('images/category-social.jpg'))

@section('twitter_title', $category->name . ' - Kostenlose Tutorials')
@section('twitter_description', 'Lerne ' . $category->name . ' mit kostenlosen Tutorials. KI-gestütztes Programmieren für alle.')


@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('wiki.index') }}" class="text-gray-700 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2H3z"></path>
                    </svg>
                    Wiki
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('wiki.categories.index') }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2">Kategorien</a>
                </div>
            </li>
            @if($parentCategory)
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('wiki.categories.show', $parentCategory->slug) }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2">{{ $parentCategory->name }}</a>
                    </div>
                </li>
            @endif
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4"
                     style="background-color: {{ $category->color ?? '#6366f1' }}20;">
                    @if($category->icon)
                        <svg class="w-6 h-6" style="color: {{ $category->color ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-display font-bold text-gray-900">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="mt-2 text-gray-600">{{ $category->description }}</p>
                    @endif
                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ $articles->total() }} Artikel</span>
                        <span>•</span>
                        <span>{{ $subcategories->count() }} Unterkategorien</span>
                        @if($articles->count() > 0)
                            <span>•</span>
                            <span>{{ $articles->sum('views_count') }} Aufrufe gesamt</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @can('create', App\Models\Article::class)
                    <a href="{{ route('wiki.articles.create', ['category' => $category->id]) }}" class="btn-ki-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Artikel erstellen
                    </a>
                @endcan
                @can('update', $category)
                    <a href="{{ route('wiki.categories.edit', $category->slug) }}" class="btn-ki-outline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Kategorie bearbeiten
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Subcategories -->
    @if($subcategories->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Unterkategorien</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($subcategories as $subcategory)
                    <a href="{{ route('wiki.categories.show', $subcategory->slug) }}"
                       class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3"
                                 style="background-color: {{ $subcategory->color ?? '#6366f1' }}20;">
                                <svg class="w-4 h-4" style="color: {{ $subcategory->color ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $subcategory->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $subcategory->articles_count }} Artikel</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Related Categories -->
    @if($relatedCategories->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Verwandte Kategorien</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($relatedCategories as $related)
                    <a href="{{ route('wiki.categories.show', $related->slug) }}"
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-indigo-100 hover:text-indigo-800">
                        {{ $related->name }}
                        <span class="ml-1 text-gray-500">({{ $related->articles_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters and Sort -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Sortierung:</label>
                <select name="sort" class="form-input-sm" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Neueste</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Älteste</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Beliebteste</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="In Kategorie suchen..."
                       class="form-input-sm">
                <button type="submit" class="btn-ki-outline-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Articles -->
    <div class="space-y-6">
        @forelse($articles as $article)
            <article class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 hover:scale-102 cursor-pointer" onclick="window.location.href='{{ route('wiki.articles.show', $article->slug) }}'">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="badge badge-{{ $article->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($article->status) }}
                            </span>
                            @if($article->is_featured)
                                <span class="badge badge-primary">Featured</span>
                            @endif
                            <span class="text-sm text-gray-500">
                                {{ $article->published_at ? $article->published_at->format('d.m.Y') : $article->created_at->format('d.m.Y') }}
                            </span>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900 mb-2 hover:text-indigo-600 transition-colors duration-300">
                            {{ $article->title }}
                        </h2>

                        @if($article->excerpt)
                            <p class="text-gray-600 mb-4">{{ $article->excerpt }}</p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center space-x-3">
                                <span>von <a href="{{ route('wiki.users.show', $article->user->id) }}" class="text-gray-600 hover:text-primary-600 transition-colors duration-300" onclick="event.stopPropagation();">{{ $article->user->name ?? 'Unbekannt' }}</a></span>
                                <span>{{ $article->reading_time ?? 5 }} min</span>
                                <span>{{ $article->views_count ?? 0 }} Aufrufe</span>
                                <span title="Kommentare">
                                    <i class="fas fa-comment text-gray-400 mr-1"></i>{{ $article->comments_count ?? 0 }}
                                </span>
                                <span title="Likes">
                                    <i class="fas fa-heart text-gray-400 mr-1"></i>{{ $article->likes_count ?? 0 }}
                                </span>
                            </div>

                            @if($article->tags && $article->tags->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($article->tags->take(3) as $tag)
                                        <a href="{{ route('wiki.tags.show', $tag->slug) }}"
                                           class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 hover:bg-indigo-100 hover:text-indigo-800"
                                           onclick="event.stopPropagation();">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                    @if($article->tags->count() > 3)
                                        <span class="text-xs text-gray-400">+{{ $article->tags->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="ml-6 flex items-center space-x-2">
                        @can('update', $article)
                            <a href="{{ route('wiki.articles.edit', $article->slug) }}" class="text-gray-400 hover:text-gray-600" onclick="event.stopPropagation();">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        @endcan
                    </div>
                </div>
            </article>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Noch keine Artikel</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('search'))
                        Keine Artikel gefunden, die "{{ request('search') }}" entsprechen.
                    @else
                        Diese Kategorie enthält noch keine Artikel.
                    @endif
                </p>
                @can('create', App\Models\Article::class)
                    <div class="mt-6">
                        <a href="{{ route('wiki.articles.create', ['category' => $category->id]) }}" class="btn-ki-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ersten Artikel erstellen
                        </a>
                    </div>
                @endcan
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="mt-8">
            {{ $articles->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.form-input-sm {
    @apply px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm;
}
</style>
@endpush
@endsection
