<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'parent_id',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'parent_id' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_categories');
    }
}
