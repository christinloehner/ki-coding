<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'color',
        'icon',
        'sort_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all descendant categories.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the articles in this category.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get published articles in this category.
     */
    public function publishedArticles(): HasMany
    {
        return $this->articles()->where('status', 'published');
    }

    /**
     * Get the full path of the category (including parent names).
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Get the URL for this category.
     */
    public function getUrlAttribute(): string
    {
        return route('wiki.categories.show', $this->slug);
    }

    /**
     * Scope to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only include root categories (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get articles count including subcategories.
     */
    public function getTotalArticlesCountAttribute(): int
    {
        $count = $this->articles()->where('status', 'published')->count();
        
        foreach ($this->children as $child) {
            $count += $child->total_articles_count;
        }
        
        return $count;
    }
}
