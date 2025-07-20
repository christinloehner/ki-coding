<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Services\MarkdownService;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'rendered_content',
        'excerpt',
        'user_id',
        'category_id',
        'status',
        'published_at',
        'meta_data',
        'is_featured',
        'allow_comments',
        'reading_time',
        'deletion_requested_at',
        'deletion_reason',
        'deletion_requested_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'published_at' => 'datetime',
        'deletion_requested_at' => 'datetime',
        'meta_data' => 'array',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'reading_time' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = self::generateUniqueSlug($article->title);
            }
            
            // Auto-generate rendered content from markdown
            if (!empty($article->content)) {
                $article->rendered_content = self::renderMarkdown($article->content);
            }
            
            // Auto-generate excerpt if not provided
            if (empty($article->excerpt)) {
                $article->excerpt = self::generateExcerpt($article->content);
            }
            
            // Calculate reading time
            $article->reading_time = self::calculateReadingTime($article->content);
        });

        // Cache invalidierung für Sitemap bei neuen/geänderten/gelöschten Artikeln
        static::created(function ($article) {
            Cache::forget('sitemap_cache');
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            
            if ($article->isDirty('content')) {
                $article->rendered_content = self::renderMarkdown($article->content);
                $article->reading_time = self::calculateReadingTime($article->content);
                
                if (empty($article->excerpt)) {
                    $article->excerpt = self::generateExcerpt($article->content);
                }
            }
            
            // Cache invalidierung für Sitemap bei Artikel-Updates
            Cache::forget('sitemap_cache');
        });

        static::deleted(function ($article) {
            // Cache invalidierung für Sitemap bei gelöschten Artikeln
            Cache::forget('sitemap_cache');
        });
    }

    /**
     * Get the user who created this article.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category this article belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags associated with this article.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags')->withTimestamps();
    }

    /**
     * Get the comments for this article.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get approved comments for this article.
     */
    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved');
    }

    /**
     * Get the revisions for this article.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ArticleRevision::class)->orderBy('version_number', 'desc');
    }

    /**
     * Get the latest revision.
     */
    public function latestRevision(): HasMany
    {
        return $this->revisions()->latest();
    }

    /**
     * Get the user who requested deletion.
     */
    public function deletionRequestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deletion_requested_by');
    }

    /**
     * Get the URL for this article.
     */
    public function getUrlAttribute(): string
    {
        return route('wiki.articles.show', $this->slug);
    }

    /**
     * Scope to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope to only include featured articles.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to search articles by title and content.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%");
        });
    }

    /**
     * Check if the article is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at->isPast();
    }

    /**
     * Check if the article can be commented on.
     */
    public function canBeCommented(): bool
    {
        return $this->allow_comments && $this->isPublished();
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Create a new revision of this article.
     */
    public function createRevision(User $user, string $changeSummary = null): ArticleRevision
    {
        $latestVersion = $this->revisions()->max('version_number') ?? 0;
        
        return $this->revisions()->create([
            'user_id' => $user->id,
            'title' => $this->title,
            'content' => $this->content,
            'rendered_content' => $this->rendered_content,
            'excerpt' => $this->excerpt,
            'version_number' => $latestVersion + 1,
            'change_summary' => $changeSummary,
            'revision_type' => 'update',
        ]);
    }

    /**
     * Render markdown content to HTML.
     */
    protected static function renderMarkdown(string $content): string
    {
        $markdownService = app(MarkdownService::class);
        return $markdownService->toHtml($content);
    }

    /**
     * Generate unique slug from title.
     */
    protected static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        // Check if slug exists and increment counter until unique
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Generate excerpt from content.
     */
    protected static function generateExcerpt(string $content, int $length = 160): string
    {
        $markdownService = app(MarkdownService::class);
        return $markdownService->generateExcerpt($content, $length);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'category' => $this->category?->name,
            'tags' => $this->tags->pluck('name')->toArray(),
            'author' => $this->user?->name,
            'published_at' => $this->published_at?->timestamp,
            'reading_time' => $this->reading_time,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get the Scout key for the model.
     */
    public function getScoutKey(): mixed
    {
        return $this->getKey();
    }

    /**
     * Get the Scout key name for the model.
     */
    public function getScoutKeyName(): string
    {
        return $this->getKeyName();
    }

    /**
     * Calculate reading time in minutes.
     */
    protected static function calculateReadingTime(string $content): int
    {
        $markdownService = app(MarkdownService::class);
        return $markdownService->calculateReadingTime($content);
    }
}
