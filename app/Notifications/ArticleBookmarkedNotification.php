<?php

namespace App\Notifications;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Benachrichtigung wenn ein Artikel bookmarked wird
 */
class ArticleBookmarkedNotification extends Notification
{
    use Queueable;

    protected Article $article;
    protected User $bookmarker;

    /**
     * Create a new notification instance.
     */
    public function __construct(Article $article, User $bookmarker)
    {
        $this->article = $article;
        $this->bookmarker = $bookmarker;
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
            'type' => 'article_bookmarked',
            'message' => "{$this->bookmarker->name} hat deinen Artikel \"{$this->article->title}\" gespeichert",
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'article_slug' => $this->article->slug,
            'bookmarker_id' => $this->bookmarker->id,
            'bookmarker_name' => $this->bookmarker->name,
            'bookmarker_username' => $this->bookmarker->username,
            'icon' => 'fas fa-bookmark',
            'color' => 'text-yellow-600',
            'url' => route('wiki.articles.show', $this->article->slug),
        ];
    }
}
