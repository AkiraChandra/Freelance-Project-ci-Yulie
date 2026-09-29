<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDeleteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'requested_by',
        'status',
        'reviewed_by',
        'review_notes',
        'rejection_reason',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
