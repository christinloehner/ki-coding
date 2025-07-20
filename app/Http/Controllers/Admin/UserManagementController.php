<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

class UserManagementController extends Controller
{
    // Middleware is handled in routes - no constructor needed

    /**
     * Display a listing of all users (Admin/Moderator only)
     */
    public function index(): View
    {
        Gate::authorize('view users');
        
        $users = User::with(['roles', 'permissions'])
            ->paginate(20);
            
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show user details and role management
     */
    public function show(User $user): View
    {
        Gate::authorize('view users');
        
        $user->load(['roles', 'permissions', 'articles']);
        $availableRoles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.users.show', compact('user', 'availableRoles', 'permissions'));
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user = null): RedirectResponse
    {
        // CRITICAL SECURITY: Only admin can assign roles!
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Nur Administratoren können Rollen zuweisen.');
        }
        
        // Handle both route parameter and form data
        if (!$user && $request->has('user_id')) {
            $user = User::findOrFail($request->user_id);
        }
        
        $request->validate([
            'role_name' => 'required|string|exists:roles,name'
        ]);
        
        $roleName = $request->input('role_name');
        $role = Role::findByName($roleName);
        
        // Prevent assigning admin role to non-admins without proper checks
        if ($role->name === 'admin' && !Auth::user()->hasRole('admin')) {
            return back()->with('error', 'Only administrators can assign admin role.');
        }

        // Check if user already has this role
        if ($user->hasRole($role->name)) {
            return back()->with('error', "{$user->name} already has the {$role->name} role.");
        }

        $user->assignRole($role);
        
        return back()->with('success', "{$user->name} has been assigned the {$role->name} role.");
    }

    /**
     * Remove role from user
     */
    public function removeRole(Request $request, User $user = null): RedirectResponse
    {
        // CRITICAL SECURITY: Only admin can remove roles!
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Nur Administratoren können Rollen entfernen.');
        }
        
        // Handle both route parameter and form data
        if (!$user && $request->has('user_id')) {
            $user = User::findOrFail($request->user_id);
        }
        
        $request->validate([
            'role_name' => 'required|string|exists:roles,name'
        ]);
        
        $roleName = $request->input('role_name');
        $role = Role::findByName($roleName);
        
        // Prevent removing admin role from yourself
        if ($role->name === 'admin' && $user->id === Auth::id()) {
            return back()->with('error', 'You cannot remove admin role from yourself.');
        }

        // Prevent non-admins from removing admin role
        if ($role->name === 'admin' && !Auth::user()->hasRole('admin')) {
            return back()->with('error', 'Only administrators can remove admin role.');
        }

        // Check if user has this role
        if (!$user->hasRole($role->name)) {
            return back()->with('error', "{$user->name} does not have the {$role->name} role.");
        }
        
        $user->removeRole($role);
        
        return back()->with('success', "{$user->name} has been removed from the {$role->name} role.");
    }

    /**
     * Ban user
     */
    public function ban(User $user): RedirectResponse
    {
        Gate::authorize('ban users');
        
        // Prevent banning admins
        if ($user->hasRole('admin')) {
            return back()->withErrors(['user' => 'Cannot ban administrator users.']);
        }
        
        $user->update([
            'is_banned' => true,
            'banned_until' => now()->addYears(10), // Long ban
            'ban_reason' => 'Banned by administrator'
        ]);
        
        return back()->with('success', "{$user->name} has been banned");
    }

    /**
     * Unban user
     */
    public function unban(User $user): RedirectResponse
    {
        Gate::authorize('unban users');
        
        $user->update([
            'is_banned' => false,
            'banned_until' => null,
            'ban_reason' => null
        ]);
        
        return back()->with('success', "{$user->name} has been unbanned");
    }

    /**
     * Show delete confirmation with article reassignment options
     */
    public function confirmDelete(User $user): View
    {
        Gate::authorize('delete users');
        
        // Prevent deleting admins
        if ($user->hasRole('admin')) {
            abort(403, 'Cannot delete administrator users.');
        }
        
        // Get all users except the one being deleted for reassignment
        $availableUsers = User::where('id', '!=', $user->id)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'moderator', 'editor', 'contributor']);
            })
            ->orderBy('name')
            ->get();
            
        $articlesCount = $user->articles()->count();
        
        return view('admin.users.delete-confirm', compact('user', 'availableUsers', 'articlesCount'));
    }

    /**
     * Delete user with article handling
     */
    public function delete(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('delete users');
        
        // Prevent deleting admins
        if ($user->hasRole('admin')) {
            return back()->withErrors(['user' => 'Cannot delete administrator users.']);
        }
        
        $request->validate([
            'article_action' => 'required|in:delete,reassign',
            'reassign_to' => 'required_if:article_action,reassign|exists:users,id'
        ]);
        
        $userName = $user->name;
        $articlesCount = $user->articles()->count();
        
        if ($request->article_action === 'reassign' && $articlesCount > 0) {
            // Reassign articles to another user
            $targetUser = User::findOrFail($request->reassign_to);
            $user->articles()->update(['user_id' => $targetUser->id]);
            
            $message = "User '{$userName}' deleted and {$articlesCount} articles reassigned to {$targetUser->name}";
        } elseif ($request->article_action === 'delete' && $articlesCount > 0) {
            // Delete all articles
            $user->articles()->delete();
            
            $message = "User '{$userName}' and {$articlesCount} articles deleted";
        } else {
            $message = "User '{$userName}' deleted";
        }
        
        // Delete the user
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', $message);
    }

}