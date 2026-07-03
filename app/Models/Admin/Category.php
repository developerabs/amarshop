<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'level',
        'status',
    ];
    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'image' => 'string',
        'parent_id' => 'integer',
        'level' => 'integer',
        'status' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
