<?php

namespace App\Models;

use App\Models\Admin\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
