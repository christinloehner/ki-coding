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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content'); // Markdown content
            $table->longText('rendered_content'); // HTML content
            $table->text('excerpt')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['draft', 'pending_review', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->json('meta_data')->nullable(); // SEO and custom meta
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->integer('reading_time')->nullable(); // in minutes
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'published_at']);
            $table->index(['user_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index('slug');
            $table->index('is_featured');
            $table->fullText(['title', 'content']); // For search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
