<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'thumbnail',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'published_at',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
