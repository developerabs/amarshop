<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'product_name',
        'variant_name',
        'sku',
    ];
    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'product_name' => 'string',
        'variant_name' => 'string',
        'sku' => 'string',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
