<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentMeta extends Model
{
    use HasFactory;

    protected $table = 'agent_meta';

    protected $fillable = [
        'user_id',
        'title',
        'company',
        'member_of',
        'address',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'youtube',
        'vimeo',
        'whatsapp',
        'about_me',
        'specialties',
        'experience_details',
        'reach_time',
        'awards',
        'mls_id',
        'languages',
        'experience_years',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
