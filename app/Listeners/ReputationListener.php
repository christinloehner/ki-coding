<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Event Listener für Reputation-Updates
 */
class ReputationListener
{
    /**
     * Handle article published events.
     */
    public function handleArticlePublished($event): void
    {
        $article = $event->article;
        $author = $article->user;
        
        if ($author) {
            $author->incrementReputation(5);
            \Log::info("Reputation +5 für Artikel-Veröffentlichung: User {$author->id}, Artikel {$article->id}");
        }
    }

    /**
     * Handle article liked events.
     */
    public function handleArticleLiked($event): void
    {
        $article = $event->article;
        $author = $article->user;
        
        if ($author) {
            $author->incrementReputation(2);
            \Log::info("Reputation +2 für Artikel-Like: User {$author->id}, Artikel {$article->id}");
        }
    }

    /**
     * Handle article unliked events.
     */
    public function handleArticleUnliked($event): void
    {
        $article = $event->article;
        $author = $article->user;
        
        if ($author) {
            $author->decrementReputation(2);
            \Log::info("Reputation -2 für Artikel-Unlike: User {$author->id}, Artikel {$article->id}");
        }
    }

    /**
     * Handle comment created events.
     */
    public function handleCommentCreated($event): void
    {
        $comment = $event->comment;
        $author = $comment->user;
        
        if ($author) {
            $author->incrementReputation(1);
            \Log::info("Reputation +1 für Kommentar: User {$author->id}, Kommentar {$comment->id}");
        }
    }

    /**
     * Handle comment liked events.
     */
    public function handleCommentLiked($event): void
    {
        $comment = $event->comment;
        $author = $comment->user;
        
        if ($author) {
            $author->incrementReputation(1);
            \Log::info("Reputation +1 für Kommentar-Like: User {$author->id}, Kommentar {$comment->id}");
        }
    }

    /**
     * Handle comment unliked events.
     */
    public function handleCommentUnliked($event): void
    {
        $comment = $event->comment;
        $author = $comment->user;
        
        if ($author) {
            $author->decrementReputation(1);
            \Log::info("Reputation -1 für Kommentar-Unlike: User {$author->id}, Kommentar {$comment->id}");
        }
    }

    /**
     * Handle article voted events.
     */
    public function handleArticleVoted($event): void
    {
        $article = $event->article;
        $vote = $event->vote;
        $author = $article->user;
        
        if ($author) {
            $points = $vote === 'up' ? 3 : -1;
            if ($points > 0) {
                $author->incrementReputation($points);
            } else {
                $author->decrementReputation(abs($points));
            }
            \Log::info("Reputation {$points} für Artikel-Vote ({$vote}): User {$author->id}, Artikel {$article->id}");
        }
    }

    /**
     * Handle user banned events (reputation penalty).
     */
    public function handleUserBanned($event): void
    {
        $user = $event->user;
        
        if ($user) {
            $user->decrementReputation(10);
            \Log::info("Reputation -10 für User-Ban: User {$user->id}");
        }
    }

    /**
     * Handle content reported events (minor penalty for author).
     */
    public function handleContentReported($event): void
    {
        if (isset($event->article)) {
            $author = $event->article->user;
        } elseif (isset($event->comment)) {
            $author = $event->comment->user;
        } else {
            return;
        }
        
        if ($author) {
            $author->decrementReputation(1);
            \Log::info("Reputation -1 für Content-Report: User {$author->id}");
        }
    }
}