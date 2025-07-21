<?php

namespace App\Events;

use App\Models\Article;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Artikel wurde bewertet (up/down vote)
 */
class ArticleVoted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Article $article;
    public User $user;
    public string $vote; // 'up' or 'down'

    /**
     * Create a new event instance.
     */
    public function __construct(Article $article, User $user, string $vote)
    {
        $this->article = $article;
        $this->user = $user;
        $this->vote = $vote;
    }
}