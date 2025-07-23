<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Prüfe ob die Tabelle existiert bevor wir Änderungen vornehmen
        if (!Schema::hasTable('articles')) {
            return; // Tabelle existiert nicht - nichts zu tun
        }
        
        // Prüfe ob die slug Spalte existiert
        if (!Schema::hasColumn('articles', 'slug')) {
            return; // Spalte existiert nicht - nichts zu tun
        }
        
        // Entferne das unique constraint von der slug spalte falls es existiert
        // Laravel 11 kompatible Lösung: Versuche verschiedene Index-Namen
        $possibleIndexNames = [
            'articles_slug_unique',
            'slug_unique', 
            'slug'
        ];
        
        foreach ($possibleIndexNames as $indexName) {
            try {
                DB::statement("ALTER TABLE articles DROP INDEX {$indexName}");
                break; // Erfolgreich entfernt - stop
            } catch (Exception $e) {
                // Index mit diesem Namen existiert nicht - weiter versuchen
                continue;
            }
        }
        
        // Erstelle einen normalen Index für Performance falls er nicht existiert
        try {
            // Prüfe ob der Index bereits existiert durch Versuch ihn zu erstellen
            DB::statement('CREATE INDEX articles_slug_index ON articles (slug)');
        } catch (Exception $e) {
            // Index existiert bereits oder anderer Fehler - das ist okay für Performance-Index
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Prüfe ob die Tabelle existiert
        if (!Schema::hasTable('articles')) {
            return;
        }
        
        // Prüfe ob die slug Spalte existiert
        if (!Schema::hasColumn('articles', 'slug')) {
            return;
        }
        
        // Entferne den Performance-Index falls er existiert
        try {
            DB::statement('DROP INDEX articles_slug_index ON articles');
        } catch (Exception $e) {
            // Index existiert nicht - das ist okay
        }
        
        // Stelle das unique constraint wieder her falls es nicht existiert
        Schema::table('articles', function (Blueprint $table) {
            try {
                $table->unique('slug', 'articles_slug_unique');
            } catch (Exception $e) {
                // Unique constraint existiert bereits - das ist okay
            }
        });
    }
};