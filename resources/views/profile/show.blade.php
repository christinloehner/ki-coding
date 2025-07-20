@extends('layouts.app')

@section('title', $user->name . ' - Profil')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 sticky top-8">
                <!-- Avatar and Basic Info -->
                <div class="px-6 py-6 text-center border-b border-gray-200">
                    <img class="mx-auto h-32 w-32 rounded-full object-cover" 
                         src="{{ $user->avatar_url }}" 
                         alt="{{ e($user->name) }}">
                    
                    <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ e($user->name) }}</h1>
                    
                    @if($user->shouldShowField('show_job_title') && $user->job_title)
                        <p class="text-gray-600">{{ e($user->job_title) }}</p>
                    @endif
                    
                    @if($user->shouldShowField('show_company') && $user->company)
                        <p class="text-sm text-gray-500">@ {{ e($user->company) }}</p>
                    @endif

                    @if($user->shouldShowField('show_reputation'))
                        <div class="mt-4 flex items-center justify-center space-x-4">
                            <div class="text-center">
                                <div class="text-xl font-bold text-primary-600">{{ $user->reputation_score }}</div>
                                <div class="text-xs text-gray-500">Reputation</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-green-600">{{ $user->articles_count }}</div>
                                <div class="text-xs text-gray-500">Artikel</div>
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                         @if($user->reputation_level === 'Expert') bg-purple-100 text-purple-800
                                         @elseif($user->reputation_level === 'Advanced') bg-blue-100 text-blue-800
                                         @elseif($user->reputation_level === 'Intermediate') bg-green-100 text-green-800
                                         @elseif($user->reputation_level === 'Beginner') bg-yellow-100 text-yellow-800
                                         @else bg-gray-100 text-gray-800
                                         @endif">
                                {{ $user->reputation_level }}
                            </span>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->id() === $user->id)
                        <div class="mt-4">
                            <a href="{{ route('profile.edit-profile') }}" 
                               class="inline-flex items-center px-4 py-2 btn-ki-primary text-sm">
                                <i class="fas fa-edit mr-2"></i>Profil bearbeiten
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Contact Information -->
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kontakt</h3>
                    <div class="space-y-3">
                        @if($user->shouldShowField('show_location') && $user->location)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt w-5 h-5 mr-3 text-gray-400"></i>
                                {{ e($user->location) }}
                            </div>
                        @endif

                        @if($user->shouldShowField('show_website') && $user->website)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-globe w-5 h-5 mr-3 text-gray-400"></i>
                                <a href="{{ e($user->website) }}" 
                                   target="_blank" 
                                   class="text-primary-600 hover:text-primary-800 truncate">
                                    {{ e(parse_url($user->website, PHP_URL_HOST)) }}
                                </a>
                            </div>
                        @endif

                        @if($user->shouldShowField('show_email') && $user->email)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope w-5 h-5 mr-3 text-gray-400"></i>
                                <a href="mailto:{{ e($user->email) }}" class="text-primary-600 hover:text-primary-800">
                                    {{ e($user->email) }}
                                </a>
                            </div>
                        @endif

                        @if($user->shouldShowField('show_birthday') && $user->birthday)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-birthday-cake w-5 h-5 mr-3 text-gray-400"></i>
                                {{ $user->birthday->format('d.m.Y') }}
                                @if($user->age)
                                    ({{ $user->age }} Jahre)
                                @endif
                            </div>
                        @endif

                        @if($user->shouldShowField('show_joined_date'))
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt w-5 h-5 mr-3 text-gray-400"></i>
                                Mitglied seit {{ $user->created_at->format('M Y') }}
                            </div>
                        @endif

                        @if($user->shouldShowField('show_last_activity') && $user->last_activity_at)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock w-5 h-5 mr-3 text-gray-400"></i>
                                Zuletzt aktiv {{ $user->last_activity_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>

                    <!-- Social Media Links -->
                    @if($user->shouldShowField('show_social_media'))
                        @php
                            $socialLinks = $user->getSocialMediaLinks();
                        @endphp
                        
                        @if(count($socialLinks) > 0)
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Social Media</h4>
                                <div class="flex space-x-3">
                                    @foreach($socialLinks as $platform => $link)
                                        <a href="{{ $link['url'] }}" 
                                           target="_blank" 
                                           class="text-gray-400 hover:text-gray-600 transition-colors"
                                           title="{{ $link['label'] }}">
                                            <i class="{{ $link['icon'] }} text-lg"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Biography -->
            @if($user->shouldShowField('show_bio') && $user->bio)
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Über {{ $user->name }}</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="prose prose max-w-none">
                            {!! Str::markdown($user->bio) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Articles -->
            @if($user->shouldShowField('show_articles'))
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Veröffentlichte Artikel ({{ $user->publishedArticles()->count() }})
                            </h2>
                            @if($user->publishedArticles()->count() > 10)
                                <a href="{{ route('wiki.articles.index', ['author' => $user->username ?: $user->id]) }}" 
                                   class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    Alle anzeigen →
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        @if($user->articles->count() > 0)
                            <div class="space-y-6">
                                @foreach($user->articles as $article)
                                    <article class="border-b border-gray-100 last:border-b-0 pb-6 last:pb-0">
                                        <div class="flex items-start space-x-4">
                                            @if($article->featured_image)
                                                <div class="flex-shrink-0 w-20 h-20">
                                                    <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                                         alt="{{ e($article->title) }}"
                                                         class="w-full h-full object-cover rounded-lg">
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                    <a href="{{ route('wiki.articles.show', $article->slug) }}" 
                                                       class="hover:text-primary-600">
                                                        {{ e($article->title) }}
                                                    </a>
                                                </h3>
                                                
                                                @if($article->excerpt)
                                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                                        {{ e($article->excerpt) }}
                                                    </p>
                                                @endif

                                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $article->created_at->format('d.m.Y') }}
                                                    </span>
                                                    
                                                    @if($article->category)
                                                        <span class="flex items-center">
                                                            <i class="fas fa-folder mr-1"></i>
                                                            {{ e($article->category->name) }}
                                                        </span>
                                                    @endif
                                                    
                                                    <span class="flex items-center">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        {{ number_format($article->views_count ?? 0) }} Aufrufe
                                                    </span>

                                                    @if($article->featured)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-star mr-1"></i>Featured
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($article->tags && $article->tags->count() > 0)
                                                    <div class="mt-3 flex flex-wrap gap-1">
                                                        @foreach($article->tags->take(5) as $tag)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                #{{ $tag->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900">Noch keine Artikel</h3>
                                <p class="text-gray-500">{{ $user->name }} hat noch keine Artikel veröffentlicht.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- User Roles (for admins/moderators) -->
            @if(auth()->check() && (auth()->user()->can('view users') || auth()->id() === $user->id))
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Rollen & Berechtigungen</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Aktuelle Rollen</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                     @if($role->name === 'admin') bg-red-100 text-red-800
                                                     @elseif($role->name === 'moderator') bg-orange-100 text-orange-800
                                                     @elseif($role->name === 'editor') bg-blue-100 text-blue-800
                                                     @elseif($role->name === 'contributor') bg-green-100 text-green-800
                                                     @else bg-gray-100 text-gray-800
                                                     @endif">
                                            <i class="fas fa-users mr-2"></i>{{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            @if(auth()->user()->can('manage users') && auth()->id() !== $user->id)
                                <div class="pt-4 border-t">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="inline-flex items-center px-4 py-2 btn-ki-primary text-sm">
                                        <i class="fas fa-cog mr-2"></i>Benutzer*innen verwalten
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Prose styles for markdown content */
.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    font-weight: 600;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    line-height: 1.25;
}

.prose h1 { font-size: 1.875rem; }
.prose h2 { font-size: 1.5rem; }
.prose h3 { font-size: 1.25rem; }
.prose h4 { font-size: 1.125rem; }

.prose p {
    margin-bottom: 1rem;
    line-height: 1.75;
}

.prose a {
    color: var(--color-primary-600);
    text-decoration: underline;
}

.prose a:hover {
    color: var(--color-primary-800);
}

.prose ul, .prose ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.25rem;
}

.prose blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #6b7280;
}

.prose code {
    background-color: #f3f4f6;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}

.prose pre {
    background-color: #1f2937;
    color: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1rem 0;
}

.prose pre code {
    background-color: transparent;
    padding: 0;
    color: inherit;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
</style>
@endpush