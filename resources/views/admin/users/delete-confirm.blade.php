@extends('layouts.app')

@section('title', 'Delete User - ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Delete User</h1>
                <p class="mt-2 text-gray-600">Permanently delete {{ e($user->name) }}'s account</p>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="btn-ki-outline">
                ← Back
            </a>
        </div>
    </div>

    <!-- Warning -->
    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Warning: This action cannot be undone
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>You are about to permanently delete the user account for <strong>{{ e($user->name) }}</strong>.</p>
                    @if($articlesCount > 0)
                    <p class="mt-1">This user has <strong>{{ $articlesCount }}</strong> article(s). You must decide what to do with them.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">User Information</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 flex-shrink-0">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
                        <span class="text-lg font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-medium text-gray-900">{{ e($user->name) }}</h4>
                    <p class="text-gray-600">{{ e($user->email) }}</p>
                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                        <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                        <span>{{ $articlesCount }} articles</span>
                        <span>{{ $user->comments->count() ?? 0 }} comments</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Deletion Options</h3>
        </div>
        <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="p-6">
            @csrf
            @method('DELETE')
            
            @if($articlesCount > 0)
            <div class="mb-6">
                <fieldset>
                    <legend class="text-sm font-medium text-gray-700 mb-3">
                        What should happen to the user's {{ $articlesCount }} article(s)?
                    </legend>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="delete-articles" name="article_action" type="radio" value="delete" 
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="delete-articles" class="font-medium text-gray-700">
                                    Delete all articles
                                </label>
                                <p class="text-gray-500">Permanently remove all articles written by this user.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="reassign-articles" name="article_action" type="radio" value="reassign" 
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="reassign-articles" class="font-medium text-gray-700">
                                    Reassign articles to another user
                                </label>
                                <p class="text-gray-500">Transfer ownership of all articles to another user.</p>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            
            <div id="reassign-options" class="mb-6" style="display: none;">
                <label for="reassign_to" class="block text-sm font-medium text-gray-700 mb-2">
                    Assign articles to:
                </label>
                <select name="reassign_to" id="reassign_to" class="form-select">
                    <option value="">Select a user...</option>
                    @foreach($availableUsers as $availableUser)
                    <option value="{{ $availableUser->id }}">
                        {{ $availableUser->name }} ({{ $availableUser->email }})
                        @if($availableUser->can('admin access')) - Admin @endif
                        @if($availableUser->can('moderate content')) - Moderator @endif
                    </option>
                    @endforeach
                </select>
                @error('reassign_to')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @else
            <input type="hidden" name="article_action" value="delete">
            <p class="mb-6 text-sm text-gray-600">This user has no articles to handle.</p>
            @endif
            
            <!-- Confirmation -->
            <div class="mb-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="confirm-delete" name="confirm_delete" type="checkbox" required
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="confirm-delete" class="font-medium text-gray-700">
                            I understand that this action cannot be undone
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.users.show', $user) }}" class="btn-ki-outline">
                    Cancel
                </a>
                <button type="submit" 
                        class="btn-ki-primary bg-red-600 hover:bg-red-700 focus:ring-red-500"
                        onclick="return confirm('Are you absolutely sure you want to delete {{ e($user->name) }}? This cannot be undone.')">
                    Delete User Permanently
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reassignRadio = document.getElementById('reassign-articles');
    const deleteRadio = document.getElementById('delete-articles');
    const reassignOptions = document.getElementById('reassign-options');
    const reassignSelect = document.getElementById('reassign_to');
    
    function toggleReassignOptions() {
        if (reassignRadio && reassignRadio.checked) {
            reassignOptions.style.display = 'block';
            reassignSelect.required = true;
        } else {
            reassignOptions.style.display = 'none';
            reassignSelect.required = false;
            reassignSelect.value = '';
        }
    }
    
    if (reassignRadio) {
        reassignRadio.addEventListener('change', toggleReassignOptions);
    }
    if (deleteRadio) {
        deleteRadio.addEventListener('change', toggleReassignOptions);
    }
});
</script>
@endsection