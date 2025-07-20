<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model für Artikel-Meldungen
 */
class ArticleReport extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'reason',
        'category',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Beziehung zum gemeldeten Artikel
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Beziehung zum meldenden Benutzer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Beziehung zum prüfenden Moderator
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
