<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'export_order_number',
        'order_date',
        'shipping_number',
        'do_number',
        'product_name',
        'shipping_line',
        'vessel_name',
        'voy_number',
        'closing_date',
        'peb_number',
        'party',
        'depo',
        'container_number',
        'pickup_date',
        'return_date',
        'trucking_vendor',
        'issue',
        'status',
        'created_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'closing_date' => 'date',
        'pickup_date' => 'date',
        'return_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
