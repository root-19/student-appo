<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'recipient_id',
        'title',
        'message',
        'type',
        'timestamp',
        'is_read',
        'ticket_id',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the notification's attributes for API responses
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Map database fields to frontend expectations
        $array['recipientId'] = (string) $this->recipient_id;
        $array['isRead'] = $this->is_read;
        $array['ticketId'] = $this->ticket_id;
        
        // Remove snake_case fields
        unset($array['recipient_id'], $array['is_read'], $array['ticket_id']);
        
        return $array;
    }
}
