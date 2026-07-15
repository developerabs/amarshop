<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'image',
    ];
    protected $casts = [
        'type' => 'string',
        'title' => 'string',
        'description' => 'string',
        'image' => 'string',
    ];
}
