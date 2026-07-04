<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class VariantType extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public function variantValues()
    {
        return $this->hasMany(VariantValue::class);
    }

}
