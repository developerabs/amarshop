<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'additional_price',
        'additional_cost',
        'stock',
        'image',
    ];
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'name' => 'string',
        'sku' => 'string',
        'additional_price' => 'decimal:2',
        'additional_cost' => 'decimal:2',
        'stock' => 'integer',
        'image' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantValues()
    {
        return $this->hasMany(ProductVariantValue::class);
    }
}
