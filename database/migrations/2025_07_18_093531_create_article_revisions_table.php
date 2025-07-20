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
        Schema::create('article_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('content'); // Markdown content
            $table->longText('rendered_content'); // HTML content
            $table->text('excerpt')->nullable();
            $table->integer('version_number');
            $table->text('change_summary')->nullable();
            $table->enum('revision_type', ['create', 'update', 'restore'])->default('update');
            $table->json('meta_data')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['article_id', 'version_number']);
            $table->index(['user_id', 'created_at']);
            $table->index('revision_type');
            
            // Ensure version numbers are unique per article
            $table->unique(['article_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_revisions');
    }
};
