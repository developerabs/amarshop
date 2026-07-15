<?php

namespace App\Models\Admin;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'admin_id',
        'code',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'cost',
        'price',
        'sale_price',
        'wholesale_price',
        'alert_quantity',
        'model',
        'discount_amount',
        'discount_type',
        'tax_rate',
        'tax_type',
        'total_stock',
        'short_description',
        'description',
        'thumbnail',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_flash_deal',
        'is_featured',
        'is_trending',
        'is_daily_offer',
        'is_best_deal',
        'is_top_sale',
        'is_new_arrival',
        'is_wholesale',
        'has_variants',
        'desc_image',
        'status',
    ];
    protected $casts = [
        'id' => 'integer',
        'admin_id' => 'integer',
        'code' => 'string',
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'cost' => 'decimal:2',
        'price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'alert_quantity' => 'integer',
        'tax_rate' => 'decimal:2',
        'tax_type' => 'string',
        'total_stock' => 'integer',
        'model' => 'string',
        'discount_amount' => 'decimal:2',
        'discount_type' => 'string',
        'short_description' => 'string',
        'description' => 'string',
        'thumbnail' => 'string',
        'image' => 'array',
        'desc_image' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_keywords' => 'string',
        'is_flash_deal' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_daily_offer' => 'boolean',
        'is_best_deal' => 'boolean',
        'is_top_sale' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_wholesale' => 'boolean',
        'has_variants' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
