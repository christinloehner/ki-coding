<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\CommonMark\CommonMarkConverter;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'content',
        'rendered_content',
        'status',
        'likes_count',
        'dislikes_count',
        'is_pinned',
        'meta_data',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
        'is_pinned' => 'boolean',
        'meta_data' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            if (!empty($comment->content)) {
                $comment->rendered_content = self::renderMarkdown($comment->content);
            }
        });

        static::updating(function ($comment) {
            if ($comment->isDirty('content')) {
                $comment->rendered_content = self::renderMarkdown($comment->content);
            }
        });
    }

    /**
     * Get the article this comment belongs to.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who wrote this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for threaded comments).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at');
    }

    /**
     * Get all replies recursively.
     */
    public function allReplies(): HasMany
    {
        return $this->replies()->with('allReplies');
    }

    /**
     * Scope to only include approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to only include pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to only include top-level comments (no parent).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to only include pinned comments.
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Check if the comment is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the comment is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the comment is a reply to another comment.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Check if the comment is a top-level comment.
     */
    public function isTopLevel(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Approve the comment.
     */
    public function approve(): bool
    {
        return $this->update(['status' => 'approved']);
    }

    /**
     * Reject the comment.
     */
    public function reject(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    /**
     * Mark the comment as spam.
     */
    public function markAsSpam(): bool
    {
        return $this->update(['status' => 'spam']);
    }

    /**
     * Pin the comment.
     */
    public function pin(): bool
    {
        return $this->update(['is_pinned' => true]);
    }

    /**
     * Unpin the comment.
     */
    public function unpin(): bool
    {
        return $this->update(['is_pinned' => false]);
    }

    /**
     * Get the depth of this comment in the thread.
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        
        return $depth;
    }

    /**
     * Get the URL for this comment.
     */
    public function getUrlAttribute(): string
    {
        return $this->article->url . '#comment-' . $this->id;
    }

    /**
     * Render markdown content to HTML.
     */
    protected static function renderMarkdown(string $content): string
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
        
        return $converter->convertToHtml($content);
    }
}
