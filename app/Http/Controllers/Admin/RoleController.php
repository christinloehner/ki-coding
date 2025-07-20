<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

/**
 * Controller für Role Management im Admin-Bereich
 * Verwaltet Rollen und deren Permissions
 */
class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        Gate::authorize('manage roles');
        
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->paginate(20);
            
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        Gate::authorize('manage roles');
        
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0]; // Group by first word (e.g., 'view', 'create', 'edit')
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        Gate::authorize('manage roles');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'description' => $request->description
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role "' . $role->name . '" has been created successfully.');
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        Gate::authorize('manage roles');
        
        $role->load('permissions', 'users');
        
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        Gate::authorize('manage roles');
        
        // Prevent editing system roles
        if (in_array($role->name, ['admin', 'user'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be edited.');
        }
        
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0];
        });
        
        $role->load('permissions');
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize('manage roles');
        
        // Prevent editing system roles
        if (in_array($role->name, ['admin', 'user'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be modified.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role "' . $role->name . '" has been updated successfully.');
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        Gate::authorize('manage roles');
        
        // Prevent deleting system roles
        if (in_array($role->name, ['admin', 'user', 'moderator', 'editor', 'contributor'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        // Check if role has users assigned
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role "' . $role->name . '" because it has users assigned.');
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role "' . $roleName . '" has been deleted successfully.');
    }

    /**
     * Show form to assign permissions to role
     */
    public function permissions(Role $role)
    {
        Gate::authorize('manage roles');
        
        $permissionsByCategory = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0];
        });
        
        $allPermissions = Permission::all();
        
        $role->load('permissions');
        
        return view('admin.roles.permissions', compact('role', 'permissionsByCategory', 'allPermissions'));
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request, Role $role)
    {
        Gate::authorize('manage roles');
        
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.show', $role)
            ->with('success', 'Permissions for role "' . $role->name . '" have been updated successfully.');
    }
}