<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'author_name',
        'role',
        'text',
        'attachment_name',
        'attachment_url',
        'attachment_type',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comment's attributes for API responses
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Format attachment
        if ($this->attachment_name) {
            $array['attachment'] = [
                'name' => $this->attachment_name,
                'url' => $this->attachment_url,
                'type' => $this->attachment_type,
            ];
        }
        
        // Remove snake_case fields
        unset($array['attachment_name'], $array['attachment_url'], $array['attachment_type'],
              $array['ticket_id'], $array['user_id']);
        
        return $array;
    }
}
