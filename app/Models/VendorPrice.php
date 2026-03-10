<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'lokasi',
        'price_20',
        'price_40',
        'price_2x20',
        'status',
    ];

    protected $casts = [
        'price_20' => 'decimal:2',
        'price_40' => 'decimal:2',
        'price_2x20' => 'decimal:2',
        'status' => 'string',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
