<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model für Benutzer-Meldungen
 */
class UserReport extends Model
{
    protected $fillable = [
        'reported_user_id',
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
     * Beziehung zum gemeldeten Benutzer
     */
    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
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
