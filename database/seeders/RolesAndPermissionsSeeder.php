<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Article permissions
            'view articles',
            'create articles',
            'edit own articles',
            'edit all articles',
            'delete own articles',
            'delete all articles',
            'publish articles',
            'unpublish articles',
            'feature articles',
            
            // Category permissions
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Comment permissions
            'view comments',
            'create comments',
            'edit own comments',
            'edit all comments',
            'delete own comments',
            'delete all comments',
            'approve comments',
            'reject comments',
            'pin comments',
            
            // Tag permissions
            'view tags',
            'create tags',
            'edit tags',
            'delete tags',
            
            // User permissions
            'view users',
            'edit own profile',
            'edit all profiles',
            'ban users',
            'unban users',
            'delete users',
            'invite users',
            'view invitations',
            'manage user roles',
            'assign roles',
            'manage roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage permissions',
            
            // Admin permissions
            'access admin panel',
            'view logs',
            'manage system settings',
            'manage backups',
            'view analytics',
            
            // Moderation permissions
            'moderate content',
            'view reports',
            'handle reports',
            'manage spam',
            
            // API permissions
            'use api',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // User role - basic permissions
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'view articles',
            'view categories',
            'view comments',
            'create comments',
            'edit own comments',
            'delete own comments',
            'view tags',
            'edit own profile',
        ]);

        // Contributor role - can create articles
        $contributorRole = Role::firstOrCreate(['name' => 'contributor']);
        $contributorRole->givePermissionTo([
            'view articles',
            'create articles',
            'edit own articles',
            'delete own articles',
            'view categories',
            'view comments',
            'create comments',
            'edit own comments',
            'delete own comments',
            'view tags',
            'create tags',
            'edit own profile',
        ]);

        // Editor role - can edit articles and moderate comments
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'view articles',
            'create articles',
            'edit own articles',
            'edit all articles',
            'delete own articles',
            'publish articles',
            'unpublish articles',
            'feature articles',
            'view categories',
            'create categories',
            'edit categories',
            'view comments',
            'create comments',
            'edit own comments',
            'edit all comments',
            'delete own comments',
            'delete all comments',
            'approve comments',
            'reject comments',
            'pin comments',
            'view tags',
            'create tags',
            'edit tags',
            'view users',
            'edit own profile',
            'moderate content',
            'view reports',
            'handle reports',
        ]);

        // Moderator role - can moderate all content and manage users
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $moderatorRole->givePermissionTo([
            'view articles',
            'create articles',
            'edit own articles',
            'edit all articles',
            'delete own articles',
            'delete all articles',
            'publish articles',
            'unpublish articles',
            'feature articles',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view comments',
            'create comments',
            'edit own comments',
            'edit all comments',
            'delete own comments',
            'delete all comments',
            'approve comments',
            'reject comments',
            'pin comments',
            'view tags',
            'create tags',
            'edit tags',
            'delete tags',
            'view users',
            'edit own profile',
            'edit all profiles',
            'ban users',
            'unban users',
            'invite users',
            'view invitations',
            'manage user roles',
            'assign roles',
            'moderate content',
            'view reports',
            'handle reports',
            'manage spam',
            'access admin panel',
        ]);

        // Admin role - all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles and permissions created successfully!');
    }
}
