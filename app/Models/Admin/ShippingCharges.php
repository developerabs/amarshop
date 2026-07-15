<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ShippingCharges extends Model
{
    protected $fillable = [
        'name',
        'charge',
    ];
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'charge' => 'decimal:2',
    ];
    public function getChargeAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }
}
