<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    protected $fillable = [
        'name',
        'variant_type_id',
    ];

    protected $casts = [
        'name' => 'string',
        'variant_type_id' => 'integer',
    ];

    public function variantType()
    {
        return $this->belongsTo(VariantType::class);
    }
}
