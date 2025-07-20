<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:update-permissions {user_id?}';

    /**
     * The console command description.
     */
    protected $description = 'Update user permissions to include publish articles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $users = User::where('id', $userId)->get();
        } else {
            $users = User::all();
        }
        
        $requiredPermissions = [
            'create articles',
            'edit own articles', 
            'delete own articles',
            'publish articles',
            'create comments',
            'edit own comments',
            'delete own comments',
            'view articles',
            'view categories',
            'view tags',
            'view comments',
            'edit own profile'
        ];
        
        foreach ($users as $user) {
            $this->info("Updating permissions for user: {$user->name} (ID: {$user->id})");
            
            // Assign contributor role if not already assigned
            if (!$user->hasRole('contributor')) {
                $user->assignRole('contributor');
                $this->info("  - Assigned 'contributor' role");
            }
            
            // Check and assign missing permissions
            foreach ($requiredPermissions as $permission) {
                if (!$user->hasPermissionTo($permission)) {
                    $user->givePermissionTo($permission);
                    $this->info("  - Added permission: {$permission}");
                } else {
                    $this->line("  - Already has permission: {$permission}");
                }
            }
        }
        
        $this->info('User permissions updated successfully!');
        return 0;
    }
}
