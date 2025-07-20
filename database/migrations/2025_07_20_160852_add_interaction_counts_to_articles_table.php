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
        Schema::table('articles', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('articles', 'likes_count')) {
                $table->unsignedInteger('likes_count')->default(0)->after('views_count');
            }
            if (!Schema::hasColumn('articles', 'helpful_votes')) {
                $table->unsignedInteger('helpful_votes')->default(0)->after('likes_count');
            }
            if (!Schema::hasColumn('articles', 'not_helpful_votes')) {
                $table->unsignedInteger('not_helpful_votes')->default(0)->after('helpful_votes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['likes_count', 'helpful_votes', 'not_helpful_votes']);
        });
    }
};
