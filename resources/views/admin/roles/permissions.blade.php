@extends('layouts.app')

@section('title', 'Manage Permissions - ' . ucfirst($role->name))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manage Permissions</h1>
                <p class="mt-2 text-gray-600">
                    Fine-tune permissions for role: <span class="font-semibold text-gray-900">{{ ucfirst($role->name) }}</span>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.roles.edit', $role) }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-edit mr-2"></i>Edit Role
                </a>
                <a href="{{ route('admin.roles.show', $role) }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Role
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
                    <strong>System Role:</strong> 
                    @if($role->name === 'admin')
                        Admin roles have all permissions automatically and cannot be modified.
                    @else
                        This is a system role. Permissions are managed carefully to maintain system security.
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Role Info -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 sticky top-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Role Overview</h3>
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
                            <span class="text-gray-900">{{ ucfirst($role->name) }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Users</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $role->users->count() }} users
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Permissions</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $role->permissions->count() }} / {{ $allPermissions->count() }}
                            </span>
                        </div>
                    </div>

                    <!-- Permission Summary -->
                    <div class="pt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permission Summary</label>
                        <div class="space-y-2">
                            @foreach($permissionsByCategory as $category => $permissions)
                                @php
                                    $assigned = $role->permissions->whereIn('name', $permissions->pluck('name'))->count();
                                    $total = $permissions->count();
                                    $percentage = $total > 0 ? round(($assigned / $total) * 100) : 0;
                                @endphp
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600 capitalize">{{ $category }}</span>
                                    <span class="text-gray-900">{{ $assigned }}/{{ $total }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Permissions -->
        <div class="lg:col-span-3">
            <form method="POST" action="{{ route('admin.roles.update-permissions', $role) }}" id="permissions-form">
                @csrf
                
                <!-- Global Actions -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                            <div class="flex space-x-2">
                                <button type="button" id="select-all" 
                                        class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                                    <i class="fas fa-check-double mr-1"></i>Select All
                                </button>
                                <button type="button" id="deselect-all" 
                                        class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                                    <i class="fas fa-times mr-1"></i>Deselect All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($permissionsByCategory as $category => $permissions)
                            <button type="button" class="category-toggle px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm text-gray-700" 
                                    data-category="{{ $category }}">
                                <i class="fas fa-{{ $category === 'view' ? 'eye' : ($category === 'create' ? 'plus' : ($category === 'edit' ? 'edit' : ($category === 'delete' ? 'trash' : 'cog'))) }} mr-1"></i>
                                Toggle {{ ucfirst($category) }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Permissions by Category -->
                <div class="space-y-6">
                    @foreach($permissionsByCategory as $category => $permissions)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 capitalize">
                                    <i class="fas fa-{{ $category === 'view' ? 'eye' : ($category === 'create' ? 'plus' : ($category === 'edit' ? 'edit' : ($category === 'delete' ? 'trash' : 'cog'))) }} mr-2"></i>
                                    {{ ucfirst($category) }} Permissions
                                    <span class="text-sm font-normal text-gray-500">({{ $permissions->count() }})</span>
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           id="category_all_{{ $category }}" 
                                           class="category-checkbox rounded border-gray-300 text-indigo-600 shadow-sm"
                                           data-category="{{ $category }}">
                                    <label for="category_all_{{ $category }}" class="text-sm text-gray-600">Select All</label>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($permissions as $permission)
                                <div class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->name }}" 
                                           id="permission_{{ $permission->id }}"
                                           class="permission-checkbox mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           data-category="{{ $category }}"
                                           {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <div class="ml-3 flex-1">
                                        <label for="permission_{{ $permission->id }}" class="block text-sm font-medium text-gray-900 cursor-pointer">
                                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                        </label>
                                        @if($permission->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ $permission->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Save Actions -->
                <div class="mt-8 bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span id="selected-count">{{ $role->permissions->count() }}</span> of {{ $allPermissions->count() }} permissions selected
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.roles.show', $role) }}" 
                               class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i>Save Permissions
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update selected count
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        document.getElementById('selected-count').textContent = selectedCount;
    }

    // Global select all
    document.getElementById('select-all').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateCategoryCheckboxes();
        updateSelectedCount();
    });

    // Global deselect all
    document.getElementById('deselect-all').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateCategoryCheckboxes();
        updateSelectedCount();
    });

    // Category toggles
    document.querySelectorAll('.category-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            const categoryCheckboxes = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox`);
            const checkedCount = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox:checked`).length;
            const shouldCheck = checkedCount === 0;
            
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = shouldCheck;
            });
            
            updateCategoryCheckboxes();
            updateSelectedCount();
        });
    });

    // Category select all checkboxes
    document.querySelectorAll('.category-checkbox').forEach(categoryCheckbox => {
        categoryCheckbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const permissionCheckboxes = document.querySelectorAll(`input[data-category="${category}"].permission-checkbox`);
            
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            updateSelectedCount();
        });
    });

    // Individual permission checkboxes
    document.querySelectorAll('.permission-checkbox').forEach(permissionCheckbox => {
        permissionCheckbox.addEventListener('change', function() {
            updateCategoryCheckboxes();
            updateSelectedCount();
        });
    });

    // Update category checkboxes based on individual selections
    function updateCategoryCheckboxes() {
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
    }

    // Initialize category checkboxes
    updateCategoryCheckboxes();
    updateSelectedCount();

    // Form submission confirmation
    document.getElementById('permissions-form').addEventListener('submit', function(e) {
        const selectedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        if (selectedCount === 0) {
            if (!confirm('Du gibst dieser Rolle keine Berechtigungen. Bist du sicher?')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection