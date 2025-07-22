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
        if (!Schema::hasTable('user_activities')) {
            Schema::create('user_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type'); // article_created, comment_posted, article_liked, article_bookmarked, etc.
                $table->text('description');
                $table->string('subject_type')->nullable(); // App\Models\Article, App\Models\Comment, etc.
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->json('properties')->nullable(); // Additional data
                $table->timestamps();
                
                $table->index(['user_id', 'created_at']);
                $table->index(['subject_type', 'subject_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
