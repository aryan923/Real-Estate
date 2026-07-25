<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'lead_source',
        'enq_for',
        'name',
        'email',
        'phone',
        'message',
        'tour_schedule_time',
        'tour_type',
        'property_id',
        'location',
        'budget',
        'budget_type',
        'payment_mode',
        'loan_required',
        'loan_amount',
        'down_payment',
        'preferred_type',
        'bhk',
        'carpet_area',
        'floor_preference',
        'furnishing',
        'amenities',
        'other_preferences',
        'tags',
        'status',
        'action_by',
        'performed_at',
        'note',
        'agent_id',
        'lead_score',
    ];

    protected $casts = [
        'tour_schedule_time' => 'datetime',
        'performed_at' => 'datetime',
        'loan_required' => 'boolean',
        'loan_amount' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'amenities' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function siteVisits()
    {
        return $this->hasMany(SiteVisit::class);
    }

    public static function getNextRoundRobinAgentId(): ?int
    {
        $agents = User::has('agentMeta')
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        if (empty($agents)) {
            return null;
        }

        $lastEnquiry = self::whereNotNull('agent_id')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEnquiry && in_array($lastEnquiry->agent_id, $agents)) {
            $lastIndex = array_search($lastEnquiry->agent_id, $agents);
            $nextIndex = ($lastIndex + 1) % count($agents);
            return $agents[$nextIndex];
        }

        return $agents[0];
    }
}