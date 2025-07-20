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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->text('content');
            $table->text('rendered_content');
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->json('meta_data')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['article_id', 'status', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['parent_id', 'created_at']);
            $table->index('status');
            $table->index('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
