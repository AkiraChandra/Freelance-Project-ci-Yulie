<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_code',
        'company_name',
        'pic_name',
        'phone',
        'email',
        'address',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function importOrders()
    {
        return $this->hasMany(ImportOrder::class);
    }

    public function exportOrders()
    {
        return $this->hasMany(ExportOrder::class);
    }
}
