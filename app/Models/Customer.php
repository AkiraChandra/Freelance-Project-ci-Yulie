<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'type',
        'city',
        'created_by',
    ];

    /**
     * Generate a unique 5-digit numeric customer code.
     */
    public static function generateCode(): string
    {
        do {
            $code = str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('customer_code', $code)->exists());

        return $code;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
