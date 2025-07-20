<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only add fields that don't exist yet (bio, avatar, website, location already exist)
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->date('birthday')->nullable();
            
            // Social media links
            $table->string('github_username')->nullable();
            $table->string('twitter_username')->nullable();
            $table->string('linkedin_username')->nullable();
            $table->string('instagram_username')->nullable();
            
            // Privacy settings - JSON field for flexible privacy controls
            $table->json('privacy_settings')->nullable();
            
            // Profile completion and stats
            $table->boolean('profile_completed')->default(false);
            $table->timestamp('last_profile_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'job_title',
                'company',
                'birthday',
                'github_username',
                'twitter_username',
                'linkedin_username',
                'instagram_username',
                'privacy_settings',
                'profile_completed',
                'last_profile_update'
            ]);
        });
    }
};
