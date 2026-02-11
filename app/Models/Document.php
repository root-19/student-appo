<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'name',
        'type',
        'size',
        'upload_date',
        'status',
        'user_id',
        'student_name',
        'file_path',
    ];

    protected $casts = [
        'upload_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the document's attributes for API responses
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Map database fields to frontend expectations
        $array['uploadDate'] = $this->upload_date?->format('Y-m-d');
        
        // Remove snake_case fields
        unset($array['upload_date'], $array['user_id'], $array['file_path']);
        
        return $array;
    }
}
