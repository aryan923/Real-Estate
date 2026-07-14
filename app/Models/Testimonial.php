<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'designation',
        'content',
        'rating',
        'image',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'integer',
    ];
}
