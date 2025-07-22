{{-- Recursive comment template for hierarchical display --}}
@php
    $maxDepth = 2; // Maximum nesting depth (0, 1, 2 = 3 levels)
    $indentClass = match($depth) {
        0 => 'ml-0',
        1 => 'ml-8 md:ml-12', 
        default => 'ml-12 md:ml-16'
    };
    $borderClass = match($depth) {
        0 => 'border-l-4 border-primary-200',
        1 => 'border-l-4 border-blue-200',
        default => 'border-l-4 border-gray-200'
    };
@endphp

<div class="comment {{ $indentClass }} {{ $borderClass }} pl-4 pb-4">
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-sm font-medium text-primary-700">
                {{ substr($comment->user->name ?? 'U', 0, 1) }}
            </div>
            <div>
                <span class="font-medium text-gray-900">{{ $comment->user->name ?? 'Unbekannt' }}</span>
                <span class="text-sm text-gray-500 ml-2">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
            </div>
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
    
    <!-- Comment Content -->
    <div id="commentContent{{ $comment->id }}" class="mb-3">
        <div class="prose prose-sm max-w-none text-gray-700">
            {!! $comment->rendered_content !!}
        </div>
    </div>
    
    <!-- Edit Form (initially hidden) -->
    <div id="editForm{{ $comment->id }}" class="hidden mb-3">
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
    
    <!-- Actions -->
    @auth
        @if($depth < $maxDepth)
            <div class="flex items-center space-x-4 text-sm">
                <button onclick="toggleReplyForm({{ $comment->id }})" class="text-primary-600 hover:text-primary-800 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                    Antworten
                </button>
            </div>
        @endif
    @endauth
    
    <!-- Reply Form (initially hidden) -->
    @auth
        @if($depth < $maxDepth)
            <div id="replyForm{{ $comment->id }}" class="hidden mt-4 {{ $depth === 0 ? 'ml-10' : 'ml-6' }}">
                <form action="{{ route('wiki.comments.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="article_id" value="{{ $comment->article_id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center text-xs font-medium text-primary-700">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <textarea 
                                name="content" 
                                rows="3" 
                                placeholder="Schreibe eine Antwort..."
                                class="form-input w-full text-sm"
                                required
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 ml-9">
                        <button type="button" onclick="cancelReply({{ $comment->id }})" class="btn-ki-outline-sm">
                            Abbrechen
                        </button>
                        <button type="submit" class="btn-ki-primary-sm">
                            Antworten
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endauth
    
    <!-- Replies -->
    @if($comment->replies && $comment->replies->count() > 0 && $depth < $maxDepth)
        <div class="replies mt-4 space-y-4">
            @foreach($comment->replies->where('status', 'approved')->sortBy('created_at') as $reply)
                @include('wiki.comments.comment', ['comment' => $reply, 'depth' => $depth + 1])
            @endforeach
        </div>
    @elseif($comment->replies && $comment->replies->count() > 0 && $depth >= $maxDepth)
        <!-- Show count of hidden deeper replies -->
        <div class="mt-3 ml-4">
            <p class="text-sm text-gray-500">
                {{ $comment->replies->where('status', 'approved')->count() }} weitere Antwort(en) verfügbar
            </p>
        </div>
    @endif
</div>