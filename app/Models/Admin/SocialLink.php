<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'name' => 'string',
        'url' => 'string',
        'icon' => 'string',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];
}
