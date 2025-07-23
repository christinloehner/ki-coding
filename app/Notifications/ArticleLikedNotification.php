<?php

namespace App\Notifications;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Benachrichtigung wenn ein Artikel geliked wird
 */
class ArticleLikedNotification extends Notification
{
    use Queueable;

    protected Article $article;
    protected User $liker;

    /**
     * Create a new notification instance.
     */
    public function __construct(Article $article, User $liker)
    {
        $this->article = $article;
        $this->liker = $liker;
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
            'type' => 'article_liked',
            'message' => "{$this->liker->name} hat deinen Artikel \"{$this->article->title}\" geliked",
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'article_slug' => $this->article->slug,
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'liker_username' => $this->liker->username,
            'icon' => 'fas fa-heart',
            'color' => 'text-red-600',
            'url' => route('wiki.articles.show', $this->article->slug),
        ];
    }
}
