<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'site_title',
        'site_description',
        'site_logo',
        'site_favicon',
        'site_email',
        'site_phone',
        'site_address',
        'copyright_text'
    ];

    protected $casts = [
        'site_name' => 'string',
        'site_title' => 'string',
        'site_description' => 'string',
        'site_logo' => 'string',
        'site_favicon' => 'string',
        'site_email' => 'string',
        'site_phone' => 'string',
        'site_address' => 'string',
        'copyright_text' => 'string',
    ];
}
