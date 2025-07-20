@extends('layouts.app')

@section('title', $article->title . ' | KI-Coding Tutorial - Lerne ' . ($article->category->name ?? 'KI-Programmierung'))
@section('description', $article->excerpt ?? substr(strip_tags($article->content ?? ''), 0, 155) . '...')
@section('keywords', implode(', ', array_merge(
    ['KI-Programmierung', 'Tutorial', $article->category->name ?? 'Programmierung'],
    $article->tags ? $article->tags->pluck('name')->toArray() : [],
    ['GitHub Copilot', 'ChatGPT', 'AI Coding', 'Prompt Engineering']
)))
@section('robots', 'index, follow, max-image-preview:large')

@section('og_title', $article->title . ' | KI-Coding Tutorial')
@section('og_description', $article->excerpt ?? substr(strip_tags($article->content ?? ''), 0, 155) . '...')
@section('og_type', 'article')
@section('og_image', asset('images/article-social.jpg'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_alt', $article->title . ' - KI-Coding Tutorial')
@section('article_author', $article->user->name ?? 'KI-Coding Team')

@section('twitter_title', $article->title)
@section('twitter_description', $article->excerpt ?? substr(strip_tags($article->content ?? ''), 0, 155) . '...')
@section('twitter_card', 'summary_large_image')
@section('twitter_image', asset('images/article-social.jpg'))
@section('twitter_image_alt', $article->title . ' Tutorial')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Article Header -->
    <div class="bg-white rounded-lg shadow-primary p-6 mb-6 border-l-4 border-primary-500">
        <div class="flex items-center mb-4">
            <span class="badge badge-secondary mr-2">{{ $article->category->name ?? 'Allgemein' }}</span>
            <span class="text-sm text-gray-500">{{ $article->published_at ? $article->published_at->format('d.m.Y') : 'Unveröffentlicht' }}</span>
        </div>

        <h1 class="text-3xl font-display font-bold text-high-contrast mb-4">
            {{ $article->title }}
        </h1>

        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
            <div class="flex items-center space-x-4">
                <span>von {{ $article->user->name ?? 'Unbekannt' }}</span>
                <span>{{ $article->reading_time ?? 5 }} min Lesezeit</span>
                <span>{{ $article->views_count ?? 0 }} Aufrufe</span>
            </div>
            <div class="flex items-center space-x-2">
                @auth
                    @php
                        $userLiked = DB::table('article_likes')->where('article_id', $article->id)->where('user_id', auth()->id())->exists();
                        $userBookmarked = DB::table('article_bookmarks')->where('article_id', $article->id)->where('user_id', auth()->id())->exists();
                        $userVote = DB::table('article_votes')->where('article_id', $article->id)->where('user_id', auth()->id())->first();
                    @endphp
                    
                    <button id="likeBtn" class="{{ $userLiked ? 'btn-ki-primary-sm' : 'btn-ki-outline-sm' }}" onclick="toggleLike('{{ $article->slug }}')">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span id="likeText">{{ $userLiked ? 'Geliked' : 'Liken' }}</span>
                    </button>
                    <button id="bookmarkBtn" class="{{ $userBookmarked ? 'btn-ki-primary-sm' : 'btn-ki-outline-sm' }}" onclick="toggleBookmark('{{ $article->slug }}')">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        <span id="bookmarkText">{{ $userBookmarked ? 'Gemerkt' : 'Merken' }}</span>
                    </button>
                    
                    <!-- Author/Admin Actions -->
                    @can('update', $article)
                        <a href="{{ route('wiki.articles.edit', $article->slug) }}" class="btn-ki-outline-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    
                    @can('delete all articles')
                        <form method="POST" action="{{ route('wiki.articles.destroy', $article->slug) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn-ki-outline-sm text-red-600 border-red-300 hover:bg-red-50"
                                    onclick="return confirm('Artikel \'{{ $article->title }}\' wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Löschen
                            </button>
                        </form>
                    @endcan
                    
                    @can('delete', $article)
                        @if(!auth()->user()->can('delete all articles'))
                            @if(!$article->deletion_requested_at)
                                <button type="button" 
                                        class="btn-ki-outline-sm text-yellow-600 border-yellow-300 hover:bg-yellow-50"
                                        onclick="showDeletionRequestModal()">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Löschung beantragen
                                </button>
                            @else
                                <span class="btn-ki-outline-sm text-orange-600 border-orange-300 bg-orange-50 cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Löschung beantragt
                                </span>
                                <form method="POST" action="{{ route('wiki.articles.cancel-deletion', $article->slug) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-ki-outline-sm text-gray-600 border-gray-300 hover:bg-gray-50"
                                            onclick="return confirm('Löschungsantrag wirklich zurückziehen?')">
                                        Antrag zurückziehen
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endcan
                @endauth
            </div>
        </div>

        @if($article->excerpt)
            <p class="text-lg text-gray-700 mb-4">{{ $article->excerpt }}</p>
        @endif

        @if($article->tags && $article->tags->count() > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                    <a href="{{ route('wiki.tags.show', $tag->slug) }}" class="badge badge-secondary hover:bg-primary-100 hover:text-primary-800">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Article Content -->
    <div class="bg-white rounded-lg shadow-primary p-6 mb-6">
        <div class="wiki-content">
            @if($article->rendered_content)
                {!! $article->rendered_content !!}
            @else
                <div class="prose max-w-none">
                    {!! nl2br(e($article->content)) !!}
                </div>
            @endif
        </div>
    </div>

    <!-- Article Actions -->
    <div class="bg-white rounded-lg shadow-primary p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">War dieser Artikel hilfreich?</span>
                @auth
                    <button id="helpfulBtn" class="{{ $userVote && $userVote->is_helpful === 1 ? 'btn-ki-primary-sm' : 'btn-ki-outline-sm' }}" onclick="voteHelpful('{{ $article->slug }}', true)">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.60L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                        <span id="helpfulText">{{ $userVote && $userVote->is_helpful === 1 ? 'Als hilfreich bewertet' : 'Hilfreich' }}</span>
                    </button>
                    <button id="notHelpfulBtn" class="{{ $userVote && $userVote->is_helpful === 0 ? 'btn-ki-primary-sm' : 'btn-ki-outline-sm' }}" onclick="voteHelpful('{{ $article->slug }}', false)">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.60L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10H5a2 2 0 00-2 2v6a2 2 0 002 2h2.5"></path>
                        </svg>
                        <span id="notHelpfulText">{{ $userVote && $userVote->is_helpful === 0 ? 'Als nicht hilfreich bewertet' : 'Nicht hilfreich' }}</span>
                    </button>
                @endauth
            </div>

            <div class="flex items-center space-x-4">
                @can('update', $article)
                    <a href="{{ route('wiki.articles.edit', $article->slug) }}" class="btn-ki-outline-sm">
                        Bearbeiten
                    </a>
                @endcan

                @auth
                    <button class="btn-ki-outline-sm text-red-600 hover:text-red-800" onclick="reportArticle('{{ $article->slug }}')">
                        Melden
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    @if($article->allow_comments ?? true)
        <div class="bg-white rounded-lg shadow-primary p-6">
            <h3 class="text-lg font-semibold mb-4">Kommentare</h3>

            @auth
                <div class="mb-6">
                    <form action="{{ route('wiki.comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="article_id" value="{{ $article->id }}">
                        <div class="mb-4">
                            <label class="form-label">Kommentar schreiben</label>
                            <textarea name="content" rows="4" class="form-input" placeholder="Schreibe einen Kommentar..."></textarea>
                        </div>
                        <button type="submit" class="btn-ki-primary">
                            Kommentar absenden
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-gray-600">
                        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800">Melde dich an</a>
                        oder
                        <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-800">registriere dich</a>,
                        um einen Kommentar zu schreiben.
                    </p>
                </div>
            @endauth

            <!-- Comments List -->
            <div class="space-y-4">
                @php
                    $comments = collect();
                    try {
                        $comments = $article->comments()
                            ->where('status', 'approved')
                            ->with('user')
                            ->orderBy('created_at', 'desc')
                            ->get();
                    } catch (\Exception $e) {
                        // Fallback für den Fall, dass die Kommentare nicht geladen werden können
                        $comments = collect();
                    }
                @endphp

                @forelse($comments as $comment)
                    <div class="border-l-4 border-primary-200 pl-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="font-medium">{{ $comment->user->name ?? 'Unbekannt' }}</span>
                                <span class="text-sm text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            @auth
                                <div class="relative">
                                    <button class="text-gray-400 hover:text-gray-600" onclick="toggleCommentMenu({{ $comment->id }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div id="commentMenu{{ $comment->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                        <div class="py-1">
                                            @if(auth()->user()->id === $comment->user_id)
                                                <button onclick="editComment({{ $comment->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Bearbeiten
                                                </button>
                                                <button onclick="deleteComment({{ $comment->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Löschen
                                                </button>
                                            @endif
                                            
                                            @if(auth()->user()->id !== $comment->user_id)
                                                <button onclick="reportComment({{ $comment->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    Melden
                                                </button>
                                            @endif
                                            
                                            @can('delete all comments')
                                                <div class="border-t border-gray-100 mt-1 pt-1">
                                                    <button onclick="adminDeleteComment({{ $comment->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Admin: Löschen
                                                    </button>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>
                        <!-- Normale Kommentar-Anzeige -->
                        <div id="commentContent{{ $comment->id }}">
                            <p class="text-gray-700">{{ $comment->content }}</p>
                        </div>
                        
                        <!-- Bearbeitungsform (initial versteckt) -->
                        <div id="editForm{{ $comment->id }}" class="hidden mt-2">
                            <form action="{{ route('wiki.comments.update', $comment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea name="content" rows="3" class="form-input w-full mb-2">{{ $comment->content }}</textarea>
                                <div class="flex space-x-2">
                                    <button type="submit" class="btn-ki-primary-sm">Speichern</button>
                                    <button type="button" onclick="cancelEdit({{ $comment->id }})" class="btn-ki-outline-sm">Abbrechen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <p class="text-gray-500">Noch keine Kommentare vorhanden.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Related Articles -->
    @if($relatedArticles && $relatedArticles->count() > 0)
        <div class="bg-white rounded-lg shadow-primary p-6 mt-6">
            <h3 class="text-lg font-semibold mb-4">Ähnliche Artikel</h3>
            <div class="grid md:grid-cols-3 gap-4">
                @foreach($relatedArticles as $related)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium mb-2">
                            <a href="{{ route('wiki.articles.show', $related->slug) }}" class="text-primary-600 hover:text-primary-800">
                                {{ $related->title }}
                            </a>
                        </h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $related->excerpt }}</p>
                        <div class="text-xs text-gray-500">
                            {{ $related->reading_time ?? 5 }} min Lesezeit
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Deletion Request Modal -->
@can('delete', $article)
@if(!auth()->user()->can('delete all articles') && !$article->deletion_requested_at)
<div id="deletionRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Löschung beantragen</h3>
            <form method="POST" action="{{ route('wiki.articles.request-deletion', $article->slug) }}" class="mt-4">
                @csrf
                <div class="text-left">
                    <label for="deletion_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Begründung für die Löschung:
                    </label>
                    <textarea name="deletion_reason" 
                              id="deletion_reason" 
                              rows="3" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                              placeholder="Bitte begründe, warum dieser Artikel gelöscht werden soll..."></textarea>
                </div>
                <div class="items-center px-4 py-3">
                    <button type="submit" 
                            class="px-4 py-2 bg-secondary-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-secondary-300">
                        Löschung beantragen
                    </button>
                    <button type="button" 
                            onclick="hideDeletionRequestModal()"
                            class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Abbrechen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showDeletionRequestModal() {
    document.getElementById('deletionRequestModal').classList.remove('hidden');
}

function hideDeletionRequestModal() {
    document.getElementById('deletionRequestModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deletionRequestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeletionRequestModal();
    }
});

</script>
@endif
@endcan

<!-- Kommentar-JavaScript (muss außerhalb der Permissions stehen) -->
<script>
// Kommentar-Menü Funktionen
function toggleCommentMenu(commentId) {
    const menu = document.getElementById('commentMenu' + commentId);
    if (!menu) return;
    
    const allMenus = document.querySelectorAll('[id^="commentMenu"]');
    
    // Alle anderen Menüs schließen
    allMenus.forEach(m => {
        if (m.id !== 'commentMenu' + commentId) {
            m.classList.add('hidden');
        }
    });
    
    // Aktuelles Menü togglen
    menu.classList.toggle('hidden');
}

function editComment(commentId) {
    const contentEl = document.getElementById('commentContent' + commentId);
    const formEl = document.getElementById('editForm' + commentId);
    const menuEl = document.getElementById('commentMenu' + commentId);
    
    if (contentEl) contentEl.classList.add('hidden');
    if (formEl) formEl.classList.remove('hidden');
    if (menuEl) menuEl.classList.add('hidden');
}

function cancelEdit(commentId) {
    const contentEl = document.getElementById('commentContent' + commentId);
    const formEl = document.getElementById('editForm' + commentId);
    
    if (contentEl) contentEl.classList.remove('hidden');
    if (formEl) formEl.classList.add('hidden');
}

function deleteComment(commentId) {
    if (confirm('Kommentar wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/wiki/comments/${commentId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function adminDeleteComment(commentId) {
    if (confirm('Als Admin diesen Kommentar löschen? Diese Aktion kann nicht rückgängig gemacht werden.')) {
        deleteComment(commentId);
    }
}

function reportComment(commentId) {
    if (confirm('Diesen Kommentar als unangemessen melden?')) {
        // TODO: Report-Funktionalität implementieren
        alert('Kommentar wurde gemeldet. Danke für dein Feedback!');
    }
}

// Menüs schließen beim Klick außerhalb
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        const allMenus = document.querySelectorAll('[id^="commentMenu"]');
        allMenus.forEach(menu => menu.classList.add('hidden'));
    }
});

// Artikel-Interaktions-Funktionen
function toggleLike(articleSlug) {
    const btn = document.getElementById('likeBtn');
    const text = document.getElementById('likeText');
    
    btn.disabled = true;
    
    fetch(`/wiki/articles/${articleSlug}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.liked) {
                btn.classList.remove('btn-ki-outline-sm');
                btn.classList.add('btn-ki-primary-sm');
                text.textContent = 'Geliked';
            } else {
                btn.classList.remove('btn-ki-primary-sm');
                btn.classList.add('btn-ki-outline-sm');
                text.textContent = 'Liken';
            }
        } else {
            alert('Fehler beim Liken: ' + (data.message || 'Unbekannter Fehler'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Liken. Bitte versuche es erneut.');
    })
    .finally(() => {
        btn.disabled = false;
    });
}

function toggleBookmark(articleSlug) {
    const btn = document.getElementById('bookmarkBtn');
    const text = document.getElementById('bookmarkText');
    
    btn.disabled = true;
    
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
            if (data.bookmarked) {
                btn.classList.remove('btn-ki-outline-sm');
                btn.classList.add('btn-ki-primary-sm');
                text.textContent = 'Gemerkt';
            } else {
                btn.classList.remove('btn-ki-primary-sm');
                btn.classList.add('btn-ki-outline-sm');
                text.textContent = 'Merken';
            }
        } else {
            alert('Fehler beim Merken: ' + (data.message || 'Unbekannter Fehler'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Merken. Bitte versuche es erneut.');
    })
    .finally(() => {
        btn.disabled = false;
    });
}

function voteHelpful(articleSlug, isHelpful) {
    const helpfulBtn = document.getElementById('helpfulBtn');
    const notHelpfulBtn = document.getElementById('notHelpfulBtn');
    const helpfulText = document.getElementById('helpfulText');
    const notHelpfulText = document.getElementById('notHelpfulText');
    
    helpfulBtn.disabled = true;
    notHelpfulBtn.disabled = true;
    
    fetch(`/wiki/articles/${articleSlug}/vote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ helpful: isHelpful })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset both buttons
            helpfulBtn.classList.remove('btn-ki-primary-sm');
            helpfulBtn.classList.add('btn-ki-outline-sm');
            notHelpfulBtn.classList.remove('btn-ki-primary-sm');
            notHelpfulBtn.classList.add('btn-ki-outline-sm');
            helpfulText.textContent = 'Hilfreich';
            notHelpfulText.textContent = 'Nicht hilfreich';
            
            // Highlight the voted button
            if (data.vote === 'helpful') {
                helpfulBtn.classList.remove('btn-ki-outline-sm');
                helpfulBtn.classList.add('btn-ki-primary-sm');
                helpfulText.textContent = 'Als hilfreich bewertet';
            } else if (data.vote === 'not_helpful') {
                notHelpfulBtn.classList.remove('btn-ki-outline-sm');
                notHelpfulBtn.classList.add('btn-ki-primary-sm');
                notHelpfulText.textContent = 'Als nicht hilfreich bewertet';
            }
        } else {
            alert('Fehler beim Bewerten: ' + (data.message || 'Unbekannter Fehler'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Bewerten. Bitte versuche es erneut.');
    })
    .finally(() => {
        helpfulBtn.disabled = false;
        notHelpfulBtn.disabled = false;
    });
}

