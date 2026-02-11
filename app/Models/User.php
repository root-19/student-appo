<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'program',
        'year_level',
        'section',
        'student_id',
        'status',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's attributes for API responses
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Ensure ID is a string for frontend consistency
        $array['id'] = (string) $this->id;
        
        // Map database fields to frontend expectations
        $array['yearLevel'] = $this->year_level;
        $array['studentId'] = $this->student_id;
        
        // Remove snake_case fields to avoid duplication
        unset($array['year_level'], $array['student_id']);
        
        return $array;
    }
}
