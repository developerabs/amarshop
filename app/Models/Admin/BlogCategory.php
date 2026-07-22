<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'meta_title',
        'meta_description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }
}
