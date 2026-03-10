<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_date',
        'company_id',
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
