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
        'combine_with',
        'combine_with_container_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function comboContainer()
    {
        return $this->belongsTo(OrderContainer::class, 'combo_with');
    }

    public function comboCombinations()
    {
        return $this->hasMany(OrderContainer::class, 'combo_with');
    }

    public function combineWithContainer()
    {
        return $this->belongsTo(OrderContainer::class, 'combine_with_container_id');
    }

    public function canCombineWith(self $otherContainer): bool
    {
        if ($this->order_id === $otherContainer->order_id && $this->order_type === $otherContainer->order_type) {
            return false;
        }

        return $this->container_size === $otherContainer->container_size
            && $this->vendor === $otherContainer->vendor
            && $this->container_type === $otherContainer->container_type
            && ($this->order->customer_id ?? null) === ($otherContainer->order->customer_id ?? null)
            && trim((string) $this->container_number) !== ''
            && trim((string) $otherContainer->container_number) !== ''
            && trim((string) $this->container_number) === trim((string) $otherContainer->container_number);
    }

    public function combineCombinations()
    {
        return $this->hasMany(OrderContainer::class, 'combine_with_container_id');
    }

    public function getComboContainerNumberAttribute()
    {
        return $this->comboContainer ? $this->comboContainer->container_number : null;
    }

    public function scopeImport($query)
    {
        return $query->where('order_type', 'import');
    }

    public function scopeExport($query)
    {
        return $query->where('order_type', 'export');
    }

    public function scopeGroup($query, $group)
    {
        return $query->where('container_group', $group);
    }

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
