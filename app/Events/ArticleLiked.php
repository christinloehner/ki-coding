<?php

namespace App\Events;

use App\Models\Article;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Artikel wurde geliked
 */
class ArticleLiked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Article $article;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Article $article, User $user)
    {
        $this->article = $article;
        $this->user = $user;
    }
}