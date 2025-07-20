@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                <p class="mt-2 text-gray-600">Manage {{ e($user->name) }}'s account and permissions</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-ki-outline">
                ← Back to Users
            </a>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center space-x-6">
                <div class="h-24 w-24 flex-shrink-0">
                    <div class="h-24 w-24 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ e($user->name) }}</h2>
                    <p class="text-gray-600">{{ e($user->email) }}</p>
                    <div class="mt-2 flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </span>
                        @if($user->is_banned)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Banned
                            </span>
                        @elseif($user->email_verified_at)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Unverified
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Articles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $user->articles->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Comments</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $user->comments->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Reputation</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $user->reputation_score ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Management -->
    @can('assign roles')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Role Management</h3>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Current Roles</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                    <div class="flex items-center">
                        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full 
                            @if($role->name === 'admin') bg-red-100 text-red-800
                            @elseif($role->name === 'moderator') bg-yellow-100 text-yellow-800
                            @elseif($role->name === 'editor') bg-blue-100 text-blue-800
                            @elseif($role->name === 'contributor') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($role->name) }}
                        </span>
                        @if($user->roles->count() > 1 && !($role->name === 'admin' && $user->id === Auth::id()))
                        <form method="POST" action="{{ route('admin.users.remove-role', $user) }}" class="inline ml-2">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="role_name" value="{{ e($role->name) }}">
                            <button type="submit" 
                                    class="text-red-500 hover:text-red-700"
                                    onclick="return confirm('Remove {{ addslashes(e($role->name)) }} role from {{ addslashes(e($user->name)) }}?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.assign-role', $user) }}" class="flex items-end space-x-4">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="flex-1">
                    <label for="role_name" class="block text-sm font-medium text-gray-700">Assign Role</label>
                    <select name="role_name" id="role_name" class="form-select mt-1">
                        <option value="">Select a role...</option>
                        @foreach($availableRoles as $role)
                            @if(!$user->hasRole($role->name))
                            <option value="{{ e($role->name) }}">{{ e(ucfirst($role->name)) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-ki-primary">
                    Assign Role
                </button>
            </form>
        </div>
    </div>
    @endcan

    <!-- Ban Management -->
    @can('ban users')
    @if(!$user->can('admin access'))
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Ban Management</h3>
        </div>
        <div class="p-6">
            @if($user->is_banned)
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">User is currently banned</h3>
                            @if($user->ban_reason)
                            <p class="mt-1 text-sm text-red-700">Reason: {{ $user->ban_reason }}</p>
                            @endif
                            @if($user->banned_until)
                            <p class="mt-1 text-sm text-red-700">Until: {{ $user->banned_until->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                    @csrf
                    <button type="submit" 
                            class="btn-ki-primary bg-green-600 hover:bg-green-700"
                            onclick="return confirm('Unban {{ addslashes(e($user->name)) }}?')">
                        Unban User
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                    @csrf
                    <button type="submit" 
                            class="btn-ki-primary bg-red-600 hover:bg-red-700"
                            onclick="return confirm('Ban {{ addslashes(e($user->name)) }}?')">
                        Ban User
                    </button>
                </form>
            @endif
        </div>
    </div>
    @endif
    @endcan

    <!-- Delete User -->
    @can('delete users')
    @if(!$user->can('admin access'))
    <div class="bg-white shadow-sm rounded-lg border border-red-200 mb-8">
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <h3 class="text-lg font-medium text-red-900">Danger Zone</h3>
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900">Delete User Account</h4>
                    <p class="mt-1 text-sm text-gray-600">
                        Permanently delete this user account and all associated data. This action cannot be undone.
                        @if($user->articles->count() > 0)
                        <br><strong>Note:</strong> This user has {{ $user->articles->count() }} article(s) that need to be handled.
                        @endif
                    </p>
                </div>
                <div class="ml-6">
                    <a href="{{ route('admin.users.confirm-delete', $user) }}" 
                       class="btn-ki-primary bg-red-600 hover:bg-red-700 focus:ring-red-500">
                        Delete User
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endcan

    <!-- Recent Articles -->
    @if($user->articles->count() > 0)
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Articles</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($user->articles->take(5) as $article)
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900">
                            <a href="{{ route('wiki.articles.show', $article->slug) }}" class="hover:text-indigo-600">
                                {{ $article->title }}
                            </a>
                        </h4>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ Str::limit($article->excerpt, 100) }}
                        </p>
                        <div class="mt-2 flex items-center text-xs text-gray-500 space-x-4">
                            <span>{{ $article->created_at->format('M d, Y') }}</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($article->status === 'published') bg-green-100 text-green-800
                                @elseif($article->status === 'draft') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($article->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection