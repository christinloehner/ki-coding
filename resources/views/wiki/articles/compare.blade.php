@extends('layouts.app')

@section('title', 'Versionsvergleich: ' . $article->title . ' | KI-Coding.de')
@section('description', 'Vergleich zwischen Version ' . $oldRevision->version_number . ' und Version ' . $newRevision->version_number . ' des Artikels: ' . $article->title)

@push('styles')
<style>
/* Simple Side-by-Side Diff */
.diff-container {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    overflow: hidden;
    max-height: 600px;
}

.diff-headers {
    display: grid;
    grid-template-columns: 1fr 1fr;
    background-color: #f8f9fa;
}

.diff-header-left, .diff-header-right {
    padding: 8px 12px;
    font-weight: 600;
    border-bottom: 1px solid #dee2e6;
}

.diff-header-left {
    background-color: #f8d7da;
    color: #721c24;
    border-right: 1px solid #dee2e6;
}

.diff-header-right {
    background-color: #d4edda;
    color: #155724;
}

.diff-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    height: calc(600px - 45px);
    overflow: hidden;
}

.diff-side {
    overflow: auto;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 13px;
    line-height: 1.4;
}

.diff-left {
    border-right: 1px solid #dee2e6;
}

.diff-line {
    display: flex;
    height: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    margin: 0;
    padding: 0;
    width: 100%;
}

.line-number {
    width: 40px;
    padding: 2px 6px;
    background-color: #f8f9fa;
    border-right: 1px solid #e9ecef;
    text-align: right;
    font-size: 11px;
    color: #6c757d;
    user-select: none;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.line-content {
    flex: 1;
    padding: 2px 6px;
    white-space: pre;
    overflow: visible;
    line-height: 16px;
    min-height: 16px;
    position: relative;
}

.diff-line.line-equal {
    background-color: #f8f9fa;
    border-left: 3px solid #e9ecef;
}

.diff-line.line-equal .line-content {
    background-color: #f8f9fa;
}

.diff-line.line-added {
    background-color: #d4edda;
    border-left: 3px solid #28a745;
}

.diff-line.line-added .line-content {
    background-color: #d4edda;
    color: #155724;
}

.diff-line.line-deleted {
    background-color: #f8d7da;
    border-left: 3px solid #dc3545;
}

.diff-line.line-deleted .line-content {
    background-color: #f8d7da;
    color: #721c24;
}

.diff-line.line-empty {
    background-color: #ffffff;
    border-left: 3px solid transparent;
}

.diff-line.line-empty .line-content {
    background-color: #ffffff;
}

.diff-word .diff-added {
    background-color: #acf2bd;
    padding: 1px 2px;
    border-radius: 2px;
}

.diff-word .diff-deleted {
    background-color: #fdb8c0;
    padding: 1px 2px;
    border-radius: 2px;
}

.diff-word .diff-equal {
    background-color: transparent;
}

.diff-stats {
    font-size: 12px;
}

.diff-stats .stat-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 4px;
    margin-right: 8px;
}

