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
        if (!Schema::hasTable('article_reports')) {
            Schema::create('article_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reason', 500);
            $table->enum('category', ['spam', 'inappropriate', 'copyright', 'other']);
            $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['article_id', 'user_id']);
            $table->index(['status', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_reports');
    }
};
