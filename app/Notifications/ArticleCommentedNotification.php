<?php

namespace App\Notifications;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Benachrichtigung wenn ein Artikel kommentiert wird
 */
class ArticleCommentedNotification extends Notification
{
    use Queueable;

    protected Article $article;
    protected Comment $comment;
    protected User $commenter;

    /**
     * Create a new notification instance.
     */
    public function __construct(Article $article, Comment $comment, User $commenter)
    {
        $this->article = $article;
        $this->comment = $comment;
        $this->commenter = $commenter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'article_commented',
            'message' => "{$this->commenter->name} hat deinen Artikel \"{$this->article->title}\" kommentiert",
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'article_slug' => $this->article->slug,
            'comment_id' => $this->comment->id,
            'comment_excerpt' => \Str::limit(strip_tags($this->comment->content), 100),
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'commenter_username' => $this->commenter->username,
            'icon' => 'fas fa-comment',
            'color' => 'text-blue-600',
            'url' => route('wiki.articles.show', $this->article->slug) . '#comment-' . $this->comment->id,
        ];
    }
}
