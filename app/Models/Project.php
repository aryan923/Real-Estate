<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'total_units',
        'status',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
