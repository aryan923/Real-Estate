<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'profile_photo',
        'pan_number',
        'aadhaar_number',
        'occupation',
        'family_members',
        'budget_range',
        'interested_projects',
    ];

    protected $casts = [
        'family_members' => 'array',
        'interested_projects' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
