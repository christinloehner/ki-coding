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
        @can('create', App\Models\Article::class)
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
        @endcan

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

    <!-- Admin/Moderator Notifications -->
    @canany(['delete all articles', 'moderate'])
        @php
            $deletionRequests = \App\Models\Article::whereNotNull('deletion_requested_at')->count();
            $reportedArticles = \App\Models\ArticleReport::where('status', 'pending')->count();
        @endphp
        
        <!-- Deletion Requests Notification -->
        @can('delete all articles')
            @if($deletionRequests > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
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

        <!-- Reported Articles Notification -->
        @can('moderate')
            @if($reportedArticles > 0)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-red-800">
                            {{ $reportedArticles }} Artikel{{ $reportedArticles > 1 ? '' : '' }} gemeldet
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Es {{ $reportedArticles > 1 ? 'wurden' : 'wurde' }} {{ $reportedArticles }} Artikel{{ $reportedArticles > 1 ? '' : '' }} von der Community gemeldet, die deine Moderation benötigen.</p>
                        </div>
                        <div class="mt-3">
                            <div class="flex space-x-3">
                                <button onclick="scrollToElement('#moderation-section')" 
                                   class="bg-red-100 px-3 py-1.5 rounded-md text-sm font-medium text-red-800 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-red-50">
                                    Meldungen bearbeiten
                                </button>
                                <a href="{{ route('admin.articles.index') }}" 
                                   class="bg-white px-3 py-1.5 rounded-md text-sm font-medium text-red-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-red-50">
                                    Artikel Management
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $reportedArticles }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endcan
    @endcanany

    <!-- Quick Access -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Schnellzugriff</h2>
        
        <!-- Management Links (Obere Reihe) -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Management
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @can('edit all articles')
                <a href="{{ route('admin.articles.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Article Management</span>
                </a>
                @endcan

                <a href="{{ route('wiki.categories.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-200">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Category Management</span>
                </a>

                @hasrole('admin')
                <a href="{{ route('admin.roles.index') }}" class="flex flex-col items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors group">
                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-pink-200">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Role Management</span>
                </a>
                @endhasrole

                @can('view users')
                <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors group">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-red-200">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">User Management</span>
                </a>
                @endcan

                <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.show.id', Auth::user()->id) }}" class="flex flex-col items-center p-4 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors group">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-primary-200">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Mein Profil</span>
                </a>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 my-6"></div>

        <!-- Quick Actions (Untere Reihe) -->
        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Schnellaktionen
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @can('create', App\Models\Article::class)
                    <a href="{{ route('wiki.articles.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-200">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 text-center">Neuer Artikel</span>
                    </a>
                @endcan

                @can('create', App\Models\Category::class)
                <a href="{{ route('wiki.categories.create') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors group">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-200">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Neue Kategorie</span>
                </a>
                @endcan

                @hasrole('admin')
                <a href="{{ route('admin.roles.create') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors group">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-yellow-200">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Neue Rolle</span>
                </a>
                @endhasrole

                <a href="{{ route('profile.edit-profile') }}" class="flex flex-col items-center p-4 bg-secondary-50 rounded-lg hover:bg-secondary-100 transition-colors group">
                    <div class="w-10 h-10 bg-secondary-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-secondary-200">
                        <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Profil bearbeiten</span>
                </a>

                <a href="{{ route('wiki.search') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors group">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-200">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 text-center">Suchen</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- My Articles -->
        @can('create', App\Models\Article::class)
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
        @endcan

        <!-- Bookmarked Articles -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    Gemerkte Artikel
                </h2>
                <a href="{{ route('dashboard.bookmarks') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                    Alle anzeigen
                </a>
            </div>

            @if($bookmarkedArticles && $bookmarkedArticles->count() > 0)
                <div class="space-y-3">
                    @foreach($bookmarkedArticles as $article)
                        <div class="flex items-center justify-between p-3 bg-primary-50 rounded-lg group hover:bg-primary-100 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-primary-600">
                                        {{ $article->title }}
                                    </a>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    von {{ $article->user->name ?? 'Unbekannt' }} • 
                                    Gemerkt am {{ $article->bookmarked_at ? \Carbon\Carbon::parse($article->bookmarked_at)->format('d.m.Y') : 'N/A' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <span class="text-xs text-gray-500">{{ $article->views_count ?? 0 }} views</span>
                                <button onclick="removeBookmark('{{ $article->slug }}')" 
                                        class="text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity" 
                                        title="Lesezeichen entfernen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Keine gemerkten Artikel</h3>
                    <p class="mt-1 text-sm text-gray-500">Merke Dir interessante Artikel mit dem Lesezeichen-Button.</p>
                    <div class="mt-4">
                        <a href="{{ route('wiki.index') }}" class="btn-ki-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Artikel entdecken
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

    <!-- Moderation Section -->
    @can('moderate')
        @php
            $pendingReports = \App\Models\ArticleReport::where('status', 'pending')
                ->with(['article', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
        @endphp

        @if($pendingReports->count() > 0)
        <div id="moderation-section" class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Artikel-Meldungen ({{ $pendingReports->count() }})
                </h2>
                <div class="text-sm text-gray-500">
                    Benötigen deine Überprüfung
                </div>
            </div>

            <div class="space-y-4">
                @foreach($pendingReports as $report)
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ ucfirst($report->category ?? 'Allgemein') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Gemeldet am {{ $report->created_at->format('d.m.Y H:i') }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 mb-1">
                                    <a href="{{ route('wiki.articles.show', $report->article->slug) }}" 
                                       class="hover:text-red-600" target="_blank">
                                        {{ $report->article->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Grund:</strong> {{ $report->reason }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Gemeldet von: {{ $report->user->name }}
                                </p>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <form method="POST" action="{{ route('wiki.moderation.reports.resolve', $report->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded text-xs font-medium"
                                            onclick="return confirm('Meldung als berechtigt markieren und Artikel bearbeiten?')">
                                        Berechtigt
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('wiki.moderation.reports.dismiss', $report->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded text-xs font-medium"
                                            onclick="return confirm('Meldung als unberechtigt markieren?')">
                                        Unberechtigt
                                    </button>
                                </form>
                                <a href="{{ route('wiki.articles.edit', $report->article->slug) }}" 
                                   class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded text-xs font-medium">
                                    Artikel bearbeiten
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    @endcan
</div>

<script>
function scrollToElement(elementId) {
    const element = document.querySelector(elementId);
    if (element) {
        element.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

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
