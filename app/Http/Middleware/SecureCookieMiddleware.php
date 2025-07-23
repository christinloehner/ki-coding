<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware für sichere Cookie-Konfiguration
 */
class SecureCookieMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // PHP Session Cookie-Einstellungen vor der Request-Verarbeitung setzen
        if (session_status() === PHP_SESSION_NONE) {
            // Session Cookie-Einstellungen für mehr Sicherheit
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', config('session.secure', true) ? '1' : '0');
            ini_set('session.cookie_samesite', config('session.same_site', 'Lax'));
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_path', config('session.path', '/'));
            
            // Domain nur setzen wenn konfiguriert
            if (config('session.domain')) {
                ini_set('session.cookie_domain', config('session.domain'));
            }
        }

        $response = $next($request);

        // Security Headers setzen
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Response Headers für Cookie-Sicherheit setzen
        if ($response->headers->getCookies()) {
            foreach ($response->headers->getCookies() as $cookie) {
                // Für Session-Cookies HttpOnly forcieren (außer XSRF-TOKEN)
                if (in_array($cookie->getName(), ['PHPSESSID', 'laravel_session', 'ki_coding_session']) 
                    && !$cookie->isHttpOnly()) {
                    
                    $response->headers->setCookie(
                        cookie(
                            $cookie->getName(),
                            $cookie->getValue(),
                            $cookie->getExpiresTime(),
                            $cookie->getPath(),
                            $cookie->getDomain(),
                            $cookie->isSecure(),
                            true, // HttpOnly = true
                            false,
                            $cookie->getSameSite()
                        )
                    );
                }
            }
        }

        return $response;
    }
}