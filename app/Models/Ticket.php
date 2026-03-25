<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_id',
        'title',
        'category',
        'subcategory',
        'priority',
        'status',
        'description',
        'submitted_by',
        'student_name',
        'submitted_date',
        'last_updated',
        'appointment_date',
        'appointment_time',
        'attachment_name',
        'attachment_url',
        'attachment_type',
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'last_updated' => 'date',
        'appointment_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the ticket's attributes for API responses
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Map database fields to frontend expectations
        $array['id'] = $this->ticket_id;
        $array['submittedBy'] = (string) $this->submitted_by;
        $array['submittedDate'] = $this->submitted_date?->format('Y-m-d');
        $array['lastUpdated'] = $this->last_updated?->format('Y-m-d');
        $array['appointmentDate'] = $this->appointment_date?->format('Y-m-d');
        $array['appointmentTime'] = $this->appointment_time;
        
        // Add student information from related user
        if ($this->relationLoaded('user') && $this->user) {
            $array['studentId'] = $this->user->student_id;
            $array['studentEmail'] = $this->user->email;
        } else {
            // Fallback if user relationship not loaded
            $array['studentId'] = null;
            $array['studentEmail'] = null;
        }
        
        // Format attachment
        if ($this->attachment_name) {
            $array['attachment'] = [
                'name' => $this->attachment_name,
                'url' => $this->attachment_url,
                'type' => $this->attachment_type,
            ];
        } else {
            $array['attachment'] = null;
        }
        
        // Format comments if loaded, otherwise return empty array
        if ($this->relationLoaded('comments')) {
            $array['comments'] = $this->comments->map(function ($comment) {
                return $comment->toArray();
            })->toArray();
        } else {
            $array['comments'] = [];
        }
        
        // Remove snake_case fields
        unset($array['ticket_id'], $array['submitted_by'], $array['submitted_date'], 
              $array['last_updated'], $array['appointment_date'], $array['appointment_time'],
              $array['attachment_name'], $array['attachment_url'], $array['attachment_type']);
        
        return $array;
    }
}
