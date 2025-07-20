@extends('layouts.app')

@section('title', 'Dashboard - KI-Coding Wiki')
@section('description', 'Dein persönliches Wiki-Dashboard mit Übersicht über Artikel, Statistiken und Aktivitäten')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900">
            Willkommen zurück, {{ Auth::user()->name }}!
        </h1>
        <p class="mt-2 text-gray-600">
            Hier ist deine persönliche Wiki-Übersicht und deine neuesten Aktivitäten.
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Meine Artikel</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $userStats['articles'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-secondary-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kommentare</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $userStats['comments'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Aufrufe</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $userStats['views'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Reputation</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $userStats['reputation'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Notifications -->
    @can('delete all articles')
        @php
            $deletionRequests = \App\Models\Article::whereNotNull('deletion_requested_at')->count();
        @endphp
        
        @if($deletionRequests > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">
                        {{ $deletionRequests }} Löschungsantrag{{ $deletionRequests > 1 ? 'e' : '' }} ausstehend
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Es {{ $deletionRequests > 1 ? 'liegen' : 'liegt' }} {{ $deletionRequests }} Antrag{{ $deletionRequests > 1 ? 'e' : '' }} zur Artikellöschung vor, die deine Überprüfung benötigen.</p>
                    </div>
                    <div class="mt-3">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.articles.deletion-requests') }}" 
                               class="bg-yellow-100 px-3 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-yellow-50">
                                Anträge bearbeiten
                            </a>
                            <a href="{{ route('admin.articles.index') }}" 
                               class="bg-white px-3 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-yellow-50">
                                Artikel Management
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ $deletionRequests }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endcan

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Schnellzugriff</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('wiki.articles.create') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Neuer Artikel</span>
            </a>

            <a href="{{ route('wiki.articles.index') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Alle Artikel</span>
            </a>

            <a href="{{ route('wiki.categories.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Kategorien</span>
            </a>

            <a href="{{ route('wiki.search') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Suchen</span>
            </a>

            @can('create', App\Models\Category::class)
            <a href="{{ route('wiki.categories.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Neue Kategorie</span>
            </a>
            @endcan

            <!-- Admin Quick Actions -->
            @can('view users')
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">User Management</span>
            </a>
            @endcan
            
            @can('edit all articles')
            <a href="{{ route('admin.articles.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Article Management</span>
            </a>
            @endcan
            
            @hasrole('admin')
            <a href="{{ route('admin.roles.index') }}" class="flex flex-col items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors">
                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-users text-pink-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Role Management</span>
            </a>
            @endhasrole

            <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.show.id', Auth::user()->id) }}" class="flex flex-col items-center p-4 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Mein Profil</span>
            </a>
            
            <a href="{{ route('profile.edit-profile') }}" class="flex flex-col items-center p-4 bg-secondary-50 rounded-lg hover:bg-secondary-100 transition-colors">
                <div class="w-8 h-8 bg-secondary-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Profil bearbeiten</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- My Articles -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Meine Artikel</h2>
                <a href="{{ route('wiki.articles.index', ['author' => Auth::id()]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                    Alle anzeigen
                </a>
            </div>

            @if($myArticles && $myArticles->count() > 0)
                <div class="space-y-3">
                    @foreach($myArticles as $article)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-indigo-600">
                                        {{ $article->title }}
                                    </a>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $article->created_at->format('d.m.Y') }} •
                                    <span class="badge badge-{{ $article->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <span class="text-xs text-gray-500">{{ $article->views_count ?? 0 }} views</span>
                                <a href="{{ route('wiki.articles.edit', $article->slug) }}" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Noch keine Artikel</h3>
                    <p class="mt-1 text-sm text-gray-500">Erstelle deinen ersten Artikel für die Community.</p>
                    <div class="mt-4">
                        <a href="{{ route('wiki.articles.create') }}" class="btn-ki-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ersten Artikel erstellen
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Neueste Aktivitäten</h2>

            @if($recentActivity && $recentActivity->count() > 0)
                <div class="space-y-3">
                    @foreach($recentActivity as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    @switch($activity->type)
                                        @case('article_created')
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            @break
                                        @case('comment_posted')
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                    @endswitch
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    {{ $activity->description }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Aktivitäten</h3>
                    <p class="mt-1 text-sm text-gray-500">Starte mit der Erstellung deines ersten Artikels.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Community Stats -->
    <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Community-Statistiken</h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ $communityStats['total_articles'] ?? 0 }}</div>
                <div class="text-sm text-gray-500">Artikel</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $communityStats['total_users'] ?? 0 }}</div>
                <div class="text-sm text-gray-500">Benutzer*innen</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $communityStats['total_categories'] ?? 0 }}</div>
                <div class="text-sm text-gray-500">Kategorien</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $communityStats['total_comments'] ?? 0 }}</div>
                <div class="text-sm text-gray-500">Kommentare</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $communityStats['total_views'] ?? 0 }}</div>
                <div class="text-sm text-gray-500">Aufrufe</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ $communityStats['total_tags'] ?? 0 }}</div>
                <div class="text-sm text-gray-500">Tags</div>
            </div>
        </div>
    </div>
</div>
@endsection
