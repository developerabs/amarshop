<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'question' => 'string',
        'answer' => 'string',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];
}
