@extends('layouts.app')

@section('title', 'Role Management - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Role Management</h1>
                <p class="mt-2 text-gray-600">Manage user roles and their permissions</p>
            </div>
            @can('create roles')
            <a href="{{ route('admin.roles.create') }}" 
               class="btn-ki-primary">
                <i class="fas fa-plus mr-2"></i>Create New Role
            </a>
            @endcan
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-primary-500 mr-3"></i>
                <span class="text-primary-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                <span class="text-red-800">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Roles Table -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 admin-table-container">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">All Roles ({{ $roles->total() }})</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Users
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permissions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center
                                        @if($role->name === 'admin') bg-red-100 text-red-600
                                        @elseif($role->name === 'moderator') bg-orange-100 text-orange-600
                                        @elseif($role->name === 'editor') bg-blue-100 text-blue-600
                                        @elseif($role->name === 'contributor') bg-green-100 text-green-600
                                        @else bg-gray-100 text-gray-600
                                        @endif">
                                        <i class="fas fa-users text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ ucfirst($role->name) }}
                                    </div>
                                    @if(in_array($role->name, ['admin', 'user', 'moderator', 'editor', 'contributor']))
                                        <div class="text-xs text-gray-500">System Role</div>
                                    @else
                                        <div class="text-xs text-blue-600">Custom Role</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                {{ $role->description ?? 'No description provided' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($role->users_count > 0) bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-800 @endif">
                                {{ $role->users_count }} {{ $role->users_count == 1 ? 'user' : 'users' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $role->permissions->count() }} permissions
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $role->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.roles.show', $role) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View Role">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @can('manage permissions')
                                <a href="{{ route('admin.roles.permissions', $role) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Manage Permissions">
                                    <i class="fas fa-key"></i>
                                </a>
                                @endcan
                                
                                @can('edit roles')
                                @if(!in_array($role->name, ['admin', 'user']))
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="text-green-600 hover:text-green-900" title="Edit Role">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @endcan
                                
                                @can('delete roles')
                                @if(!in_array($role->name, ['admin', 'user', 'moderator', 'editor', 'contributor']))
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
                                      class="inline" onsubmit="return confirm('Rolle wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete Role">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users mx-auto h-12 w-12 text-gray-400 text-4xl mb-4"></i>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No roles found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new role.</p>
                                <div class="mt-4">
                                    <a href="{{ route('admin.roles.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        <i class="fas fa-plus mr-2"></i>Create Role
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($roles->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $roles->links() }}
        </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
            <div class="text-blue-800">
                <h4 class="font-medium">Role Management Info</h4>
                <ul class="mt-2 text-sm space-y-1">
                    <li>• System roles (admin, user, moderator, editor, contributor) cannot be deleted</li>
                    <li>• Admin and user roles cannot be edited to maintain system integrity</li>
                    <li>• Roles with assigned users cannot be deleted</li>
                    <li>• Use permissions to fine-tune what each role can do</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection