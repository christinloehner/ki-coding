@extends('layouts.app')

@section('title', 'Edit Role - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Role: {{ ucfirst($role->name) }}</h1>
                <p class="mt-2 text-gray-600">Update role information and permissions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.roles.show', $role) }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-eye mr-2"></i>View Role
                </a>
                <a href="{{ route('admin.roles.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Roles
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                <span class="text-red-800 font-medium">Please fix the following errors:</span>
            </div>
            <ul class="text-red-700 text-sm list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- System Role Warning -->
    @if(in_array($role->name, ['admin', 'user']))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                <div class="text-yellow-800">
                    <strong>System Role:</strong> This is a system role. Only the description can be modified to maintain system integrity.
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
            </div>
            
            <div class="px-6 py-4 space-y-6">
                <!-- Role Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    @if(in_array($role->name, ['admin', 'user']))
                        <input type="text" 
                               value="{{ ucfirst($role->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-500"
                               disabled>
                        <p class="mt-1 text-sm text-gray-500">System role names cannot be changed</p>
                        <input type="hidden" name="name" value="{{ $role->name }}">
                    @else
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $role->name) }}"
                               placeholder="e.g., Content Manager, Support Agent"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                               required>
                    @endif
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              placeholder="Describe the purpose and responsibilities of this role..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Role Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="text-gray-900 ml-2">{{ $role->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Users:</span>
                            <span class="text-gray-900 ml-2">{{ $role->users->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Permissions:</span>
                            <span class="text-gray-900 ml-2">{{ $role->permissions->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            @if(!in_array($role->name, ['admin', 'user']))
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Permissions</h3>
                    <a href="{{ route('admin.roles.permissions', $role) }}" 
                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        <i class="fas fa-key mr-1"></i>Advanced Permission Management
                    </a>
                </div>
                <p class="text-sm text-gray-600 mb-6">Select which permissions this role should have. For detailed permission management, use the dedicated permissions page.</p>
                
                <div class="space-y-6">
                    @foreach($permissions as $category => $categoryPermissions)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900 capitalize">
                                <i class="fas fa-{{ $category === 'view' ? 'eye' : ($category === 'create' ? 'plus' : ($category === 'edit' ? 'edit' : ($category === 'delete' ? 'trash' : 'cog'))) }} mr-2"></i>
                                {{ ucfirst($category) }} Permissions
                            </h4>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" 
                                       id="select_all_{{ $category }}" 
                                       class="category-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       data-category="{{ $category }}">
                                <label for="select_all_{{ $category }}" class="text-xs text-gray-600">Select All</label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($categoryPermissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->name }}" 
                                       id="permission_{{ $permission->id }}"
                                       class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       data-category="{{ $category }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @error('permissions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @else
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-400 mr-3"></i>
                        <div class="text-blue-800">
                            <strong>System Role Permissions:</strong> 
                            @if($role->name === 'admin')
                                Admin roles have all permissions automatically. Permissions cannot be modified.
                            @else
                                User role permissions are managed separately to maintain system security.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.roles.show', $role) }}" 
                   class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-save mr-2"></i>Update Role
                </button>
            </div>
        </form>
    </div>

    <!-- Users with this Role -->
    @if($role->users->count() > 0)
    <div class="mt-8 bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Users with this Role ({{ $role->users->count() }})</h3>
            <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" 
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                <i class="fas fa-users mr-1"></i>View All Users
            </a>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($role->users->take(9) as $user)
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
                    <div class="ml-3">
                        <a href="{{ route('admin.users.show', $user) }}" 
                           class="text-indigo-600 hover:text-indigo-800 text-xs">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($role->users->count() > 9)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" 
                       class="text-indigo-600 hover:text-indigo-800 text-sm">
                        View all {{ $role->users->count() }} users →
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle "Select All" checkboxes for each category
    document.querySelectorAll('.category-checkbox').forEach(categoryCheckbox => {
        categoryCheckbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const permissionCheckboxes = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox`);
            
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    });

    // Update category checkbox when individual permissions change
    document.querySelectorAll('.permission-checkbox').forEach(permissionCheckbox => {
        permissionCheckbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const categoryCheckbox = document.querySelector(`input[data-category="${category}"].category-checkbox`);
            const permissionCheckboxes = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox`);
            
            const checkedCount = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox:checked`).length;
            const totalCount = permissionCheckboxes.length;
            
            if (checkedCount === 0) {
                categoryCheckbox.checked = false;
                categoryCheckbox.indeterminate = false;
            } else if (checkedCount === totalCount) {
                categoryCheckbox.checked = true;
                categoryCheckbox.indeterminate = false;
            } else {
                categoryCheckbox.checked = false;
                categoryCheckbox.indeterminate = true;
            }
        });
    });

    // Set initial state for category checkboxes
    document.querySelectorAll('.category-checkbox').forEach(categoryCheckbox => {
        const category = categoryCheckbox.dataset.category;
        const permissionCheckboxes = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox`);
        const checkedCount = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox:checked`).length;
        const totalCount = permissionCheckboxes.length;
        
        if (checkedCount === 0) {
            categoryCheckbox.checked = false;
            categoryCheckbox.indeterminate = false;
        } else if (checkedCount === totalCount) {
            categoryCheckbox.checked = true;
            categoryCheckbox.indeterminate = false;
        } else {
            categoryCheckbox.checked = false;
            categoryCheckbox.indeterminate = true;
        }
    });
});
</script>
@endsection