<?php

namespace App\Providers;

use App\Events\ArticleLiked;
use App\Events\ArticlePublished;
use App\Events\ArticleUnliked;
use App\Events\ArticleVoted;
use App\Events\CommentCreated;
use App\Events\CommentLiked;
use App\Events\CommentUnliked;
use App\Listeners\ReputationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Event Service Provider für Reputation-Events
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Event Listener Mappings
     */
    protected $listen = [
        // Laravel Auth Events
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Artikel Events
        ArticlePublished::class => [
            [ReputationListener::class, 'handleArticlePublished'],
        ],
        ArticleLiked::class => [
            [ReputationListener::class, 'handleArticleLiked'],
        ],
        ArticleUnliked::class => [
            [ReputationListener::class, 'handleArticleUnliked'],
        ],
        ArticleVoted::class => [
            [ReputationListener::class, 'handleArticleVoted'],
        ],

        // Kommentar Events
        CommentCreated::class => [
            [ReputationListener::class, 'handleCommentCreated'],
        ],
        CommentLiked::class => [
            [ReputationListener::class, 'handleCommentLiked'],
        ],
        CommentUnliked::class => [
            [ReputationListener::class, 'handleCommentUnliked'],
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    /**
     * Get the discovered events.
     *
     * @return array<class-string, array<int, class-string>>
     */
    public function discoverEvents()
    {
        return [];
    }
}