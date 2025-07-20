<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleRevision extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'article_id',
        'user_id',
        'title',
        'content',
        'rendered_content',
        'excerpt',
        'version_number',
        'change_summary',
        'revision_type',
        'meta_data',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'version_number' => 'integer',
        'meta_data' => 'array',
    ];

    /**
     * Get the article this revision belongs to.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who created this revision.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the previous revision.
     */
    public function previousRevision(): ?ArticleRevision
    {
        return $this->where('article_id', $this->article_id)
                    ->where('version_number', '<', $this->version_number)
                    ->orderBy('version_number', 'desc')
                    ->first();
    }

    /**
     * Get the next revision.
     */
    public function nextRevision(): ?ArticleRevision
    {
        return $this->where('article_id', $this->article_id)
                    ->where('version_number', '>', $this->version_number)
                    ->orderBy('version_number', 'asc')
                    ->first();
    }

    /**
     * Check if this is the latest revision.
     */
    public function isLatest(): bool
    {
        $latestVersion = $this->where('article_id', $this->article_id)
                             ->max('version_number');
        
        return $this->version_number === $latestVersion;
    }

    /**
     * Check if this is the first revision.
     */
    public function isFirst(): bool
    {
        return $this->version_number === 1;
    }

    /**
     * Restore the article to this revision.
     */
    public function restore(): bool
    {
        return $this->article->update([
            'title' => $this->title,
            'content' => $this->content,
            'rendered_content' => $this->rendered_content,
            'excerpt' => $this->excerpt,
        ]);
    }

    /**
     * Get the changes made in this revision compared to the previous one.
     */
    public function getChanges(): array
    {
        $previous = $this->previousRevision();
        
        if (!$previous) {
            return [
                'title' => ['old' => null, 'new' => $this->title],
                'content' => ['old' => null, 'new' => $this->content],
            ];
        }
        
        $changes = [];
        
        if ($previous->title !== $this->title) {
            $changes['title'] = ['old' => $previous->title, 'new' => $this->title];
        }
        
        if ($previous->content !== $this->content) {
            $changes['content'] = ['old' => $previous->content, 'new' => $this->content];
        }
        
        return $changes;
    }

    /**
     * Get the URL for this revision.
     */
    public function getUrlAttribute(): string
    {
        return route('wiki.articles.revisions.show', [
            'article' => $this->article->slug,
            'revision' => $this->id
        ]);
    }
}
