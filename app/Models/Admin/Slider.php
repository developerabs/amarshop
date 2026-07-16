<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'button_link',
    ];
    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'image' => 'string',
        'button_text' => 'string',
        'button_link' => 'string',
    ];
}
