<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Kommentar wurde geliked
 */
class CommentLiked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Comment $comment;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user = $user;
    }
}