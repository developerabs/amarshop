<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'type',
        'name',
        'permalink',
        'title',
        'banner',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'position',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
}
