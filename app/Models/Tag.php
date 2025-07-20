<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'usage_count',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'usage_count' => 'integer',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get the articles associated with this tag.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tags')->withTimestamps();
    }

    /**
     * Get published articles associated with this tag.
     */
    public function publishedArticles(): BelongsToMany
    {
        return $this->articles()->where('status', 'published');
    }

    /**
     * Get the URL for this tag.
     */
    public function getUrlAttribute(): string
    {
        return route('wiki.tags.show', $this->slug);
    }

    /**
     * Scope to only include featured tags.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order tags by usage count.
     */
    public function scopePopular($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    /**
     * Scope to search tags by name.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    /**
     * Increment usage count when tag is assigned to article.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement usage count when tag is removed from article.
     */
    public function decrementUsage(): void
    {
        $this->decrement('usage_count');
    }

    /**
     * Update usage count based on current article count.
     */
    public function updateUsageCount(): void
    {
        $this->update([
            'usage_count' => $this->articles()->count()
        ]);
    }

    /**
     * Get the tag's color for UI display.
     */
    public function getColorAttribute($value): string
    {
        return $value ?: '#3B82F6';
    }
}
