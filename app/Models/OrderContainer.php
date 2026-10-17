<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'release_date',
        'return_date',
    ];

    protected $casts = [
        'release_date' => 'date',
        'return_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the container this container is combo'd with
     */
    public function comboContainer()
    {
        return $this->belongsTo(OrderContainer::class, 'combo_with');
    }

    /**
     * Get all containers that are combo'd with this container
     */
    public function comboCombinations()
    {
        return $this->hasMany(OrderContainer::class, 'combo_with');
    }

    /**
     * Get the container this container is combined with
     */
    public function combineWithContainer()
    {
        return $this->belongsTo(OrderContainer::class, 'combine_with_container_id');
    }

    /**
     * Get all containers that are combined with this container
     */
    public function combineCombinations()
    {
        return $this->hasMany(OrderContainer::class, 'combine_with_container_id');
    }

    /**
     * Check if this container can be combined with another container from different order
     * Rules:
     * - Different order
     * - Same customer
     * - Same order type
     * - Order status is "on going"
     * - Same size, type, vendor, AND container number
     */
    public function canCombineWith(self $otherContainer): bool
    {
        // Must be different order
        if ($this->order_id === $otherContainer->order_id) {
            return false;
        }

        // Must be same type
        if ($this->order_type !== $otherContainer->order_type) {
            return false;
        }

        // Must be same customer
        $thisOrder = $this->order;
        $otherOrder = $otherContainer->order;
        if (!$thisOrder || !$otherOrder || $thisOrder->customer_id !== $otherOrder->customer_id) {
            return false;
        }

        // Must be on going orders
        if ($thisOrder->status !== 'on going' || $otherOrder->status !== 'on going') {
            return false;
        }

        // Must be same size, type, vendor, AND container number
        return $this->container_size === $otherContainer->container_size
            && $this->vendor === $otherContainer->vendor
            && $this->container_type === $otherContainer->container_type
            && $this->container_number === $otherContainer->container_number
            && !empty(trim((string) $this->container_number))
            && !empty(trim((string) $otherContainer->container_number));
    }

    /**
     * Check if this container can be combo'd with another container from the same order
     */
    public function canComboWith(self $otherContainer): bool
    {
        // Must be same order
        if ($this->order_id !== $otherContainer->order_id) {
            return false;
        }

        // Must be different container
        if ($this->id === $otherContainer->id) {
            return false;
        }

        // Must be same type
        if ($this->order_type !== $otherContainer->order_type) {
            return false;
        }

        // Must be same size, type, vendor, AND different container number
        return $this->container_size === $otherContainer->container_size
            && $this->vendor === $otherContainer->vendor
            && $this->container_type === $otherContainer->container_type
            && $this->container_number !== $otherContainer->container_number
            && !empty(trim((string) $this->container_number))
            && !empty(trim((string) $otherContainer->container_number));
    }

    /**
     * Get the combo container number
     */
    public function getComboContainerNumberAttribute()
    {
        return $this->comboContainer ? $this->comboContainer->container_number : null;
    }

    /**
     * Get the combine order code and container number
     */
    public function getCombineDisplayAttribute()
    {
        if (!$this->combineWithContainer) {
            return null;
        }

        $targetOrder = $this->combineWithContainer->order;
        if (!$targetOrder) {
            return null;
        }

        return $targetOrder->order_code . '/' . $this->combineWithContainer->container_number;
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

    /**
     * Get available containers for Combine from other orders
     */
    public static function getAvailableForCombine($currentOrderId, $containerSize, $vendor, $containerType, $containerNumber)
    {
        return self::whereHas('order', function ($query) use ($currentOrderId) {
                $query->where('status', 'on going')
                      ->where('id', '!=', $currentOrderId);
            })
            ->where('container_size', $containerSize)
            ->where('vendor', $vendor)
            ->where('container_type', $containerType)
            ->where('container_number', $containerNumber)
            ->whereNotNull('container_number')
            ->where('container_number', '!=', '')
            ->with('order')
            ->get();
    }

    /**
     * Get available containers for Combo from the same order
     */
    public static function getAvailableForCombo($currentOrderId, $containerSize, $vendor, $containerType)
    {
        return self::where('order_id', $currentOrderId)
            ->where('container_size', $containerSize)
            ->where('vendor', $vendor)
            ->where('container_type', $containerType)
            ->whereNotNull('container_number')
            ->where('container_number', '!=', '')
            ->whereNull('combo_with') // Only containers not already combo'd
            ->get();
    }

    /**
     * Get detention deadline from parent order
     */
    public function getDetentionDeadlineAttribute()
    {
        $order = $this->order;
        if (!$order) return null;

        if ($this->order_type === 'import') {
            $importOrder = ImportOrder::find($order->id);
            return $importOrder ? $importOrder->detention_deadline : null;
        } else {
            $exportOrder = ExportOrder::find($order->id);
            return $exportOrder ? $exportOrder->detention_deadline : null;
        }
    }

    /**
     * Calculate usage days (return_date - release_date)
     */
    public function getUsageDaysAttribute(): ?int
    {
        if (!$this->release_date || !$this->return_date) {
            return null;
        }
        return $this->return_date->diffInDays($this->release_date);
    }

    /**
     * Check if usage exceeds 7 days
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->usage_days === null) return false;
        return $this->usage_days > 7;
    }

    /**
     * Check if past detention deadline
     */
    public function getIsPastDeadlineAttribute(): bool
    {
        if (!$this->detention_deadline || !$this->return_date) {
            return false;
        }
        return $this->return_date->gt(Carbon::parse($this->detention_deadline));
    }
}
