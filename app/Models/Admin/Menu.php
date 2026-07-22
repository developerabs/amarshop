<?php

namespace App\Models\Admin;

use App\Models\Admin\MenuItem;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'location',
        'position',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('position');
    }
}
