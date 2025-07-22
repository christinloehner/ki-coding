@extends('layouts.app')

@section('title', 'Article Management - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Article Management</h1>
        <p class="mt-2 text-gray-600">Manage all articles, bulk operations, and deletion requests</p>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                    <select name="author" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Authors</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search articles..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    @can('moderate', App\Models\Article::class)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <form id="bulkForm" method="POST" class="flex items-center space-x-4">
                @csrf
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="selectAll" class="text-sm font-medium text-gray-700">Select All</label>
                </div>
                
                <select name="bulk_action" id="bulkAction" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Choose Action</option>
                    <option value="status_draft">📝 Set to Draft</option>
                    <option value="status_pending_review">⏳ Set to Pending Review</option>
                    <option value="status_published">✅ Set to Published</option>
                    <option value="status_archived">📦 Set to Archived</option>
                    @can('forceDelete', App\Models\Article::class)
                    <option value="delete" class="text-red-600">🗑️ Delete Selected</option>
                    @endcan
                </select>
                
                <button type="button" onclick="executeBulkAction()" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
                        id="bulkActionBtn" disabled>
                    Execute
                </button>
                
                <span class="text-sm text-gray-500" id="selectedCount">0 articles selected</span>
            </form>
        </div>
    </div>
    @endcan

    <!-- Articles Table -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">All Articles ({{ $articles->total() }})</h3>
            <a href="{{ route('admin.articles.deletion-requests') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                View Deletion Requests
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @can('moderate', App\Models\Article::class)
                            <input type="checkbox" id="tableSelectAll" class="rounded border-gray-300 text-indigo-600">
                            @endcan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Title
                                @if(request('sort') === 'title')
                                    <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Autor*innen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Created
                                @if(request('sort') === 'created_at')
                                    <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($articles as $article)
                    <tr class="hover:bg-gray-50" data-article-id="{{ $article->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @can('moderate', App\Models\Article::class)
                            <input type="checkbox" name="article_ids[]" value="{{ $article->id }}" 
                                   class="article-checkbox rounded border-gray-300 text-indigo-600">
                            @endcan
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    <a href="{{ route('wiki.articles.show', $article->slug) }}" 
                                       class="hover:text-indigo-600" target="_blank">
                                        {{ $article->title }}
                                    </a>
                                </div>
                                @if($article->deletion_requested_at)
                                    <div class="text-xs text-red-600 mt-1">
                                        <svg class="inline w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Deletion requested
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $article->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $article->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $article->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @can('changeStatus', App\Models\Article::class)
                            <form method="POST" action="{{ route('admin.articles.update-status', $article) }}" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" 
                                        class="text-sm rounded-full px-2 py-1 border-0 focus:ring-2 focus:ring-indigo-500
                                        @if($article->status === 'published') bg-green-100 text-green-800
                                        @elseif($article->status === 'pending_review') bg-yellow-100 text-yellow-800
                                        @elseif($article->status === 'draft') bg-gray-100 text-gray-800
                                        @elseif($article->status === 'archived') bg-red-100 text-red-800
                                        @endif">
                                    <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_review" {{ $article->status === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                                    <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ $article->status === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </form>
                            @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($article->status === 'published') bg-green-100 text-green-800
                                @elseif($article->status === 'pending_review') bg-yellow-100 text-yellow-800
                                @elseif($article->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($article->status === 'archived') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                            </span>
                            @endcan
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('wiki.articles.show', $article->slug) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" target="_blank" title="View Article">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $article)
                                <a href="{{ route('wiki.articles.edit', $article->slug) }}" 
                                   class="text-green-600 hover:text-green-900" title="Edit Article">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('forceDelete', $article)
                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" 
                                      class="inline" onsubmit="return confirm('Artikel wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete Article">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No articles found</h3>
                                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
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

<script>
// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.article-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
    updateBulkActionButton();
});

document.getElementById('tableSelectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.article-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
    updateBulkActionButton();
});

// Update selected count and button state
document.querySelectorAll('.article-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        updateSelectedCount();
        updateBulkActionButton();
    });
});

function updateSelectedCount() {
    const checked = document.querySelectorAll('.article-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = `${checked} articles selected`;
}

function updateBulkActionButton() {
    const checked = document.querySelectorAll('.article-checkbox:checked').length;
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    const bulkAction = document.getElementById('bulkAction');
    
    bulkActionBtn.disabled = checked === 0 || bulkAction.value === '';
}

document.getElementById('bulkAction').addEventListener('change', updateBulkActionButton);

function executeBulkAction() {
    const checkedBoxes = document.querySelectorAll('.article-checkbox:checked');
    const bulkAction = document.getElementById('bulkAction').value;
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one article.');
        return;
    }
    
    if (bulkAction === '') {
        alert('Please select an action.');
        return;
    }
    
    let confirmMessage = `Are you sure you want to execute this action on ${checkedBoxes.length} article(s)?`;
    if (bulkAction === 'delete') {
        confirmMessage = `Are you sure you want to DELETE ${checkedBoxes.length} article(s)? This action cannot be undone.`;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    const form = document.getElementById('bulkForm');
    
    // Add article IDs to form
    checkedBoxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'article_ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });
    
    // Set action URL based on bulk action
    if (bulkAction === 'delete') {
        form.action = '{{ route("admin.articles.bulk-delete") }}';
    } else if (bulkAction.startsWith('status_')) {
        form.action = '{{ route("admin.articles.bulk-update-status") }}';
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = bulkAction.replace('status_', '');
        form.appendChild(statusInput);
    }
    
    form.submit();
}

// Initialize
updateSelectedCount();
updateBulkActionButton();
</script>
@endsection