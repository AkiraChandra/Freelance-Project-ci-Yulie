<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderContainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_type',
        'container_group',
        'container_size',
        'container_number',
        'container_type',
        'vendor',
        'combo_with',
        'combine_with_container_id',
    ];

    /**
     * Relasi ke Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke container yang di-combo (same order, same group, same type)
     */
    public function comboContainer()
    {
        return $this->belongsTo(OrderContainer::class, 'combo_with');
    }

    /**
     * Relasi ke containers yang combo dengan container ini
     */
    public function comboCombinations()
    {
        return $this->hasMany(OrderContainer::class, 'combo_with');
    }

    /**
     * Relasi ke container yang di-combine (different order, same size, same vendor, same type)
     */
    public function combineWithContainer()
    {
        return $this->belongsTo(OrderContainer::class, 'combine_with_container_id');
    }

    /**
     * Relasi ke containers yang combine dengan container ini
     */
    public function combineCombinations()
    {
        return $this->hasMany(OrderContainer::class, 'combine_with_container_id');
    }

    /**
     * Get combo container number
     */
    public function getComboContainerNumberAttribute()
    {
        return $this->comboContainer ? $this->comboContainer->container_number : null;
    }

    /**
     * Scope untuk filter by order type
     */
    public function scopeImport($query)
    {
        return $query->where('order_type', 'import');
    }

    public function scopeExport($query)
    {
        return $query->where('order_type', 'export');
    }

    /**
     * Scope untuk filter by container group
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('container_group', $group);
    }

    /**
     * Get available containers for combine (on going orders with matching criteria)
     * Status: on going, Same size, Same vendor, Same type, Different order
     */
    public static function getAvailableForCombine($currentOrderId, $containerSize, $vendor, $containerType)
    {
        return self::whereHas('order', function ($query) use ($currentOrderId) {
                $query->where('status', 'on going')
                      ->where('id', '!=', $currentOrderId);
            })
            ->where('container_size', $containerSize)
            ->where('vendor', $vendor)
            ->where('container_type', $containerType)
            ->whereNotNull('container_number')
            ->with('order')
            ->get();
    }
}
