<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model für Benutzer-Einladungen
 */
class UserInvitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'name',
        'invited_by',
        'expires_at',
        'accepted_at',
        'user_id',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Beziehung zum einladenden Benutzer
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Beziehung zum registrierten Benutzer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Prüfen ob Einladung noch gültig ist
     */
    public function isValid(): bool
    {
        return $this->expires_at > now() && is_null($this->accepted_at);
    }

    /**
     * Prüfen ob Einladung bereits akzeptiert wurde
     */
    public function isAccepted(): bool
    {
        return !is_null($this->accepted_at);
    }
}
