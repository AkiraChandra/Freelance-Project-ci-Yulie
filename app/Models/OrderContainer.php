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
