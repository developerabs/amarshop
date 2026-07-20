<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    protected $casts = [
        'value' => 'string',
    ];
}
