<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalStaffAssignment extends Model
{
    use HasFactory;

    protected $table = 'operational_staff_assignments';

    // Status constants
    const STATUS_REQUEST  = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_DECLINED = 3;

    protected $fillable = [
        'operational_staff_id',
        'order_type',
        'order_id',
        'fee',
        'notes',
        'status',
        'assigned_by',
        'reviewed_by',
        'reviewed_at',
        'rejection_notes',
    ];

    protected $casts = [
        'fee'         => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function isRequest(): bool
    {
        return $this->status === self::STATUS_REQUEST;
    }

    public function isFinalized(): bool
    {
        return in_array($this->status, [self::STATUS_ACCEPTED, self::STATUS_DECLINED]);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_REQUEST  => 'Menunggu Persetujuan',
            self::STATUS_ACCEPTED => 'Disetujui',
            self::STATUS_DECLINED => 'Ditolak',
            default               => 'Unknown',
        };
    }

    public function operationalStaff()
    {
        return $this->belongsTo(OperationalStaff::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the related order (export or import).
     */
    public function getOrderAttribute()
    {
        if ($this->order_type === 'export') {
            return ExportOrder::find($this->order_id);
        }
        return ImportOrder::find($this->order_id);
    }
}