.stat-added { background-color: #d4edda; color: #155724; }
.stat-removed { background-color: #f8d7da; color: #721c24; }
.stat-changed { background-color: #fff3cd; color: #856404; }
.stat-unchanged { background-color: #f8f9fa; color: #6c757d; }

/* Dark Mode Styles (nur für data-theme="dark") mit !important */
/* Dark Mode Container */
.dark .diff-container {
    border-color: #4b5563 !important;
    background-color: #1f2937 !important;
}

/* Dark Mode Headers */
.dark .diff-headers {
    background-color: #111827 !important;
}

.dark .diff-header-left, .dark .diff-header-right {
    border-bottom-color: #4b5563 !important;
}

.dark .diff-header-left {
    background-color: #7f1d1d !important;
    color: #fca5a5 !important;
    border-right-color: #4b5563 !important;
}

.dark .diff-header-right {
    background-color: #14532d !important;
    color: #86efac !important;
}

/* Dark Mode Content */
.dark .diff-left {
    border-right-color: #4b5563 !important;
}

.dark .line-number {
    background-color: #374151 !important;
    border-right-color: #4b5563 !important;
    color: #9ca3af !important;
}

/* Dark Mode Line Styles */
.dark .diff-line.line-equal {
    background-color: #374151 !important;
    border-left-color: #6b7280 !important;
}

.dark .diff-line.line-equal .line-content {
    background-color: #374151 !important;
    color: #e5e7eb !important;
}

.dark .diff-line.line-added {
    background-color: #065f46 !important;
    border-left-color: #10b981 !important;
}

.dark .diff-line.line-added .line-content {
    background-color: #065f46 !important;
    color: #a7f3d0 !important;
}

.dark .diff-line.line-deleted {
    background-color: #991b1b !important;
    border-left-color: #ef4444 !important;
}

.dark .diff-line.line-deleted .line-content {
    background-color: #991b1b !important;
    color: #fca5a5 !important;
}

.dark .diff-line.line-empty {
    background-color: #1f2937 !important;
    border-left-color: transparent !important;
}

.dark .diff-line.line-empty .line-content {
    background-color: #1f2937 !important;
}

/* Dark Mode Word Diff */
.dark .diff-word .diff-added {
    background-color: #047857 !important;
    color: #a7f3d0 !important;
}

.dark .diff-word .diff-deleted {
    background-color: #b91c1c !important;
    color: #fca5a5 !important;
}

/* Dark Mode Statistics */
.dark .stat-added { 
    background-color: #065f46 !important; 
    color: #a7f3d0 !important; 
}

.dark .stat-removed { 
    background-color: #991b1b !important; 
    color: #fca5a5 !important; 
}

.dark .stat-changed { 
    background-color: #92400e !important; 
    color: #fcd34d !important; 
}

.dark .stat-unchanged { 
    background-color: #374151 !important; 
    color: #d1d5db !important; 
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const leftSide = document.getElementById('diff-left');
    const rightSide = document.getElementById('diff-right');
    
    if (!leftSide || !rightSide) return;
    
    let isLeftScrolling = false;
    let isRightScrolling = false;
    
    leftSide.addEventListener('scroll', function() {
        if (isRightScrolling) return;
        isLeftScrolling = true;
        
        rightSide.scrollTop = leftSide.scrollTop;
        rightSide.scrollLeft = leftSide.scrollLeft;
        
        setTimeout(() => { isLeftScrolling = false; }, 10);
    });
    
    rightSide.addEventListener('scroll', function() {
        if (isLeftScrolling) return;
        isRightScrolling = true;
        
        leftSide.scrollTop = rightSide.scrollTop;
        leftSide.scrollLeft = rightSide.scrollLeft;
        
        setTimeout(() => { isRightScrolling = false; }, 10);
    });
});
</script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg p-6 mb-6 border-l-4 border-primary-500">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Versionsvergleich</h1>
                <p class="text-gray-600">
                    <a href="{{ route('wiki.articles.show', $article->slug) }}" class="text-primary-600 hover:text-primary-800">
                        {{ $article->title }}
                    </a>
                </p>
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                    <span>Version {{ $oldRevision->version_number }} ↔ Version {{ $newRevision->version_number }}</span>
                    <span>{{ $oldRevision->created_at->format('d.m.Y H:i') }} → {{ $newRevision->created_at->format('d.m.Y H:i') }}</span>
                </div>
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

    <!-- Statistics -->
    <div class="bg-white rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Änderungsstatistik</h3>
        <div class="diff-stats">
            @if($stats['lines_added'] > 0)
                <span class="stat-item stat-added">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    +{{ $stats['lines_added'] }} Zeilen hinzugefügt
                </span>
            @endif
            
            @if($stats['lines_removed'] > 0)
                <span class="stat-item stat-removed">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    -{{ $stats['lines_removed'] }} Zeilen entfernt
                </span>
            @endif
            
            @if($stats['lines_changed'] > 0)
                <span class="stat-item stat-changed">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $stats['lines_changed'] }} Zeilen geändert
                </span>
            @endif
            
            <span class="stat-item stat-unchanged">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                {{ $stats['lines_unchanged'] }} Zeilen unverändert
            </span>
        </div>
    </div>

    <!-- Author Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg p-4 border border-red-200">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Version {{ $oldRevision->version_number }}</h4>
                    <p class="text-sm text-gray-500">{{ $oldRevision->user->name }} • {{ $oldRevision->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
            @if($oldRevision->change_summary)
                <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded">{{ $oldRevision->change_summary }}</p>
            @endif
        </div>

        <div class="bg-white rounded-lg p-4 border border-green-200">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Version {{ $newRevision->version_number }}</h4>
                    <p class="text-sm text-gray-500">{{ $newRevision->user->name }} • {{ $newRevision->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
            @if($newRevision->change_summary)
                <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded">{{ $newRevision->change_summary }}</p>
            @endif
        </div>
    </div>

    <!-- Title Diff -->
    @if($titleDiff['old'] !== $titleDiff['new'])
        <div class="bg-white rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Titel-Änderungen</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-red-700 mb-2">Version {{ $oldRevision->version_number }}</h4>
                    <div class="diff-word border border-red-200 rounded p-3 bg-red-50">
                        {!! $titleDiff['old'] !!}
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-green-700 mb-2">Version {{ $newRevision->version_number }}</h4>
                    <div class="diff-word border border-green-200 rounded p-3 bg-green-50">
                        {!! $titleDiff['new'] !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Content Diff -->
    <div class="bg-white rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Inhalt-Änderungen</h3>
        
        <!-- Simple Side-by-Side Diff -->
        <div class="diff-container">
            <!-- Headers -->
            <div class="diff-headers">
                <div class="diff-header-left">Version {{ $oldRevision->version_number }}</div>
                <div class="diff-header-right">Version {{ $newRevision->version_number }}</div>
            </div>
            
            <!-- Content -->
            <div class="diff-content">
                <div class="diff-side diff-left" id="diff-left">
                    @foreach($contentDiff as $line)
                    <div class="diff-line line-{{ $line['old']['type'] }}">
                        <span class="line-number">{{ $line['old']['number'] }}</span>
                        <span class="line-content" style="min-width: {{ $line['old']['maxLength'] ?? 100 }}ch;">{{ $line['old']['content'] ?: ' ' }}</span>
                    </div>
                    @endforeach
                </div>
                
                <div class="diff-side diff-right" id="diff-right">
                    @foreach($contentDiff as $line)
                    <div class="diff-line line-{{ $line['new']['type'] }}">
                        <span class="line-number">{{ $line['new']['number'] }}</span>
                        <span class="line-content" style="min-width: {{ $line['new']['maxLength'] ?? 100 }}ch;">{{ $line['new']['content'] ?: ' ' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Excerpt Diff -->
    @if(($oldRevision->excerpt || $newRevision->excerpt) && $excerptDiff['old'] !== $excerptDiff['new'])
        <div class="bg-white rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Zusammenfassung-Änderungen</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-red-700 mb-2">Version {{ $oldRevision->version_number }}</h4>
                    <div class="diff-word border border-red-200 rounded p-3 bg-red-50 text-sm">
                        {!! $excerptDiff['old'] ?: '<span class="text-gray-400">Keine Zusammenfassung</span>' !!}
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-green-700 mb-2">Version {{ $newRevision->version_number }}</h4>
                    <div class="diff-word border border-green-200 rounded p-3 bg-green-50 text-sm">
                        {!! $excerptDiff['new'] ?: '<span class="text-gray-400">Keine Zusammenfassung</span>' !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="bg-white rounded-lg p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="text-sm text-gray-500">
                Vergleich zwischen Version {{ $oldRevision->version_number }} ({{ $oldRevision->created_at->format('d.m.Y H:i') }}) 
                und Version {{ $newRevision->version_number }} ({{ $newRevision->created_at->format('d.m.Y H:i') }})
            </div>
            
            <div class="flex items-center gap-2">
                @can('update', $article)
                    @unless($newRevision->isLatest())
                        <form action="{{ route('wiki.articles.revisions.restore', [$article->slug, $newRevision->id]) }}" 
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Bist du sicher, dass du Version {{ $newRevision->version_number }} wiederherstellen möchtest?')">
                            @csrf
                            <button type="submit" class="btn-ki-primary-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Version {{ $newRevision->version_number }} wiederherstellen
                            </button>
                        </form>
                    @endunless
                @endcan
                
                <a href="{{ route('wiki.articles.revisions.show', [$article->slug, $newRevision->id]) }}" class="btn-ki-outline-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Version {{ $newRevision->version_number }} anzeigen
                </a>
            </div>
        </div>
    </div>
</div>
@endsection