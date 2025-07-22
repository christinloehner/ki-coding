<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Removes all direct user permissions from the model_has_permissions table
     * to ensure that permissions are ONLY granted via roles, never directly to users.
     */
    public function up(): void
    {
        // Log current state for debugging
        $directPermissionsCount = DB::table('model_has_permissions')
            ->where('model_type', 'App\\Models\\User')
            ->count();
            
        if ($directPermissionsCount > 0) {
            \Log::info("Removing {$directPermissionsCount} direct user permissions to enforce role-only permission system");
            
            // Get all affected users for logging
            $affectedUsers = DB::table('model_has_permissions')
                ->where('model_type', 'App\\Models\\User')
                ->join('users', 'model_has_permissions.model_id', '=', 'users.id')
                ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
                ->select('users.name', 'users.email', 'permissions.name as permission_name')
                ->get();
                
            foreach ($affectedUsers as $user) {
                \Log::info("Removing direct permission '{$user->permission_name}' from user '{$user->name}' ({$user->email})");
            }
            
            // Remove all direct user permissions
            DB::table('model_has_permissions')
                ->where('model_type', 'App\\Models\\User')
                ->delete();
                
            \Log::info("Successfully removed all direct user permissions. Permissions now managed via roles only.");
        } else {
            \Log::info("No direct user permissions found. System already uses role-only permission management.");
        }
        
        // Clear permission cache to ensure changes take effect immediately
        if (class_exists(\Spatie\Permission\PermissionServiceProvider::class)) {
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Note: This migration is intentionally NOT reversible as restoring
     * direct user permissions would go against the role-only permission policy.
     * If needed, permissions should be reassigned via proper role management.
     */
    public function down(): void
    {
        // This migration is not reversible by design
        // Direct user permissions should never be restored
        \Log::warning("Cannot reverse removal of direct user permissions - this is by design. Use role management instead.");
    }
};