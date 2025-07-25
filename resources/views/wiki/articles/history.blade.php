@extends('layouts.app')

@section('title', 'Versionshistorie: ' . $article->title . ' | KI-Coding.de')
@section('description', 'Versionshistorie und Änderungen des Artikels: ' . $article->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg p-6 mb-6 border-l-4 border-primary-500">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Versionshistorie</h1>
                <p class="text-gray-600">
                    <a href="{{ route('wiki.articles.show', $article->slug) }}" class="text-primary-600 hover:text-primary-800">
                        {{ $article->title }}
                    </a>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('wiki.articles.show', $article->slug) }}" class="btn-ki-outline-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Zurück zum Artikel
                </a>
            </div>
        </div>
    </div>

    <!-- Revision Statistics -->
    <div class="bg-white rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-primary-600">{{ $revisions->total() }}</div>
                <div class="text-sm text-gray-500">Gesamte Versionen</div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $article->user->name }}</div>
                <div class="text-sm text-gray-500">Ersteller*in</div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $article->created_at->format('d.m.Y') }}</div>
                <div class="text-sm text-gray-500">Erstellt am</div>
            </div>
        </div>
    </div>

    <!-- Compare Form (wird das gesamte Revisions-Section umschließen) -->
    <form id="compareForm" action="{{ route('wiki.articles.revisions.compare', $article->slug) }}" method="GET">
        <!-- Compare Header -->
        <div class="bg-white rounded-lg p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Versionen vergleichen</h3>
                    <p class="text-sm text-gray-600">Wähle zwei Versionen aus, um die Unterschiede anzuzeigen.</p>
                </div>
                <button type="submit" id="compareBtn" class="btn-ki-primary" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 002 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    Versionen vergleichen
                </button>
            </div>
        </div>

        <!-- Revisions List -->
        <div class="space-y-4">
        @forelse($revisions as $revision)
            <div class="bg-white rounded-lg border {{ $revision->isLatest() ? 'border-green-300 bg-green-50' : 'border-gray-200' }} overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Compare Checkbox -->
                            <div class="flex items-center pt-1">
                                <input type="checkbox" id="revision_{{ $revision->id }}" name="revisions[]" 
                                       value="{{ $revision->id }}" class="compare-checkbox w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2">
                                <label for="revision_{{ $revision->id }}" class="sr-only">Version {{ $revision->version_number }} zum Vergleich auswählen</label>
                            </div>
                            
                            <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $revision->isLatest() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    Version {{ $revision->version_number }}
                                    @if($revision->isLatest())
                                        (Aktuell)
                                    @endif
                                </span>
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($revision->revision_type === 'create') bg-blue-100 text-blue-800
                                    @elseif($revision->revision_type === 'restore') bg-orange-100 text-orange-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    @if($revision->revision_type === 'create')
                                        Erstellung
                                    @elseif($revision->revision_type === 'restore')
                                        Wiederherstellung
                                    @else
                                        Bearbeitung
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $revision->user->name }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $revision->created_at->format('d.m.Y H:i') }}
                                </div>
                            </div>

                            @if($revision->change_summary)
                                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                    <p class="text-sm text-gray-700">
                                        <strong>Änderungshinweis:</strong> {{ $revision->change_summary }}
                                    </p>
                                </div>
                            @endif

                            @if($revision->title !== $article->title)
                                <div class="text-sm text-gray-600 mb-2">
                                    <strong>Titel:</strong> {{ $revision->title }}
                                </div>
                            @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('wiki.articles.revisions.show', [$article->slug, $revision->id]) }}" 
                               class="btn-ki-outline-sm" title="Version anzeigen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Anzeigen
                            </a>

                            @can('update', $article)
                                @unless($revision->isLatest())
                                    <form action="{{ route('wiki.articles.revisions.restore', [$article->slug, $revision->id]) }}" 
                                          method="POST" class="inline-block" 
                                          onsubmit="return confirm('Bist du sicher, dass du diese Version wiederherstellen möchtest? Dies erstellt eine neue Version.')">
                                        @csrf
                                        <button type="submit" class="btn-ki-primary-sm" title="Version wiederherstellen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                            Wiederherstellen
                                        </button>
                                    </form>
                                @endunless
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Versionen verfügbar</h3>
                <p class="text-gray-500">Für diesen Artikel sind noch keine Versionen gespeichert.</p>
            </div>
        @endforelse
        </div>
    </form>

    <!-- Pagination -->
    @if($revisions->hasPages())
        <div class="mt-6">
            {{ $revisions->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.compare-checkbox');
    const compareBtn = document.getElementById('compareBtn');
    const compareForm = document.getElementById('compareForm');
    
    function updateCompareButton() {
        const checked = document.querySelectorAll('.compare-checkbox:checked');
        compareBtn.disabled = checked.length !== 2;
        
        if (checked.length === 2) {
            compareBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            compareBtn.classList.add('hover:bg-primary-700');
        } else {
            compareBtn.classList.add('opacity-50', 'cursor-not-allowed');
            compareBtn.classList.remove('hover:bg-primary-700');
        }
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checked = document.querySelectorAll('.compare-checkbox:checked');
            
            // Maximal 2 Checkboxen erlauben
            if (checked.length > 2) {
                this.checked = false;
                return;
            }
            
            // Andere Checkboxen deaktivieren wenn 2 ausgewählt sind
            if (checked.length === 2) {
                checkboxes.forEach(cb => {
                    if (!cb.checked) {
                        cb.disabled = true;
                        cb.parentElement.classList.add('opacity-50');
                    }
                });
            } else {
                checkboxes.forEach(cb => {
                    cb.disabled = false;
                    cb.parentElement.classList.remove('opacity-50');
                });
            }
            
            updateCompareButton();
        });
    });
    
    compareForm.addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.compare-checkbox:checked');
        if (checked.length !== 2) {
            e.preventDefault();
            alert('Bitte wähle genau zwei Versionen zum Vergleichen aus.');
        }
    });
    
    // Initial state
    updateCompareButton();
});
</script>
@endsection