<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'price_type',
        'price_info',
        'status',
        'featured',
        'property_code',
        'property_purpose',
        'listing_type',
        'RERA_no',
        'builder_developer',
        'bedrooms',
        'bathrooms',
        'rooms',
        'size',
        'garage_size',
        'year_built',
        'available_from',
        'exterior_material',
        'structure_type',
        'floors',
        'video_url',
        'floor_plan_url',
        'map_latitude',
        'map_longitude',
        'address_line',
        'city_id',
        'locality',
        'state_id',
        'zip',
        'country_id',
        'is_advertisement',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_advertisement' => 'boolean',
        'available_from' => 'date',
        'map_latitude' => 'decimal:7',
        'map_longitude' => 'decimal:7',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'property_categories');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'property_features');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
}
