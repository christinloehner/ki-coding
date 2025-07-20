<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixModeratorsPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:fix-moderators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove dangerous permissions from moderator role - SECURITY FIX';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->error('🚨 CRITICAL SECURITY FIX: Removing dangerous permissions from moderator role...');
        
        $moderatorRole = \Spatie\Permission\Models\Role::where('name', 'moderator')->first();
        if (!$moderatorRole) {
            $this->error('Moderator role not found!');
            return;
        }
        
        // These permissions should ONLY be available to admin role
        $dangerousPermissions = [
            'assign roles',
            'delete users', 
            'manage roles',
            'create roles',
            'edit roles', 
            'delete roles',
            'manage permissions',
            'manage user roles'
        ];
        
        foreach ($dangerousPermissions as $permission) {
            if ($moderatorRole->hasPermissionTo($permission)) {
                $moderatorRole->revokePermissionTo($permission);
                $this->error("🔒 REMOVED: {$permission} from moderator role");
            } else {
                $this->line("✓ Safe: {$permission} not assigned to moderator");
            }
        }
        
        $this->info('✅ Moderator role permissions secured!');
        $this->warn('⚠️  Only admin role should have user/role management permissions!');
        
        return 0;
    }
}
