<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

/**
 * Notification Model für das Benachrichtigungssystem
 * 
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Carbon\Carbon|null $read_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Notification extends DatabaseNotification
{
    /**
     * Die Attribute, die als Datumstypen behandelt werden sollen.
     *
     * @var array<string>
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Die Tabelle, die mit dem Model verbunden ist.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * Scope für ungelesene Benachrichtigungen
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope für gelesene Benachrichtigungen
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Benachrichtigung als gelesen markieren
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }
}
