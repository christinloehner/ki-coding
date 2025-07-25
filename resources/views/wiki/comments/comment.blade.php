{{-- Modern recursive comment template with 6 nesting levels --}}
@php
    $maxDepth = 5; // Maximum nesting depth (0-5 = 6 levels)
    
    // Kompakte Einrückung pro Level
    $indentPixels = match($depth) {
        0 => 0,
        1 => 16,  // 1rem
        2 => 28,  // 1.75rem
        3 => 36,  // 2.25rem
        4 => 44,  // 2.75rem
        5 => 52,  // 3.25rem
        default => 56  // 3.5rem max
    };
    
    // Moderne Akzent-Linien statt Borders
    $accentClass = match($depth) {
        0 => 'border-l-2 border-primary-200',
        1 => 'border-l-2 border-blue-200',
        2 => 'border-l-2 border-purple-200',
        3 => 'border-l-2 border-amber-200',
        4 => 'border-l-2 border-green-200',
        5 => 'border-l-2 border-pink-200',
        default => 'border-l-2 border-gray-200'
    };
    
    // Kompakte Avatar-Größen für tiefere Levels
    $avatarSize = $depth >= 3 ? 'w-6 h-6' : 'w-8 h-8';
    $avatarTextSize = $depth >= 3 ? 'text-xs' : 'text-sm';
@endphp

<div class="comment-item relative" style="margin-left: {{ $indentPixels }}px;">
    <!-- Moderne Kommentar-Card -->
    <div class="bg-gray-50 rounded-lg p-3 {{ $accentClass }} border-opacity-60 hover:bg-gray-100 transition-colors duration-200">
        <!-- Header mit Avatar und Aktionen -->
        <div class="flex items-start justify-between mb-2">
            <div class="flex items-center space-x-2">
                <!-- Kompakter Avatar -->
                <div class="{{ $avatarSize }} bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center {{ $avatarTextSize }} font-semibold text-white shadow-sm">
                    {{ substr($comment->user->name ?? 'U', 0, 1) }}
                </div>
                
                <!-- User Info - Kompakt -->
                <div class="min-w-0 flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900 text-sm truncate">{{ $comment->user->name ?? 'Unbekannt' }}</span>
                        <span class="text-xs text-gray-500 whitespace-nowrap">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Aktions-Menu -->
            @auth
                <div class="relative ml-2">
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded transition-colors" onclick="toggleCommentMenu({{ $comment->id }})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="commentMenu{{ $comment->id }}" class="hidden absolute right-0 mt-1 w-40 bg-white rounded-md shadow-lg z-20 border border-gray-200">
                        <div class="py-1">
                            @if(auth()->user()->id === $comment->user_id)
                                <button onclick="editComment({{ $comment->id }})" class="block w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-3 h-3 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Bearbeiten
                                </button>
                                <button onclick="deleteComment({{ $comment->id }})" class="block w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-3 h-3 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Löschen
                                </button>
                            @endif
                            
                            @if(auth()->user()->id !== $comment->user_id)
                                <button onclick="reportComment({{ $comment->id }})" class="block w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-3 h-3 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Melden
                                </button>
                            @endif
                            
                            @can('delete all comments')
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <button onclick="adminDeleteComment({{ $comment->id }})" class="block w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-3 h-3 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        
        <!-- Comment Content -->
        <div id="commentContent{{ $comment->id }}" class="mb-2">
            <div class="prose prose-sm max-w-none text-gray-800 leading-relaxed">
                {!! $comment->rendered_content !!}
            </div>
        </div>
        
        <!-- Edit Form (initially hidden) -->
        <div id="editForm{{ $comment->id }}" class="hidden mb-2">
            <form action="{{ route('wiki.comments.update', $comment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="content" rows="3" class="form-input w-full mb-2 text-sm">{{ $comment->content }}</textarea>
                <div class="flex space-x-2">
                    <button type="submit" class="btn-ki-primary-sm text-xs px-3 py-1">Speichern</button>
                    <button type="button" onclick="cancelEdit({{ $comment->id }})" class="btn-ki-outline-sm text-xs px-3 py-1">Abbrechen</button>
                </div>
            </form>
        </div>
        
        <!-- Kompakte Actions -->
        @auth
            @if($depth < $maxDepth)
                <div class="flex items-center justify-between">
                    <button onclick="toggleReplyForm({{ $comment->id }})" class="text-primary-600 hover:text-primary-800 font-medium flex items-center text-xs py-1">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Antworten
                    </button>
                    
                    @if($depth > 0)
                        <span class="text-xs text-gray-400">Ebene {{ $depth + 1 }}</span>
                    @endif
                </div>
            @else
                <div class="text-xs text-gray-500">
                    Maximale Verschachtelungstiefe erreicht
                </div>
            @endif
        @endauth
    </div>
    
    <!-- Reply Form (initially hidden) -->
    @auth
        @if($depth < $maxDepth)
            <div id="replyForm{{ $comment->id }}" class="hidden mt-2 ml-2">
                <form action="{{ route('wiki.comments.store') }}" method="POST" class="bg-white rounded-lg p-3 border border-gray-200">
                    @csrf
                    <input type="hidden" name="article_id" value="{{ $comment->article_id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    
                    <div class="flex items-start space-x-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-xs font-semibold text-white shadow-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <textarea 
                                name="content" 
                                rows="2" 
                                placeholder="Antwort schreiben..."
                                class="form-input w-full text-sm resize-none"
                                required
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 mt-2">
                        <button type="button" onclick="cancelReply({{ $comment->id }})" class="btn-ki-outline-sm text-xs px-3 py-1">
                            Abbrechen
                        </button>
                        <button type="submit" class="btn-ki-primary-sm text-xs px-3 py-1">
                            Antworten
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endauth
</div>

<!-- Replies mit modernen Verbindungslinien -->
@if($comment->replies && $comment->replies->count() > 0 && $depth <= $maxDepth)
    <div class="replies mt-2 space-y-2">
        @foreach($comment->replies->where('status', 'approved')->sortBy('created_at') as $reply)
            @include('wiki.comments.comment', ['comment' => $reply, 'depth' => $depth + 1])
        @endforeach
    </div>
@elseif($comment->replies && $comment->replies->count() > 0 && $depth > $maxDepth)
    <!-- Hinweis auf weitere Antworten -->
    <div class="mt-2" style="margin-left: {{ $indentPixels + 16 }}px;">
        <div class="bg-blue-50 border border-blue-200 rounded-md p-2">
            <p class="text-xs text-blue-700">
                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $comment->replies->where('status', 'approved')->count() }} weitere Antwort(en) vorhanden
            </p>
        </div>
    </div>
@endif