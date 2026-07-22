<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_no',
        'customer_id',
        'property_id',
        'project_id',
        'booking_amount',
        'total_agreement_value',
        'booking_date',
        'status',
        'agreement_file_path',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_amount' => 'decimal:2',
        'total_agreement_value' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
