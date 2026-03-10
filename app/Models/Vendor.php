<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function prices()
    {
        return $this->hasMany(VendorPrice::class);
    }

    /**
     * Get active prices only
     */
    public function activePrices()
    {
        return $this->hasMany(VendorPrice::class)->where('status', 'active');
    }
}
