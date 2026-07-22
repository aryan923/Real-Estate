<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'receipt_no',
        'installment_stage',
        'amount_due',
        'amount_paid',
        'due_date',
        'paid_date',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
