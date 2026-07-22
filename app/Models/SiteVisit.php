<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'project_id',
        'scheduled_at',
        'pickup_required',
        'pickup_address',
        'driver_name',
        'vehicle_number',
        'assigned_executive_id',
        'status',
        'feedback',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'pickup_required' => 'boolean',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedExecutive()
    {
        return $this->belongsTo(User::class, 'assigned_executive_id');
    }
}
