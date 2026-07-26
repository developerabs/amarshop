<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ClientReview extends Model
{
    protected $fillable = [
        'title',
        'description',
        'rating',
        'image',
        'video_link',
        'status',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'rating' => 'decimal:1',
        'image' => 'string',
        'video_link' => 'string',
        'status' => 'boolean',
    ];
}
