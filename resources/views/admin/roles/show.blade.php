@extends('layouts.app')

@section('title', 'Role Details - Admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Role: {{ e(ucfirst($role->name)) }}</h1>
                <p class="mt-2 text-gray-600">{{ e($role->description ?? 'No description provided') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.roles.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Roles
                </a>
                
                @if(!in_array($role->name, ['admin', 'user']))
                <a href="{{ route('admin.roles.edit', $role) }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-edit mr-2"></i>Edit Role
                </a>
                @endif
                
                <a href="{{ route('admin.roles.permissions', $role) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-key mr-2"></i>Manage Permissions
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Role Information -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Role Information</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1 flex items-center">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center mr-3
                                @if($role->name === 'admin') bg-red-100 text-red-600
                                @elseif($role->name === 'moderator') bg-orange-100 text-orange-600
                                @elseif($role->name === 'editor') bg-blue-100 text-blue-600
                                @elseif($role->name === 'contributor') bg-green-100 text-green-600
                                @else bg-gray-100 text-gray-600
                                @endif">
                                <i class="fas fa-users text-sm"></i>
                            </div>
                            <span class="text-gray-900">{{ e(ucfirst($role->name)) }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <div class="mt-1">
                            @if(in_array($role->name, ['admin', 'user', 'moderator', 'editor', 'contributor']))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    System Role
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Custom Role
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Users Assigned</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $role->users->count() }} {{ $role->users->count() == 1 ? 'user' : 'users' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Permissions</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $role->permissions->count() }} permissions
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created</label>
                        <div class="mt-1 text-gray-900">
                            {{ $role->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                        <div class="mt-1 text-gray-900">
                            {{ $role->updated_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions and Users -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Permissions -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Permissions ({{ $role->permissions->count() }})</h3>
                    <a href="{{ route('admin.roles.permissions', $role) }}" 
                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit Permissions
                    </a>
                </div>
                <div class="px-6 py-4">
                    @if($role->permissions->count() > 0)
                        @php
                            $groupedPermissions = $role->permissions->groupBy(function ($permission) {
                                return explode(' ', $permission->name)[0];
                            });
                        @endphp
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupedPermissions as $category => $permissions)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3 capitalize">
                                    <i class="fas fa-{{ $category === 'view' ? 'eye' : ($category === 'create' ? 'plus' : ($category === 'edit' ? 'edit' : ($category === 'delete' ? 'trash' : 'cog'))) }} mr-2"></i>
                                    {{ ucfirst($category) }} Permissions
                                </h4>
                                <div class="space-y-2">
                                    @foreach($permissions as $permission)
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 text-xs mr-2"></i>
                                        <span class="text-sm text-gray-700">
                                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-key text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-sm font-medium text-gray-900">No permissions assigned</h3>
                            <p class="text-sm text-gray-500 mt-1">This role doesn't have any permissions yet.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.roles.permissions', $role) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                                    <i class="fas fa-key mr-2"></i>Assign Permissions
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Users with this role -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Users with this Role ({{ $role->users->count() }})</h3>
                    <div class="flex space-x-3">
                        <button type="button" onclick="openAddUserModal()" 
                                class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i>Add User
                        </button>
                        <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" 
                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-users mr-1"></i>View All Users
                        </a>
                    </div>
                </div>
                <div class="px-6 py-4">
                    @if($role->users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($role->users->take(10) as $user)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ e($user->name) }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ e($user->email) }}</p>
                                </div>
                                <div class="ml-3 flex space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 text-xs" title="View User">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    @if(!in_array($role->name, ['admin', 'user']) || ($role->name === 'admin' && $user->id !== Auth::id()))
                                    <button type="button" onclick="removeUserFromRole({{ $user->id }}, '{{ addslashes(e($user->name)) }}', '{{ addslashes(e($role->name)) }}')"
                                            class="text-red-600 hover:text-red-800 text-xs" title="Remove from Role">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if($role->users->count() > 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    View all {{ $role->users->count() }} users →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-sm font-medium text-gray-900">No users assigned</h3>
                            <p class="text-sm text-gray-500 mt-1">No users have this role yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add User to Role</h3>
                <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addUserForm" method="POST" action="{{ route('admin.users.assign-role-bulk') }}">
                @csrf
                <input type="hidden" name="role_name" value="{{ e($role->name) }}">
                
                <div class="mb-4">
                    <label for="user_search" class="block text-sm font-medium text-gray-700 mb-2">
                        Search User
                    </label>
                    <input type="text" 
                           id="user_search" 
                           placeholder="Type username or email..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="user_results" class="mt-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md hidden"></div>
                </div>
                
                <input type="hidden" name="user_id" id="selected_user_id">
                <div id="selected_user_info" class="mb-4 p-3 bg-gray-50 rounded-lg hidden">
                    <div class="flex items-center">
                        <div id="selected_user_avatar" class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
                                <span id="selected_user_initials" class="text-xs font-medium text-white"></span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p id="selected_user_name" class="text-sm font-medium text-gray-900"></p>
                            <p id="selected_user_email" class="text-xs text-gray-500"></p>
                        </div>
                        <button type="button" onclick="clearSelectedUser()" class="ml-auto text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddUserModal()" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                            disabled id="addUserSubmit">
                        Add to Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove User Modal -->
<div id="removeUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Remove User from Role</h3>
                <button onclick="closeRemoveUserModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Are you sure you want to remove <strong id="removeUserName"></strong> from the <strong>{{ e($role->name) }}</strong> role?
                </p>
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mr-2"></i>
                        <span class="text-sm text-yellow-800">This action will immediately revoke all permissions associated with this role.</span>
                    </div>
                </div>
            </div>
            
            <form id="removeUserForm" method="POST" action="{{ route('admin.users.remove-role-bulk') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="role_name" value="{{ e($role->name) }}">
                <input type="hidden" name="user_id" id="removeUserId">
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRemoveUserModal()" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Remove from Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add User Modal Functions
function openAddUserModal() {
    document.getElementById('addUserModal').classList.remove('hidden');
    document.getElementById('user_search').focus();
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
    clearSelectedUser();
    document.getElementById('user_search').value = '';
    document.getElementById('user_results').classList.add('hidden');
}

function clearSelectedUser() {
    document.getElementById('selected_user_id').value = '';
    document.getElementById('selected_user_info').classList.add('hidden');
    document.getElementById('addUserSubmit').disabled = true;
}

function selectUser(userId, userName, userEmail) {
    document.getElementById('selected_user_id').value = userId;
    document.getElementById('selected_user_name').textContent = userName;
    document.getElementById('selected_user_email').textContent = userEmail;
    document.getElementById('selected_user_initials').textContent = userName.substring(0, 2).toUpperCase();
    document.getElementById('selected_user_info').classList.remove('hidden');
    document.getElementById('addUserSubmit').disabled = false;
    document.getElementById('user_results').classList.add('hidden');
    document.getElementById('user_search').value = userName;
}

// Remove User Modal Functions
function removeUserFromRole(userId, userName, roleName) {
    document.getElementById('removeUserId').value = userId;
    document.getElementById('removeUserName').textContent = userName;
    document.getElementById('removeUserModal').classList.remove('hidden');
}

function closeRemoveUserModal() {
    document.getElementById('removeUserModal').classList.add('hidden');
}

// User Search
let searchTimeout;
document.getElementById('user_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value;
    
    if (query.length < 2) {
        document.getElementById('user_results').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`/api/wiki/users/search?q=${encodeURIComponent(query)}&exclude_role={{ $role->name }}`)
            .then(response => response.json())
            .then(users => {
                const resultsDiv = document.getElementById('user_results');
                
                if (users.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500">No users found</div>';
                } else {
                    resultsDiv.innerHTML = users.map(user => {
                        const escapedName = user.name.replace(/'/g, '&#39;').replace(/"/g, '&quot;');
                        const escapedEmail = user.email.replace(/'/g, '&#39;').replace(/"/g, '&quot;');
                        const escapedNameDisplay = user.name.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        const escapedEmailDisplay = user.email.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        
                        return `
                        <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                             onclick="selectUser(${user.id}, '${escapedName}', '${escapedEmail}')">
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-white">${escapedNameDisplay.substring(0, 2).toUpperCase()}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">${escapedNameDisplay}</div>
                                    <div class="text-xs text-gray-500">${escapedEmailDisplay}</div>
                                </div>
                            </div>
                        </div>
                        `;
                    }).join('');
                }
                
                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Search error:', error);
                document.getElementById('user_results').innerHTML = '<div class="p-3 text-sm text-red-500">Search failed</div>';
                document.getElementById('user_results').classList.remove('hidden');
            });
    }, 300);
});

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const addModal = document.getElementById('addUserModal');
    const removeModal = document.getElementById('removeUserModal');
    
    if (event.target === addModal) {
        closeAddUserModal();
    }
    if (event.target === removeModal) {
        closeRemoveUserModal();
    }
});
</script>
@endsection