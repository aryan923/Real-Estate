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
        'property_name',
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

    protected static function booted()
    {
        static::saving(function ($enquiry) {
            $enquiry->syncPropertyData();
        });
    }

    public function syncPropertyData(): void
    {
        if (!empty($this->property_id)) {
            $prop = Property::find($this->property_id);
            if ($prop) {
                // Populate property title string
                $this->property_name = $prop->title ?? $prop->name ?? 'N/A';

                // Auto fill floor preference if empty
                if (empty($this->floor_preference) && !empty($prop->floor)) {
                    $this->floor_preference = (string) $prop->floor;
                }
                // Auto fill BHK if empty
                if (empty($this->bhk)) {
                    if (!empty($prop->rooms)) {
                        $this->bhk = $prop->rooms . ' BHK';
                    } elseif (!empty($prop->bedrooms)) {
                        $this->bhk = $prop->bedrooms . ' BHK';
                    }
                }
                // Auto fill carpet area/size if empty
                if (empty($this->carpet_area) && !empty($prop->size)) {
                    $this->carpet_area = (string) $prop->size;
                }
                // Auto fill budget/price if empty
                if (empty($this->budget) && !empty($prop->price)) {
                    $this->budget = (string) $prop->price;
                }
                // Auto fill preferred type if empty
                if (empty($this->preferred_type)) {
                    $this->preferred_type = $prop->structure_type ?? $prop->listing_type ?? $prop->property_purpose ?? null;
                }
                // Auto fill location if empty
                if (empty($this->location)) {
                    $this->location = $prop->locality ?? $prop->address_line ?? null;
                }
            }
        } else {
            if (empty($this->property_name)) {
                $this->property_name = 'N/A';
            }
        }
    }

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

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('due_date', 'asc');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
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