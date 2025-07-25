<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix storage path for Docker containers
        if (env('APP_BASE_PATH')) {
            $this->app->useStoragePath(env('APP_BASE_PATH') . '/storage');
        }

        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }
}
