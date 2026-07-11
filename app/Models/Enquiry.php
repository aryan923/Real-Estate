<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'enq_for',
        'name',
        'email',
        'phone',
        'message',
        'tour_schedule_time',
        'tour_type',
        'property_id',
        'status',
        'action_by',
        'performed_at',
        'note',
    ];

    protected $casts = [
        'tour_schedule_time' => 'datetime',
        'performed_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}