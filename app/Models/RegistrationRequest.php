<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'student_id',
        'program',
        'year_level',
        'section',
        'password',
        'document_name',
        'document_url',
        'document_type',
        'status',
        'date_submitted',
    ];

    protected $casts = [
        'date_submitted' => 'date',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the registration request's attributes for API responses
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Map database fields to frontend expectations
        $array['studentId'] = $this->student_id;
        $array['yearLevel'] = $this->year_level;
        $array['documentName'] = $this->document_name;
        $array['documentUrl'] = $this->document_url;
        $array['documentType'] = $this->document_type;
        $array['dateSubmitted'] = $this->date_submitted?->format('Y-m-d');
        
        // Remove snake_case fields
        unset($array['student_id'], $array['year_level'], $array['document_name'],
              $array['document_url'], $array['document_type'], $array['date_submitted']);
        
        return $array;
    }
}
