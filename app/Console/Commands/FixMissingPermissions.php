<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FixMissingPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:fix-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing permissions for role management system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Reset cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->info('Checking and creating missing permissions...');

        $missingPermissions = [
            'assign roles',
            'delete users', 
            'manage user roles',
            'manage permissions',
            'create roles',
            'edit roles',
            'delete roles'
        ];

        foreach ($missingPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if (!$permission) {
                Permission::create(['name' => $permissionName]);
                $this->info("Created permission: {$permissionName}");
            } else {
                $this->line("Permission already exists: {$permissionName}");
            }
        }

        $this->info('Assigning all permissions to admin role...');
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
            $this->info('Admin role now has all permissions.');
        } else {
            $this->error('Admin role not found!');
        }

        $this->info('Assigning assign roles permission to moderator role...');
        $moderatorRole = Role::where('name', 'moderator')->first();
        if ($moderatorRole) {
            $moderatorRole->givePermissionTo('assign roles');
            $this->info('Moderator role now has assign roles permission.');
        }

        $this->info('Done! All missing permissions have been fixed.');
        
        return Command::SUCCESS;
    }
}
