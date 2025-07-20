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
        // Add missing composite indexes to article_likes if they don't exist
        if (Schema::hasTable('article_likes')) {
            Schema::table('article_likes', function (Blueprint $table) {
                // Add composite indexes for better performance
                if (!$this->indexExists('article_likes', ['article_id', 'created_at'])) {
                    $table->index(['article_id', 'created_at']);
                }
                if (!$this->indexExists('article_likes', ['user_id', 'created_at'])) {
                    $table->index(['user_id', 'created_at']);
                }
            });
        }

        // Add missing composite indexes to article_bookmarks if they don't exist
        if (Schema::hasTable('article_bookmarks')) {
            Schema::table('article_bookmarks', function (Blueprint $table) {
                // Add composite indexes for better performance
                if (!$this->indexExists('article_bookmarks', ['article_id', 'created_at'])) {
                    $table->index(['article_id', 'created_at']);
                }
                if (!$this->indexExists('article_bookmarks', ['user_id', 'created_at'])) {
                    $table->index(['user_id', 'created_at']);
                }
            });
        }

        // Add missing composite indexes to article_votes if they don't exist
        if (Schema::hasTable('article_votes')) {
            Schema::table('article_votes', function (Blueprint $table) {
                // Add composite indexes for better performance
                if (!$this->indexExists('article_votes', ['article_id', 'is_helpful'])) {
                    $table->index(['article_id', 'is_helpful']);
                }
                if (!$this->indexExists('article_votes', ['user_id', 'created_at'])) {
                    $table->index(['user_id', 'created_at']);
                }
            });
        }

        // Add missing composite indexes to article_reports if they don't exist
        if (Schema::hasTable('article_reports')) {
            Schema::table('article_reports', function (Blueprint $table) {
                // Note: article_reports already has index(['status', 'created_at'])
                // Add additional composite indexes for admin queries
                if (!$this->indexExists('article_reports', ['reviewed_by', 'reviewed_at'])) {
                    $table->index(['reviewed_by', 'reviewed_at']);
                }
                if (!$this->indexExists('article_reports', ['article_id', 'status'])) {
                    $table->index(['article_id', 'status']);
                }
            });
        }

        // Add missing composite indexes to comment_reports if they don't exist
        if (Schema::hasTable('comment_reports')) {
            Schema::table('comment_reports', function (Blueprint $table) {
                // Note: comment_reports already has index(['status', 'created_at'])
                // Add additional composite indexes for admin queries
                if (!$this->indexExists('comment_reports', ['reviewed_by', 'reviewed_at'])) {
                    $table->index(['reviewed_by', 'reviewed_at']);
                }
                if (!$this->indexExists('comment_reports', ['comment_id', 'status'])) {
                    $table->index(['comment_id', 'status']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes if they exist
        if (Schema::hasTable('article_likes')) {
            Schema::table('article_likes', function (Blueprint $table) {
                $table->dropIndex(['article_id', 'created_at']);
                $table->dropIndex(['user_id', 'created_at']);
            });
        }

        if (Schema::hasTable('article_bookmarks')) {
            Schema::table('article_bookmarks', function (Blueprint $table) {
                $table->dropIndex(['article_id', 'created_at']);
                $table->dropIndex(['user_id', 'created_at']);
            });
        }

        if (Schema::hasTable('article_votes')) {
            Schema::table('article_votes', function (Blueprint $table) {
                $table->dropIndex(['article_id', 'is_helpful']);
                $table->dropIndex(['user_id', 'created_at']);
            });
        }

        if (Schema::hasTable('article_reports')) {
            Schema::table('article_reports', function (Blueprint $table) {
                $table->dropIndex(['article_id', 'status']);
                $table->dropIndex(['reviewed_by', 'reviewed_at']);
            });
        }

        if (Schema::hasTable('comment_reports')) {
            Schema::table('comment_reports', function (Blueprint $table) {
                $table->dropIndex(['comment_id', 'status']);
                $table->dropIndex(['reviewed_by', 'reviewed_at']);
            });
        }
    }

    /**
     * Check if index exists on table - Laravel 11 compatible
     */
    private function indexExists(string $table, array $columns): bool
    {
        try {
            $indexes = Schema::getConnection()->select("SHOW INDEXES FROM `{$table}`");
            $existingIndexes = [];
            
            foreach ($indexes as $index) {
                $indexName = $index->Key_name;
                if (!isset($existingIndexes[$indexName])) {
                    $existingIndexes[$indexName] = [];
                }
                $existingIndexes[$indexName][] = strtolower($index->Column_name);
            }
            
            $targetColumns = array_map('strtolower', $columns);
            sort($targetColumns);
            
            foreach ($existingIndexes as $indexColumns) {
                sort($indexColumns);
                if ($indexColumns === $targetColumns) {
                    return true;
                }
            }
            
            return false;
        } catch (\Exception $e) {
            // If we can't check, assume it doesn't exist and let Laravel handle duplicates
            return false;
        }
    }
};