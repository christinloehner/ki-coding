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
        // Only create table if it doesn't exist yet
        if (!Schema::hasTable('article_likes')) {
            Schema::create('article_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['article_id', 'user_id']);
                $table->index(['article_id']);
                $table->index(['user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_likes');
    }
};
