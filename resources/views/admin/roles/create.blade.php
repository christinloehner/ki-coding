@extends('layouts.app')

@section('title', 'Create Role - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Role</h1>
                <p class="mt-2 text-gray-600">Define a new role and assign permissions</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <i class="fas fa-arrow-left mr-2"></i>Back to Roles
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            
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
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           placeholder="e.g., Content Manager, Support Agent"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                           required>
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
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Permissions -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions</h3>
                <p class="text-sm text-gray-600 mb-6">Select which permissions this role should have. Permissions are grouped by category for easier management.</p>
                
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
                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
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

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.roles.index') }}" 
                   class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-save mr-2"></i>Create Role
                </button>
            </div>
        </form>
    </div>
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
});
</script>
@endsection