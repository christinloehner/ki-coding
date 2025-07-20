<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WikiSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Security headers
        $response = $next($request);
        
        // Add security headers
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy for Wiki pages
        if ($request->is('wiki/*')) {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com https://maxcdn.bootstrapcdn.com https://matomo.christin-loehner.de https://www.google.com https://www.gstatic.com; " .
                   "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com https://maxcdn.bootstrapcdn.com; " .
                   "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://maxcdn.bootstrapcdn.com; " .
                   "connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://matomo.christin-loehner.de; " .
                   "img-src 'self' data: https:; " .
                   "media-src 'self' https:; " .
                   "object-src 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self'; " .
                   "frame-ancestors 'none'";
            
            $response->headers->set('Content-Security-Policy', $csp);
        }
        
        // Log suspicious activities
        $this->logSuspiciousActivity($request);
        
        // Check for malicious patterns in request
        $this->checkMaliciousPatterns($request);
        
        return $response;
    }
    
    /**
     * Log suspicious activities.
     */
    private function logSuspiciousActivity(Request $request): void
    {
        $suspiciousPatterns = [
            '<script',           // HTML script tags only
            '</script>',         // HTML script tags only
            'javascript:',       // JavaScript protocols
            'vbscript:',        // VBScript protocols
            'data:text/html',   // Data URLs with HTML
            'onload=',          // Event handlers with equals
            'onerror=',         // Event handlers with equals
            'onclick=',         // Event handlers with equals
            'onmouseover=',     // Event handlers with equals
            'eval(',            // JavaScript eval function
            'expression(',      // CSS expression
            'setTimeout(',      // JavaScript timer functions
            'setInterval(',     // JavaScript timer functions
            'document.cookie',  // Cookie manipulation
            'document.write',   // DOM manipulation
            'innerHTML=',       // DOM manipulation with assignment
            'outerHTML=',       // DOM manipulation with assignment
            'insertAdjacentHTML(', // DOM manipulation function
            'XMLHttpRequest',   // AJAX requests
            'fetch(',           // Modern AJAX
            'window.location=', // Location manipulation with assignment
            'location.href=',   // Location manipulation with assignment
            'location.replace(', // Location manipulation function
            'history.pushState(', // History manipulation
            'history.replaceState(', // History manipulation
        ];
        
        $input = $request->all();
        $inputString = json_encode($input);
        
        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($inputString, $pattern) !== false) {
                Log::warning('Suspicious input detected in Wiki', [
                    'user_id' => Auth::id(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'pattern' => $pattern,
                    'input' => $input,
                    'timestamp' => now(),
                ]);
                break;
            }
        }
    }
    
    /**
     * Check for malicious patterns and block if necessary.
     */
    private function checkMaliciousPatterns(Request $request): void
    {
        $dangerousPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/<iframe[^>]*>.*?<\/iframe>/is',
            '/<object[^>]*>.*?<\/object>/is',
            '/<embed[^>]*>.*?<\/embed>/is',
            '/<applet[^>]*>.*?<\/applet>/is',
            '/<meta[^>]*http-equiv[^>]*refresh/is',
            '/javascript:/i',
            '/vbscript:/i',
            '/data:text\/html/i',
            '/on\w+\s*=/i', // Event handlers like onclick, onload, etc.
            '/eval\s*\(/i',
            '/expression\s*\(/i',
            '/setTimeout\s*\(/i',
            '/setInterval\s*\(/i',
            '/document\.cookie/i',
            '/document\.write/i',
            '/innerHTML/i',
            '/outerHTML/i',
            '/insertAdjacentHTML/i',
            '/window\.location/i',
            '/location\.href/i',
            '/location\.replace/i',
            '/history\.pushState/i',
            '/history\.replaceState/i',
        ];
        
        $input = $request->all();
        $inputString = json_encode($input);
        
        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $inputString)) {
                Log::error('Malicious input blocked in Wiki', [
                    'user_id' => Auth::id(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'pattern' => $pattern,
                    'input' => $input,
                    'timestamp' => now(),
                ]);
                
                abort(400, 'Malicious input detected');
            }
        }
    }
}