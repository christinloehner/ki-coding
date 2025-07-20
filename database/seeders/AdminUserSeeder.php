<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin credentials from env
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');
        $adminName = env('ADMIN_NAME', 'System Administrator');
        
        if (!$adminEmail || !$adminPassword) {
            $this->command->error('Admin credentials not found in .env file!');
            $this->command->error('Please set ADMIN_EMAIL and ADMIN_PASSWORD in your .env file.');
            return;
        }
        
        // Check if admin user already exists
        $existingAdmin = User::where('email', $adminEmail)->first();
        if ($existingAdmin) {
            // Update existing admin with current env values
            $existingAdmin->update([
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
            ]);
            
            // Ensure admin role
            if (!$existingAdmin->hasRole('admin')) {
                $existingAdmin->assignRole('admin');
            }
            
            $this->command->info("Admin user updated: {$adminEmail}");
        } else {
            // Create new admin user
            $adminUser = User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
            ]);
            
            // Ensure admin role exists
            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            
            // Assign admin role
            $adminUser->assignRole('admin');
            
            $this->command->info("Admin user created: {$adminEmail}");
        }
    }
}
