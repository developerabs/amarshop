<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'sort_order',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'image' => 'string',
        'status' => 'boolean',
        'sort_order' => 'integer',
        'meta_title' => 'string',
        'meta_keywords' => 'string',
        'meta_description' => 'string',
    ];
}
