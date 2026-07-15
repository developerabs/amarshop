<?php

namespace App\Models;

use App\Models\Admin\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRating extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'review',
    ];
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'user_id' => 'integer',
        'rating' => 'integer',
        'review' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
