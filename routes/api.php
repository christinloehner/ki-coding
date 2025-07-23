<?php

use App\Http\Controllers\Api\V1\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User-Endpoint mit Rate Limiting
Route::middleware(['auth:sanctum', 'throttle:30,1'])->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->user(),
        'message' => 'Benutzerinformationen erfolgreich abgerufen.',
        'timestamp' => now()->toISOString(),
    ]);
});

// API v1 Routes mit Rate Limiting
Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Article Resource Routes
    Route::apiResource('articles', ArticleController::class)->only(['store']);
    
    // Weitere API-Endpunkte können hier hinzugefügt werden
    // Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    // Route::apiResource('tags', TagController::class)->only(['index', 'show']);
});
