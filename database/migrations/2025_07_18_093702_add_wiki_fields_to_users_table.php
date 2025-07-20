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
            $table->string('username')->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('website')->nullable()->after('avatar');
            $table->string('location')->nullable()->after('website');
            $table->boolean('is_active')->default(true)->after('location');
            $table->boolean('is_banned')->default(false)->after('is_active');
            $table->timestamp('banned_until')->nullable()->after('is_banned');
            $table->text('ban_reason')->nullable()->after('banned_until');
            $table->integer('reputation_score')->default(0)->after('ban_reason');
            $table->integer('articles_count')->default(0)->after('reputation_score');
            $table->integer('comments_count')->default(0)->after('articles_count');
            $table->timestamp('last_activity_at')->nullable()->after('comments_count');
            $table->json('preferences')->nullable()->after('last_activity_at');
            $table->json('notification_settings')->nullable()->after('preferences');
            $table->boolean('email_notifications')->default(true)->after('notification_settings');
            $table->string('invitation_token')->nullable()->after('email_notifications');
            $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null')->after('invitation_token');
            $table->timestamp('invited_at')->nullable()->after('invited_by');
            
            // Indexes
            $table->index('username');
            $table->index('is_active');
            $table->index('is_banned');
            $table->index('reputation_score');
            $table->index('last_activity_at');
            $table->index('invitation_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'bio',
                'avatar',
                'website',
                'location',
                'is_active',
                'is_banned',
                'banned_until',
                'ban_reason',
                'reputation_score',
                'articles_count',
                'comments_count',
                'last_activity_at',
                'preferences',
                'notification_settings',
                'email_notifications',
                'invitation_token',
                'invited_by',
                'invited_at'
            ]);
        });
    }
};
