@extends('layouts.app')

@section('title', 'Kategorien - KI-Coding Wiki')
@section('description', 'Entdecke alle Kategorien und Themenbereiche der KI-Coding Knowledge Base')

@section('content')
<!-- Hero Header -->
<x-hero-header 
    title="Wiki-Kategorien" 
    subtitle="Entdecke alle Themenbereiche und organisiere dein Wissen strukturiert in unserer umfassenden Knowledge Base."
    gradient="gradient-dawn">
    <x-slot name="actions">
        @can('create', App\Models\Category::class)
            <a href="{{ route('wiki.categories.create') }}" class="btn-ki-primary-inverse">
                <i class="fas fa-plus mr-2"></i>Neue Kategorie erstellen
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kategorien</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $categories->count() }}</p>
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
                    <p class="text-sm text-gray-500">Artikel gesamt</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalArticles }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-accent-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Hauptkategorien</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $rootCategories->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Aktivste Kategorie</p>
                    @if($rootCategories->count() > 0)
                        @php $mostActive = $rootCategories->sortByDesc('articles_count')->first(); @endphp
                        <p class="text-lg font-semibold text-gray-900">{{ $mostActive->name }}</p>
                        <p class="text-sm text-gray-500">{{ $mostActive->articles_count }} Artikel</p>
                    @else
                        <p class="text-lg font-semibold text-gray-900">-</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($rootCategories as $category)
            <div class="bg-white rounded-lg shadow-primary border border-gray-200 hover:shadow-lg transition-all duration-300">
                <!-- Category Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3"
                                 style="background-color: {{ $category->color ?? '#6366f1' }}20;">
                                @if($category->icon)
                                    <svg class="w-5 h-5" style="color: {{ $category->color ?? '#6366f1' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <a href="{{ route('wiki.categories.show', $category->slug) }}" class="hover:text-primary-600">
                                        {{ $category->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">{{ $category->articles_count }} Artikel</p>
                            </div>
                        </div>
                        @can('update', $category)
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('wiki.categories.edit', $category->slug) }}" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>

                <!-- Category Content -->
                <div class="p-6">
                    @if($category->description)
                        <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                    @endif

                    <!-- Subcategories -->
                    @if($category->children && $category->children->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Unterkategorien</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($category->children as $child)
                                    <a href="{{ route('wiki.categories.show', $child->slug) }}"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-primary-100 hover:text-primary-800">
                                        {{ $child->name }}
                                        <span class="ml-1 text-gray-500">({{ $child->articles_count ?? 0 }})</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Recent Articles -->
                    @if($category->articles_count > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Neueste Artikel</h4>
                            <div class="space-y-2">
                                @foreach($category->articles()->published()->latest()->take(3)->get() as $article)
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
                </div>

                <!-- Category Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('wiki.categories.show', $category->slug) }}" class="btn-ki-outline-sm">
                            Alle Artikel anzeigen
                        </a>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $category->articles()->published()->sum('views_count') }} Aufrufe
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Noch keine Kategorien</h3>
                    <p class="mt-1 text-sm text-gray-500">Erstelle die erste Kategorie für deine Knowledge Base.</p>
                    @can('create', App\Models\Category::class)
                        <div class="mt-6">
                            <a href="{{ route('wiki.categories.create') }}" class="btn-ki-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Erste Kategorie erstellen
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        @endforelse
    </div>

    <!-- All Categories - Hierarchical Tree View -->
    @if($categories->count() > $rootCategories->count())
        <div class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-display font-bold text-gray-900">Hierarchische Kategorien-Übersicht</h2>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-sitemap mr-2"></i>Alle Kategorien mit ihrer Hierarchie
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    @php
                        // Funktion zur rekursiven Darstellung der Kategorie-Hierarchie
                        function renderCategoryTree($categories, $parentId = null, $depth = 0) {
                            $filtered = $categories->filter(function($category) use ($parentId) {
                                return $category->parent_id == $parentId;
                            });
                            
                            // Natural alphabetische Sortierung nach Name
                            $filtered = $filtered->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
                            
                            if ($filtered->isEmpty()) return '';
                            
                            $html = '<div class="space-y-2">';
                            
                            foreach ($filtered as $category) {
                                $marginClass = $depth > 0 ? 'ml-' . ($depth * 6) : '';
                                $hasChildren = $categories->filter(function($cat) use ($category) {
                                    return $cat->parent_id == $category->id;
                                })->isNotEmpty();
                                
                                $html .= '<div class="' . $marginClass . ' group">';
                                $html .= '<div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-gray-50 transition-colors">';
                                
                                // Left side - Category Info
                                $html .= '<div class="flex items-center">';
                                
                                // Indentation indicator and connector lines
                                if ($depth > 0) {
                                    $html .= '<div class="flex items-center mr-3">';
                                    for ($i = 0; $i < $depth; $i++) {
                                        if ($i == $depth - 1) {
                                            $html .= '<div class="w-4 h-4 border-l-2 border-b-2 border-gray-300 rounded-bl-md mr-2"></div>';
                                        } else {
                                            $html .= '<div class="w-4 h-4 border-l-2 border-gray-300 mr-2"></div>';
                                        }
                                    }
                                    $html .= '</div>';
                                }
                                
                                // Category icon
                                $iconColor = $category->color ?? '#6366f1';
                                $html .= '<div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: ' . $iconColor . '20;">';
                                if ($hasChildren) {
                                    $html .= '<svg class="w-4 h-4" style="color: ' . $iconColor . ';" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                    $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>';
                                    $html .= '</svg>';
                                } else {
                                    $html .= '<svg class="w-4 h-4" style="color: ' . $iconColor . ';" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                    $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>';
                                    $html .= '</svg>';
                                }
                                $html .= '</div>';
                                
                                // Category name and description
                                $html .= '<div>';
                                $html .= '<h3 class="text-sm font-medium text-gray-900 group-hover:text-primary-600">';
                                $html .= '<a href="' . route('wiki.categories.show', $category->slug) . '" class="hover:text-primary-600">';
                                if ($depth == 0) {
                                    $html .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 mr-2">Hauptkategorie</span>';
                                }
                                $html .= htmlspecialchars($category->name);
                                $html .= '</a>';
                                $html .= '</h3>';
                                if ($category->description) {
                                    $html .= '<p class="text-xs text-gray-500 mt-1">' . htmlspecialchars(Str::limit($category->description, 80)) . '</p>';
                                }
                                $html .= '</div>';
                                
                                $html .= '</div>'; // End left side
                                
                                // Right side - Stats and Actions
                                $html .= '<div class="flex items-center space-x-4">';
                                $html .= '<div class="text-right">';
                                $html .= '<span class="text-sm font-medium text-gray-700">' . $category->articles_count . '</span>';
                                $html .= '<span class="text-xs text-gray-500 block">Artikel</span>';
                                $html .= '</div>';
                                
                                if (auth()->user() && auth()->user()->can('update', $category)) {
                                    $html .= '<a href="' . route('wiki.categories.edit', $category->slug) . '" class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-gray-600 transition-opacity">';
                                    $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                                    $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>';
                                    $html .= '</svg>';
                                    $html .= '</a>';
                                }
                                $html .= '</div>'; // End right side
                                
                                $html .= '</div>'; // End flex container
                                $html .= '</div>'; // End group div
                                
                                // Recursive call for children
                                $html .= renderCategoryTree($categories, $category->id, $depth + 1);
                            }
                            
                            $html .= '</div>';
                            return $html;
                        }
                    @endphp
                    
                    {!! renderCategoryTree($categories) !!}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
