<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Policies\ArticlePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Category::class => CategoryPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

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
        $this->registerPolicies();

        // Define additional gates
        Gate::define('admin-access', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('moderator-access', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        Gate::define('editor-access', function ($user) {
            return $user->hasRole(['editor', 'moderator', 'admin']);
        });

        Gate::define('contributor-access', function ($user) {
            return $user->hasRole(['contributor', 'editor', 'moderator', 'admin']);
        });

        // Wiki-specific gates
        Gate::define('wiki-admin', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        Gate::define('can-publish', function ($user) {
            return $user->hasPermissionTo('publish articles');
        });

        Gate::define('can-moderate', function ($user) {
            return $user->hasPermissionTo('moderate content');
        });

        Gate::define('can-manage-users', function ($user) {
            return $user->hasPermissionTo('manage users');
        });

        Gate::define('can-manage-categories', function ($user) {
            return $user->hasPermissionTo('manage categories');
        });

        Gate::define('can-manage-tags', function ($user) {
            return $user->hasPermissionTo('manage tags');
        });

        Gate::define('can-view-analytics', function ($user) {
            return $user->hasPermissionTo('view analytics');
        });

        Gate::define('can-export-data', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        Gate::define('can-bulk-actions', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        // Content-specific gates
        Gate::define('can-feature-content', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        Gate::define('can-pin-content', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        Gate::define('can-archive-content', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        // User management gates
        Gate::define('can-ban-users', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        Gate::define('can-change-user-roles', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('can-manage-invitations', function ($user) {
            return $user->hasRole(['moderator', 'admin']);
        });

        // System gates
        Gate::define('can-access-system-settings', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('can-manage-permissions', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('can-view-system-logs', function ($user) {
            return $user->hasRole('admin');
        });
    }
}