function reportArticle(articleSlug) {
    const reason = prompt('Bitte gib einen Grund für die Meldung an:');
    if (!reason || reason.trim() === '') {
        return;
    }
    
    fetch(`/wiki/articles/${articleSlug}/report`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ reason: reason.trim() })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Artikel wurde gemeldet. Danke für dein Feedback!');
        } else {
            alert('Fehler beim Melden: ' + (data.message || 'Unbekannter Fehler'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Melden. Bitte versuche es erneut.');
    });
}
</script>

@endsection

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Article",
    "headline": "{{ $article->title }}",
    "description": "{{ $article->excerpt ?? substr(strip_tags($article->content ?? ''), 0, 155) }}",
    "image": {
        "@@type": "ImageObject",
        "url": "{{ asset('images/article-social.jpg') }}",
        "width": 1200,
        "height": 630
    },
    "author": {
        "@@type": "Person",
        "name": "{{ $article->user->name ?? 'KI-Coding Team' }}",
        "url": "{{ $article->user ? ($article->user->username ? route('profile.show', $article->user->username) : route('profile.show.id', $article->user->id)) : route('home') }}"
    },
    "publisher": {
        "@@type": "Organization",
        "name": "KI-Coding",
        "url": "{{ config('app.url') }}",
        "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('images/icon-512x512.png') }}",
            "width": 512,
            "height": 512
        }
    },
    "datePublished": "{{ $article->published_at ? $article->published_at->toISOString() : $article->created_at->toISOString() }}",
    "dateModified": "{{ $article->updated_at->toISOString() }}",
    "mainEntityOfPage": {
        "@@type": "WebPage",
        "@id": "{{ route('wiki.articles.show', $article->slug) }}"
    },
    "articleSection": "{{ $article->category->name ?? 'KI-Programmierung' }}",
    "wordCount": {{ str_word_count(strip_tags($article->content ?? '')) }},
    "timeRequired": "PT{{ $article->reading_time ?? 5 }}M",
    "inLanguage": "de-DE",
    "about": [
        @if($article->tags && $article->tags->count() > 0)
            @foreach($article->tags as $index => $tag)
                {
                    "@@type": "Thing",
                    "name": "{{ $tag->name }}"
                }@if(!$loop->last),@endif
            @endforeach
        @else
            {
                "@@type": "Thing",
                "name": "KI-Programmierung"
            }
        @endif
    ],
    "breadcrumb": {
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ route('home') }}"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "Wiki",
                "item": "{{ route('wiki.index') }}"
            },
            {
                "@@type": "ListItem",
                "position": 3,
                "name": "{{ $article->title }}",
                "item": "{{ route('wiki.articles.show', $article->slug) }}"
            }
        ]
    }
}
</script>
@endpush
