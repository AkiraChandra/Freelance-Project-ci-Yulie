<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_type',
        'order_id',
        'nota_number',
        'invoice_title',
        'invoice_type',
        'vessel_name',
        'vessel_date',
        'destination',
        'party_display',
        'product_name',
        'tonage',
        'merk',
        'container_display',
        'revision',
        'sections',
        'panjar',
        'include_tax',
        'tax_percentage',
        'taxable_sections',
        'created_by',
    ];

    protected $casts = [
        'sections'         => 'array',
        'taxable_sections' => 'array',
        'panjar'           => 'decimal:2',
        'include_tax'      => 'boolean',
        'tax_percentage'   => 'decimal:2',
        'revision'         => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate nota number based on existing invoices for this order.
     * First invoice: same as order number (e.g. 26161/IMP/001)
     * Subsequent:   order number + A, B, C... (e.g. 26161/IMP/001A)
     */
    public static function generateNotaNumber(string $orderType, int $orderId): array
    {
        $order = $orderType === 'import'
            ? ImportOrder::find($orderId)
            : ExportOrder::find($orderId);

        $baseNumber = $orderType === 'import'
            ? ($order->import_order_number ?? '')
            : ($order->export_order_number ?? '');

        $existingCount = self::where('order_type', $orderType)
            ->where('order_id', $orderId)
            ->count();

        if ($existingCount === 0) {
            return ['nota' => $baseNumber, 'revision' => 0];
        }

        $suffix = chr(64 + $existingCount); // 1=A, 2=B, 3=C ...
        return ['nota' => $baseNumber . $suffix, 'revision' => $existingCount];
    }

    public function getOrderAttribute()
    {
        if ($this->order_type === 'import') {
            return ImportOrder::with('customer')->find($this->order_id);
        }
        return ExportOrder::with('customer')->find($this->order_id);
    }

    /**
     * Calculate subtotal for a specific section index.
     */
    public function sectionSubtotal(int $index): float
    {
        $sections = $this->sections ?? [];
        if (!isset($sections[$index])) return 0;
        return collect($sections[$index]['items'] ?? [])->sum('amount');
    }

    /**
     * Grand total of all sections.
     */
    public function getGrandTotalAttribute(): float
    {
        $sections = $this->sections ?? [];
        $total = 0;
        foreach ($sections as $section) {
            $total += collect($section['items'] ?? [])->sum('amount');
        }
        return $total;
    }

    /**
     * Tax amount - calculated only from selected taxable sections.
     * Section 0 (Reimbursement) is never included.
     * Only sections 1 (Detail Invoice) and/or 2 (Trucking) can be taxed.
     */
    public function getTaxAmountAttribute(): float
    {
        if (!$this->include_tax) return 0;
        
        $taxableSections = $this->taxable_sections ?? [];
        if (empty($taxableSections)) return 0;
        
        $taxableTotal = 0;
        foreach ($taxableSections as $sectionIndex) {
            $taxableTotal += $this->sectionSubtotal($sectionIndex);
        }
        
        return $taxableTotal * ($this->tax_percentage / 100);
    }

    /**
     * Jumlah Tagihan Keseluruhan (grand total + tax).
     */
    public function getTotalBillingAttribute(): float
    {
        return $this->grand_total + $this->tax_amount;
    }

    /**
     * Total Tagihan (after panjar deduction).
     */
    public function getTotalAfterPanjarAttribute(): float
    {
        return $this->total_billing - $this->panjar;
    }

    /**
     * Convert number to Indonesian words (terbilang).
     */
    public static function terbilang(float $number): string
    {
        $number = abs($number);
        $words  = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($number < 12) {
            return ' ' . $words[(int)$number];
        } elseif ($number < 20) {
            return self::terbilang($number - 10) . ' Belas';
        } elseif ($number < 100) {
            return self::terbilang(floor($number / 10)) . ' Puluh' . self::terbilang($number % 10);
        } elseif ($number < 200) {
            return ' Seratus' . self::terbilang($number - 100);
        } elseif ($number < 1000) {
            return self::terbilang(floor($number / 100)) . ' Ratus' . self::terbilang($number % 100);
        } elseif ($number < 2000) {
            return ' Seribu' . self::terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return self::terbilang(floor($number / 1000)) . ' Ribu' . self::terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return self::terbilang(floor($number / 1000000)) . ' Juta' . self::terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            return self::terbilang(floor($number / 1000000000)) . ' Milyar' . self::terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            return self::terbilang(floor($number / 1000000000000)) . ' Triliun' . self::terbilang($number % 1000000000000);
        }

        return '';
    }

    public function getTerbilangAttribute(): string
    {
        return trim(self::terbilang($this->total_after_panjar)) . ' Rupiah.';
    }

    public static function romanNumeral(int $num): string
    {
        $map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
        return $map[$num - 1] ?? (string)$num;
    }
}
