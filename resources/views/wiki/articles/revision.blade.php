@extends('layouts.app')

@section('title', 'Version ' . $revision->version_number . ': ' . $revision->title . ' | KI-Coding.de')
@section('description', 'Version ' . $revision->version_number . ' des Artikels: ' . $revision->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg p-6 mb-6 border-l-4 border-primary-500">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $revision->title }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $revision->isLatest() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        Version {{ $revision->version_number }}
                        @if($revision->isLatest())
                            (Aktuelle Version)
                        @endif
                    </span>
                </div>
                <p class="text-gray-600">
                    <a href="{{ route('wiki.articles.show', $article->slug) }}" class="text-primary-600 hover:text-primary-800">
                        {{ $article->title }}
                    </a>
                    @if($revision->title !== $article->title)
                        <span class="text-orange-600">(Titel wurde geändert)</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('wiki.articles.revisions', $article->slug) }}" class="btn-ki-outline-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Alle Versionen
                </a>
                <a href="{{ route('wiki.articles.show', $article->slug) }}" class="btn-ki-outline-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Zurück zum Artikel
                </a>
            </div>
        </div>
    </div>

    <!-- Revision Info -->
    <div class="bg-white rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ $revision->user->name }}</div>
                    <div class="text-sm text-gray-500">Autor*in</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ $revision->created_at->format('d.m.Y H:i') }}</div>
                    <div class="text-sm text-gray-500">Erstellt am</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900 capitalize">
                        @if($revision->revision_type === 'create')
                            Erstellung
                        @elseif($revision->revision_type === 'restore')
                            Wiederherstellung
                        @else
                            Bearbeitung
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">Typ</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ number_format(str_word_count(strip_tags($revision->content))) }}</div>
                    <div class="text-sm text-gray-500">Wörter</div>
                </div>
            </div>
        </div>

        @if($revision->change_summary)
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-medium text-blue-900 mb-2">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Änderungshinweis
                </h4>
                <p class="text-blue-800">{{ $revision->change_summary }}</p>
            </div>
        @endif
    </div>

    <!-- Comparison Notice -->
    @unless($revision->isLatest())
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-orange-900">Du siehst eine ältere Version</h4>
                    <p class="text-orange-800 text-sm mt-1">
                        Dies ist nicht die aktuelle Version des Artikels.
                        <a href="{{ route('wiki.articles.show', $article->slug) }}" class="underline hover:no-underline">
                            Zur aktuellen Version wechseln
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @endunless

    <!-- Navigation between revisions -->
    @if($revision->previousRevision() || $revision->nextRevision())
        <div class="bg-white rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    @if($revision->previousRevision())
                        <a href="{{ route('wiki.articles.revisions.show', [$article->slug, $revision->previousRevision()->id]) }}" 
                           class="btn-ki-outline-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Version {{ $revision->previousRevision()->version_number }}
                        </a>
                    @endif
                </div>
                
                <div class="text-sm text-gray-500">
                    Version {{ $revision->version_number }} von {{ $article->revisions()->count() }}
                </div>
                
                <div>
                    @if($revision->nextRevision())
                        <a href="{{ route('wiki.articles.revisions.show', [$article->slug, $revision->nextRevision()->id]) }}" 
                           class="btn-ki-outline-sm">
                            Version {{ $revision->nextRevision()->version_number }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Article Content -->
    <div class="bg-white rounded-lg p-6 mb-6">
        <div class="wiki-content">
            @if($revision->rendered_content)
                {!! $revision->rendered_content !!}
            @else
                <div class="prose max-w-none">
                    {!! nl2br(e($revision->content)) !!}
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="text-sm text-gray-500">
                Diese Version wurde am {{ $revision->created_at->format('d.m.Y um H:i') }} Uhr von {{ $revision->user->name }} erstellt.
            </div>
            
            <div class="flex items-center gap-2">
                @can('update', $article)
                    @unless($revision->isLatest())
                        <form action="{{ route('wiki.articles.revisions.restore', [$article->slug, $revision->id]) }}" 
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Bist du sicher, dass du diese Version wiederherstellen möchtest? Dies erstellt eine neue Version basierend auf dieser.')">
                            @csrf
                            <button type="submit" class="btn-ki-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Diese Version wiederherstellen
                            </button>
                        </form>
                    @else
                        <a href="{{ route('wiki.articles.edit', $article->slug) }}" class="btn-ki-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Bearbeiten
                        </a>
                    @endunless
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection