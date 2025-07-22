<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

/**
 * Service to enforce role-only permission management.
 * 
 * This service ensures that permissions are ONLY granted via roles,
 * never directly to users. This maintains a clean and manageable
 * permission system where all access control is role-based.
 */
class RoleOnlyPermissionService
{
    /**
     * Remove all direct user permissions from the system.
     * 
     * @return array Statistics about the cleanup operation
     */
    public static function removeAllDirectUserPermissions(): array
    {
        $removedCount = 0;
        $affectedUsers = [];
        
        // Get all users with direct permissions
        $usersWithDirectPermissions = User::whereHas('permissions')->get();
        
        foreach ($usersWithDirectPermissions as $user) {
            $directPermissions = $user->permissions;
            
            if ($directPermissions->count() > 0) {
                $affectedUsers[] = [
                    'user' => $user->name,
                    'email' => $user->email,
                    'permissions_removed' => $directPermissions->pluck('name')->toArray()
                ];
                
                // Remove all direct permissions from this user
                $user->permissions()->detach();
                $removedCount += $directPermissions->count();
                
                Log::info("Removed {$directPermissions->count()} direct permissions from user: {$user->name}");
            }
        }
        
        // Clear permission cache
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
        Log::info("Total direct user permissions removed: {$removedCount}");
        
        return [
            'total_removed' => $removedCount,
            'affected_users' => $affectedUsers
        ];
    }
    
    /**
     * Validate that a user has no direct permissions (only role-based).
     * 
     * @param User $user
     * @return bool True if user has only role-based permissions
     */
    public static function validateRoleOnlyPermissions(User $user): bool
    {
        $directPermissions = $user->permissions;
        
        if ($directPermissions->count() > 0) {
            Log::warning("User {$user->name} ({$user->email}) has direct permissions, which violates role-only policy", [
                'direct_permissions' => $directPermissions->pluck('name')->toArray()
            ]);
            return false;
        }
        
        return true;
    }
    
    /**
     * Get all users who have direct permissions (should be empty in a clean system).
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUsersWithDirectPermissions()
    {
        return User::whereHas('permissions')->with('permissions')->get();
    }
    
    /**
     * Ensure a user only has permissions via roles by cleaning up any direct permissions.
     * 
     * @param User $user
     * @return array Removed permissions
     */
    public static function enforceRoleOnlyForUser(User $user): array
    {
        $directPermissions = $user->permissions;
        $removedPermissions = $directPermissions->pluck('name')->toArray();
        
        if ($directPermissions->count() > 0) {
            $user->permissions()->detach();
            
            Log::info("Enforced role-only permissions for user {$user->name}, removed direct permissions", [
                'removed_permissions' => $removedPermissions
            ]);
        }
        
        return $removedPermissions;
    }
}