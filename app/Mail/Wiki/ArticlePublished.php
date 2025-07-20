<?php

namespace App\Mail\Wiki;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * E-Mail-Benachrichtigung für veröffentlichte Artikel
 */
class ArticlePublished extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Der veröffentlichte Artikel
     */
    public Article $article;

    /**
     * Create a new message instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
        $this->onQueue('wiki-emails');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Neuer Artikel veröffentlicht: ' . $this->article->title,
            from: config('mail.from.address'),
            replyTo: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.wiki.article-published',
            with: [
                'article' => $this->article,
                'author' => $this->article->user,
                'category' => $this->article->category,
                'tags' => $this->article->tags,
                'articleUrl' => route('wiki.articles.show', $this->article->slug),
                'unsubscribeUrl' => route('wiki.dashboard.settings'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
