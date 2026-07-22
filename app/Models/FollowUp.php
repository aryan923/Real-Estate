<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'scheduled_at',
        'reminder_before_minutes',
        'status',
        'call_notes',
        'outcome',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
