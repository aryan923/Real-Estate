<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'file_name',
        'file_path',
        'file_size',
        'category',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }
}
