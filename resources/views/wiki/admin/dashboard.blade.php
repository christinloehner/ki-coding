@extends('layouts.app')

@section('title', 'Admin Dashboard - KI-Coding Wiki')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900">
            Admin Dashboard
        </h1>
        <p class="mt-2 text-gray-600">
            Verwaltung der KI-Coding Knowledge Base
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Articles -->
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
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalArticles ?? 0 }}</p>
                    <p class="text-sm text-green-600">{{ $publishedArticles ?? 0 }} veröffentlicht</p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Benutzer</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                    <p class="text-sm text-green-600">{{ $activeUsers ?? 0 }} aktiv</p>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
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
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCategories ?? 0 }}</p>
                    <p class="text-sm text-green-600">{{ $activeCategories ?? 0 }} aktiv</p>
                </div>
            </div>
        </div>

        <!-- Total Comments -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kommentare</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalComments ?? 0 }}</p>
                    <p class="text-sm text-yellow-600">{{ $pendingComments ?? 0 }} ausstehend</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Article Reports -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Artikel-Meldungen</h2>
                <div class="flex items-center space-x-2">
                    <span class="badge badge-red">{{ $pendingArticleReports ?? 0 }}</span>
                    <span class="text-sm text-gray-500">neu</span>
                </div>
            </div>
            <div class="space-y-4">
                @forelse($recentArticleReports ?? [] as $report)
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('wiki.articles.show', $report->article->slug) }}" class="hover:text-indigo-600">
                                        {{ $report->article->title }}
                                    </a>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Gemeldet von {{ $report->user->name }} • {{ $report->created_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <span class="badge badge-red">{{ ucfirst($report->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            <strong>Grund:</strong> {{ $report->reason }}
                        </p>
                        <div class="flex items-center space-x-2">
                            <button class="btn-ki-outline-sm text-green-600 hover:text-green-800" onclick="resolveReport({{ $report->id }}, 'reviewed')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bearbeitet
                            </button>
                            <button class="btn-ki-outline-sm text-gray-600 hover:text-gray-800" onclick="resolveReport({{ $report->id }}, 'dismissed')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Ignorieren
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Artikel-Meldungen</h3>
                        <p class="mt-1 text-sm text-gray-500">Alle Meldungen wurden bearbeitet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Comment Reports -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Kommentar-Meldungen</h2>
                <div class="flex items-center space-x-2">
                    <span class="badge badge-red">{{ $pendingCommentReports ?? 0 }}</span>
                    <span class="text-sm text-gray-500">neu</span>
                </div>
            </div>
            <div class="space-y-4">
                @forelse($recentCommentReports ?? [] as $report)
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    Kommentar in: <a href="{{ route('wiki.articles.show', $report->comment->article->slug) }}" class="hover:text-indigo-600">
                                        {{ $report->comment->article->title }}
                                    </a>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Gemeldet von {{ $report->user->name }} • {{ $report->created_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <span class="badge badge-red">{{ ucfirst($report->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Kommentar:</strong> {{ Str::limit($report->comment->content, 100) }}
                        </p>
                        <p class="text-sm text-gray-600 mb-3">
                            <strong>Grund:</strong> {{ $report->reason }}
                        </p>
                        <div class="flex items-center space-x-2">
                            <button class="btn-ki-outline-sm text-green-600 hover:text-green-800" onclick="resolveCommentReport({{ $report->id }}, 'reviewed')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bearbeitet
                            </button>
                            <button class="btn-ki-outline-sm text-gray-600 hover:text-gray-800" onclick="resolveCommentReport({{ $report->id }}, 'dismissed')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Ignorieren
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Kommentar-Meldungen</h3>
                        <p class="mt-1 text-sm text-gray-500">Alle Meldungen wurden bearbeitet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Articles -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Neueste Artikel</h2>
                <a href="{{ route('wiki.articles.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    Alle anzeigen
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentArticles ?? [] as $article)
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-indigo-600">
                                    {{ $article->title }}
                                </a>
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $article->user->name }} • {{ $article->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="badge badge-{{ $article->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($article->status) }}
                            </span>
                            @can('update', $article)
                                <a href="{{ route('wiki.articles.edit', $article->slug) }}" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Keine Artikel vorhanden</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Comments -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Neueste Kommentare</h2>
                <a href="{{ route('wiki.comments.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    Alle anzeigen
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentComments ?? [] as $comment)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">
                                {{ Str::limit($comment->content, 100) }}
                            </p>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs text-gray-500">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-300">•</span>
                                <a href="{{ route('wiki.articles.show', $comment->article->slug) }}" class="text-xs text-indigo-600 hover:text-indigo-800">
                                    {{ $comment->article->title }}
                                </a>
                                <span class="text-xs text-gray-300">•</span>
                                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="badge badge-{{ $comment->status === 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst($comment->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Keine Kommentare vorhanden</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Moderation Queue -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Moderationsqueue</h2>
            <div class="flex items-center space-x-2">
                <span class="badge badge-warning">{{ $pendingReviews ?? 0 }}</span>
                <span class="text-sm text-gray-500">ausstehend</span>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($pendingItems ?? [] as $item)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <span class="badge badge-{{ $item->type === 'article' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($item->type) }}
                            </span>
                            <h3 class="font-medium text-gray-900">{{ $item->title }}</h3>
                        </div>
                        <span class="text-sm text-gray-500">{{ $item->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">
                        {{ Str::limit($item->content ?? $item->description, 200) }}
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">von {{ $item->user->name }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="btn-ki-outline-sm text-green-600 hover:text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Genehmigen
                            </button>
                            <button class="btn-ki-outline-sm text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Ablehnen
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Keine ausstehenden Überprüfungen</h3>
                    <p class="mt-1 text-sm text-gray-500">Alle Inhalte sind genehmigt oder bearbeitet worden.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Schnellaktionen</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @can('create articles')
                <a href="{{ route('wiki.articles.create') }}" class="btn-ki-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Neuen Artikel erstellen
                </a>
            @endcan
            @can('create categories')
                <a href="{{ route('wiki.categories.create') }}" class="btn-ki-outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Neue Kategorie
                </a>
            @endcan
            @can('create tags')
                <a href="{{ route('wiki.tags.create') }}" class="btn-ki-outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Neuen Tag
                </a>
            @endcan
            @can('view users')
                <a href="{{ route('wiki.users.index') }}" class="btn-ki-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                    Benutzer verwalten
                </a>
            @endcan
        </div>
    </div>
</div>

<script>
function resolveReport(reportId, status) {
    if (!confirm('Möchtest du diese Meldung als ' + (status === 'reviewed' ? 'bearbeitet' : 'ignoriert') + ' markieren?')) {
        return;
    }
    
    fetch(`{{ route('wiki.admin.reports.articles.resolve', '') }}/${reportId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Fehler: ' + (data.message || 'Unbekannter Fehler'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Bearbeiten der Meldung.');
    });
}

function resolveCommentReport(reportId, status) {
    if (!confirm('Möchtest du diese Kommentar-Meldung als ' + (status === 'reviewed' ? 'bearbeitet' : 'ignoriert') + ' markieren?')) {
        return;
    }
    
    fetch(`{{ route('wiki.admin.reports.comments.resolve', '') }}/${reportId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Fehler: ' + (data.message || 'Unbekannter Fehler'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Bearbeiten der Meldung.');
    });
}
</script>

@endsection
