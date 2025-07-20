<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BanCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if user is banned
        if ($user->isBanned()) {
            // Log the ban attempt
            Log::warning('Banned user attempted to access protected area', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'ban_reason' => $user->ban_reason,
                'banned_until' => $user->banned_until,
                'timestamp' => now(),
            ]);

            // Check if ban is temporary and has expired
            if ($user->banned_until && now()->greaterThan($user->banned_until)) {
                // Automatically unban the user
                $user->update([
                    'banned_until' => null,
                    'ban_reason' => null,
                ]);

                Log::info('User automatically unbanned after ban period expired', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'timestamp' => now(),
                ]);

                return $next($request);
            }

            // User is still banned, deny access
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Your account has been suspended.',
                    'message' => $user->ban_reason ?: 'Your account has been suspended for violating our terms of service.',
                    'banned_until' => $user->banned_until ? $user->banned_until->toDateTimeString() : null,
                ], 403);
            }

            // For web requests, redirect to a ban page or logout
            Auth::logout();
            return redirect()->route('home')->with('error', 'Your account has been suspended. ' . ($user->ban_reason ?: 'Please contact support for more information.'));
        }

        return $next($request);
    }
}