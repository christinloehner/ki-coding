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
        // SEO Meta-Felder hinzufügen, falls sie noch nicht existieren
        if (!Schema::hasColumn('categories', 'meta_title')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('meta_title')->nullable()->after('description');
            });
        }
        
        if (!Schema::hasColumn('categories', 'meta_description')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('meta_description')->nullable()->after('description');
            });
        }
        
        if (!Schema::hasColumn('categories', 'meta_keywords')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('meta_keywords')->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Meta-Felder wieder entfernen
            if (Schema::hasColumn('categories', 'meta_keywords')) {
                $table->dropColumn('meta_keywords');
            }
            if (Schema::hasColumn('categories', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('categories', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
        });
    }
};