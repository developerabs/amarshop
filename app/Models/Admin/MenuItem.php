<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'name',
        'url',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
