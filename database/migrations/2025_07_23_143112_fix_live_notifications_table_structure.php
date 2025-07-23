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
        // Prüfung ob notifications Tabelle die falsche Struktur hat (id + uuid)
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'uuid')) {
            // Falsche Struktur erkannt - Tabelle neu erstellen
            Schema::dropIfExists('notifications');
            
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback zur ursprünglichen fehlerhaften Struktur
        if (Schema::hasTable('notifications')) {
            Schema::dropIfExists('notifications');
            
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique()->index();
                $table->string('type');
                $table->morphs('notifiable');
                $table->json('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                
                $table->index('read_at');
            });
        }
    }
};