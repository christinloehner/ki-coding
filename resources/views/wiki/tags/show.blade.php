@extends('layouts.app')

@section('title', $tag->name . ' - KI-Coding Wiki | Tutorials & Artikel zu ' . $tag->name)
@section('description', $tag->description ?? 'Entdecke ' . $articles->total() . ' kostenlose Tutorials und Artikel zum Thema ' . $tag->name . ' in unserer KI-Programmierung Knowledge Base.')
@section('keywords', $tag->name . ', KI-Programmierung, Tutorial, ' . ($tag->description ? 'AI-Coding, Entwicklung, ' : '') . 'Kostenlos lernen, KI-Tools')
@section('robots', 'index, follow')

@section('og_title', $tag->name . ' Tutorials - KI-Coding Wiki')
@section('og_description', 'Lerne ' . $tag->name . ' mit ' . $articles->total() . ' kostenlosen Tutorials und Artikeln. Schritt-für-Schritt Anleitungen für KI-gestütztes Programmieren.')
@section('og_type', 'website')
@section('og_image', asset('images/tag-social.jpg'))

@section('twitter_title', $tag->name . ' - Kostenlose Tutorials')
@section('twitter_description', $articles->total() . ' Artikel zu ' . $tag->name . '. Lerne KI-Programmierung kostenlos.')


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
                    <a href="{{ route('wiki.tags.index') }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2">Tags</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $tag->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Tag Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-display font-bold text-gray-900">{{ $tag->name }}</h1>
                    @if($tag->description)
                        <p class="mt-2 text-gray-600">{{ $tag->description }}</p>
                    @endif
                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ $articles->total() }} Artikel</span>
                        <span>•</span>
                        <span>{{ $articles->sum('views_count') }} Aufrufe gesamt</span>
                        <span>•</span>
                        <span>Erstellt {{ $tag->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @can('update', $tag)
                    <a href="{{ route('wiki.tags.edit', $tag->slug) }}" class="btn-ki-outline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Tag bearbeiten
                    </a>
                @endcan
                <div class="flex items-center space-x-2">
                    <button onclick="shareTag()" class="btn-ki-outline-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Tags -->
    @if($relatedTags && $relatedTags->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Verwandte Tags</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($relatedTags as $relatedTag)
                    <a href="{{ route('wiki.tags.show', $relatedTag->slug) }}"
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-indigo-100 hover:text-indigo-800 transition-colors">
                        {{ $relatedTag->name }}
                        <span class="ml-1 text-gray-500">({{ $relatedTag->articles_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Artikel</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $articles->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Aufrufe</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $articles->sum('views_count') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Autoren</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $uniqueAuthors ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kategorien</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $uniqueCategories ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Sort -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Sortierung:</label>
                <select name="sort" class="form-input-sm" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Neueste</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Älteste</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Beliebteste</option>
                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Titel</option>
                </select>
            </div>
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Kategorie:</label>
                <select name="category" class="form-input-sm" onchange="this.form.submit()">
                    <option value="">Alle Kategorien</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="In Artikeln suchen..."
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
                            @if($article->category)
                                <span class="badge badge-secondary">{{ $article->category->name ?? 'Unkategorisiert' }}</span>
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

                            @if($article->tags && $article->tags->count() > 1)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($article->tags->where('id', '!=', $tag->id)->take(2) as $otherTag)
                                        <a href="{{ route('wiki.tags.show', $otherTag->slug) }}"
                                           class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 hover:bg-indigo-100 hover:text-indigo-800"
                                           onclick="event.stopPropagation();">
                                            {{ $otherTag->name }}
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Artikel gefunden</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('search'))
                        Keine Artikel mit dem Tag "{{ $tag->name }}" gefunden, die "{{ request('search') }}" entsprechen.
                    @else
                        Dieser Tag hat noch keine zugeordneten Artikel.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('wiki.articles.index') }}" class="btn-ki-primary">
                        Alle Artikel durchsuchen
                    </a>
                </div>
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

@push('scripts')
<script>
function shareTag() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $tag->name }} - KI-Coding Wiki',
            text: 'Entdecke Artikel zum Thema {{ $tag->name }}',
            url: window.location.href
        });
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link in die Zwischenablage kopiert!');
        });
    }
}
</script>
@endpush
@endsection
