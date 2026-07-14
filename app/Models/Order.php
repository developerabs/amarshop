<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no',
        'user_id',
        'guest_id',
        'subtotal',
        'discount_amount',
        'coupon_discount',
        'tax_amount',
        'shipping_charge',
        'grand_total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'placed_at'
    ];
    protected $casts = [
        'user_id' => 'integer',
        'guest_id' => 'string',
        'order_no' => 'string',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'payment_method' => 'string',
        'payment_status' => 'string',
        'order_status' => 'string',
        'placed_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class);
    }
}
