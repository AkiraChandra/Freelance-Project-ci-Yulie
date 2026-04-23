<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'import_order_number',
        'order_date',
        'bl_number',
        'product_name',
        'shipping_line',
        'vessel_name',
        'voy_number',
        'vessel_arrival_date',
        'pib_number',
        'party',
        'port',
        'do_date',
        'container_number',
        'demurrage_date',
        'release_date',
        'container_return_date',
        'trucking_vendor',
        'issue',
        'status',
        'created_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'vessel_arrival_date' => 'date',
        'do_date' => 'date',
        'demurrage_date' => 'date',
        'release_date' => 'date',
        'container_return_date' => 'date',
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
