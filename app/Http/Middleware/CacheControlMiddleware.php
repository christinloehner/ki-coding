<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // PHP-Session-Cache-Limiter deaktivieren
        session_cache_limiter('');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $response = $next($request);

        // Eigene Cache-Control-Header setzen
        $response->headers->set('Cache-Control', 'public, max-age=0, must-revalidate, stale-while-revalidate=300');

        return $response;
    }
}
