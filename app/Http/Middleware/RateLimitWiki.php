<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimitWiki
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define rate limits for different actions
        $limits = $this->getRateLimits($request);
        
        if (!$limits) {
            return $next($request);
        }

        $identifier = $this->getIdentifier($request);
        $key = $this->getRateLimitKey($request, $identifier);
        
        // Get current count from cache
        $count = Cache::get($key, 0);
        
        // Check if limit exceeded
        if ($count >= $limits['max']) {
            // Log rate limit exceeded
            Log::warning('Rate limit exceeded for Wiki action', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'action' => $limits['action'],
                'limit' => $limits['max'],
                'window' => $limits['window'],
                'current_count' => $count,
                'timestamp' => now(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Rate limit exceeded',
                    'message' => 'Too many requests. Please try again later.',
                    'retry_after' => $limits['window'],
                ], 429);
            }
            
            return back()->withErrors(['rate_limit' => 'Too many requests. Please try again later.']);
        }
        
        // Increment counter
        Cache::put($key, $count + 1, now()->addSeconds($limits['window']));
        
        return $next($request);
    }
    
    /**
     * Get rate limits for the current request.
     */
    private function getRateLimits(Request $request): ?array
    {
        $path = $request->path();
        $method = $request->method();
        
        // Rate limits for different Wiki actions
        $rateLimits = [
            // Article creation and editing
            'POST:wiki/articles' => [
                'action' => 'create_article',
                'max' => 5,
                'window' => 3600, // 1 hour
            ],
            'PATCH:wiki/articles/*' => [
                'action' => 'edit_article',
                'max' => 10,
                'window' => 3600, // 1 hour
            ],
            'DELETE:wiki/articles/*' => [
                'action' => 'delete_article',
                'max' => 3,
                'window' => 3600, // 1 hour
            ],
            
            // Comment creation and editing
            'POST:wiki/comments' => [
                'action' => 'create_comment',
                'max' => 20,
                'window' => 3600, // 1 hour
            ],
            'PATCH:wiki/comments/*' => [
                'action' => 'edit_comment',
                'max' => 10,
                'window' => 3600, // 1 hour
            ],
            'DELETE:wiki/comments/*' => [
                'action' => 'delete_comment',
                'max' => 10,
                'window' => 3600, // 1 hour
            ],
            
            // Category management
            'POST:wiki/categories' => [
                'action' => 'create_category',
                'max' => 3,
                'window' => 3600, // 1 hour
            ],
            'PATCH:wiki/categories/*' => [
                'action' => 'edit_category',
                'max' => 5,
                'window' => 3600, // 1 hour
            ],
            'DELETE:wiki/categories/*' => [
                'action' => 'delete_category',
                'max' => 2,
                'window' => 3600, // 1 hour
            ],
            
            // Search requests
            'GET:wiki/search' => [
                'action' => 'search',
                'max' => 100,
                'window' => 3600, // 1 hour
            ],
            'GET:wiki/search/suggestions' => [
                'action' => 'search_suggestions',
                'max' => 200,
                'window' => 3600, // 1 hour
            ],
            
            // Like/unlike actions
            'POST:wiki/comments/*/like' => [
                'action' => 'like_comment',
                'max' => 50,
                'window' => 3600, // 1 hour
            ],
            
            // Report actions
            'POST:wiki/comments/*/report' => [
                'action' => 'report_comment',
                'max' => 10,
                'window' => 3600, // 1 hour
            ],
            
            // General Wiki page views (very permissive)
            'GET:wiki/*' => [
                'action' => 'view_wiki',
                'max' => 1000,
                'window' => 3600, // 1 hour
            ],
        ];
        
        // Check for exact matches first
        $requestKey = $method . ':' . $path;
        if (isset($rateLimits[$requestKey])) {
            return $rateLimits[$requestKey];
        }
        
        // Check for pattern matches
        foreach ($rateLimits as $pattern => $limits) {
            if ($this->matchesPattern($requestKey, $pattern)) {
                return $limits;
            }
        }
        
        return null;
    }
    
    /**
     * Check if request matches a rate limit pattern.
     */
    private function matchesPattern(string $request, string $pattern): bool
    {
        // Convert pattern to regex
        $regex = str_replace('*', '[^/]+', $pattern);
        $regex = '/^' . str_replace('/', '\/', $regex) . '$/';
        
        return preg_match($regex, $request);
    }
    
    /**
     * Get unique identifier for rate limiting.
     */
    private function getIdentifier(Request $request): string
    {
        // Use user ID if authenticated, otherwise use IP
        if (Auth::check()) {
            return 'user:' . Auth::id();
        }
        
        return 'ip:' . $request->ip();
    }
    
    /**
     * Get cache key for rate limiting.
     */
    private function getRateLimitKey(Request $request, string $identifier): string
    {
        $path = $request->path();
        $method = $request->method();
        
        return 'rate_limit:' . $identifier . ':' . $method . ':' . str_replace('/', '_', $path);
    }
}