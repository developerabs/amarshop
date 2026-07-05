<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model
{
    protected $fillable = [
        'product_variant_id',
        'attribute_name',
        'attribute_value',
    ];

    protected $casts = [
        'id' => 'integer',
        'product_variant_id' => 'integer',
        'attribute_name' => 'string',
        'attribute_value' => 'string',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
