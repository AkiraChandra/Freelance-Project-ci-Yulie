<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'expense_date',
        'amount',
        'description',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the assignment that owns this expense.
     */
    public function assignment()
    {
        return $this->belongsTo(OperationalStaffAssignment::class, 'assignment_id');
    }

    /**
     * Get the user who created this expense.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
