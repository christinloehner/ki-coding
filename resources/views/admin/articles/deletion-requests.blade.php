@extends('layouts.app')

@section('title', 'Article Deletion Requests - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Article Deletion Requests</h1>
                <p class="mt-2 text-gray-600">Review and manage pending deletion requests</p>
            </div>
            <a href="{{ route('admin.articles.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Back to Articles
            </a>
        </div>
    </div>

    <!-- Deletion Requests Table -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Pending Deletion Requests ({{ $articles->total() }})
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($articles as $article)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('wiki.articles.show', $article->slug) }}" 
                                       class="hover:text-indigo-600" target="_blank">
                                        {{ $article->title }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $article->category->name ?? 'Uncategorized' }}
                                </div>
                                <div class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($article->status === 'published') bg-green-100 text-green-800
                                        @elseif($article->status === 'pending_review') bg-yellow-100 text-yellow-800
                                        @elseif($article->status === 'draft') bg-gray-100 text-gray-800
                                        @elseif($article->status === 'archived') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ strtoupper(substr($article->user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $article->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $article->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($article->deletionRequestedByUser)
                                <div class="flex items-center">
                                    <div class="h-8 w-8 flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-400 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">
                                                {{ strtoupper(substr($article->deletionRequestedByUser->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $article->deletionRequestedByUser->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $article->deletionRequestedByUser->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Unknown</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                @if($article->deletion_reason)
                                    <p class="text-sm text-gray-900 truncate" title="{{ $article->deletion_reason }}">
                                        {{ Str::limit($article->deletion_reason, 100) }}
                                    </p>
                                @else
                                    <span class="text-sm text-gray-500 italic">No reason provided</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->deletion_requested_at->format('M d, Y H:i') }}
                            <br>
                            <span class="text-xs text-gray-400">
                                {{ $article->deletion_requested_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('wiki.articles.show', $article->slug) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" target="_blank" title="View Article">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Approve Deletion -->
                                <form method="POST" action="{{ route('wiki.articles.destroy', $article->slug) }}" 
                                      class="inline" onsubmit="return confirm('Artikel wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Approve Deletion">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                
                                <!-- Deny Deletion Request -->
                                <form method="POST" action="{{ route('wiki.articles.cancel-deletion', $article->slug) }}" 
                                      class="inline" onsubmit="return confirm('Löschungsantrag wirklich ablehnen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-600 hover:text-gray-900" title="Deny Request">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No deletion requests</h3>
                                <p class="mt-1 text-sm text-gray-500">There are currently no pending article deletion requests.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($articles->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection