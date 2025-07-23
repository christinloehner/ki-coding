<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service Provider für Cookie-Sicherheitseinstellungen
 */
class CookieSecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Globale PHP Cookie-Sicherheitseinstellungen
        $this->configurePhpCookieSettings();
    }

    /**
     * Konfiguriert PHP Cookie-Einstellungen für bessere Sicherheit
     */
    private function configurePhpCookieSettings(): void
    {
        // Session Cookie-Einstellungen
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_secure', config('session.secure', true) ? '1' : '0');
        ini_set('session.cookie_samesite', config('session.same_site', 'Lax'));
        
        // Session Name anpassen für bessere Sicherheit
        if (config('session.cookie') !== 'laravel_session') {
            ini_set('session.name', config('session.cookie', 'laravel_session'));
        }
        
        // Cookie-Pfad und Domain
        ini_set('session.cookie_path', config('session.path', '/'));
        if (config('session.domain')) {
            ini_set('session.cookie_domain', config('session.domain'));
        }
        
        // Session Lifetime
        ini_set('session.cookie_lifetime', config('session.lifetime', 120) * 60);
        
        // Zusätzliche Sicherheitseinstellungen
        ini_set('session.use_trans_sid', '0');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.sid_length', '48');
        ini_set('session.sid_bits_per_character', '6');
    }
}