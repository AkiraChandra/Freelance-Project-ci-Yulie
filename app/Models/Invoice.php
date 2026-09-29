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

    public function deleteRequest()
    {
        return $this->hasOne(InvoiceDeleteRequest::class);
    }

    /**
     * Generate nota number with suffix A, B, C, etc.
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

        // Suffix: A, B, C, ... Z, AA, AB, etc.
        $suffix = self::intToAlpha($existingCount);
        return ['nota' => $baseNumber . '-' . $suffix, 'revision' => $existingCount];
    }

    /**
     * Convert integer to alphabetic suffix (1=A, 2=B, ..., 26=Z, 27=AA, 28=AB, ...)
     */
    public static function intToAlpha(int $num): string
    {
        $alpha = '';
        while ($num > 0) {
            $num--;
            $alpha = chr(65 + ($num % 26)) . $alpha;
            $num = intdiv($num, 26);
        }
        return $alpha;
    }

    public function getOrderAttribute()
    {
        if ($this->order_type === 'import') {
            return ImportOrder::with('customer')->find($this->order_id);
        }
        return ExportOrder::with('customer')->find($this->order_id);
    }

    public function sectionSubtotal(int $index): float
    {
        $sections = $this->getSectionsArray();
        if (!isset($sections[$index])) return 0;
        return collect($sections[$index]['items'] ?? [])->sum('amount');
    }

    /**
     * Get sections as array, handling both string and array types.
     */
    protected function getSectionsArray(): array
    {
        $sections = $this->sections ?? [];
        if (is_string($sections)) {
            $sections = json_decode($sections, true) ?? [];
        }
        return $sections;
    }

    public function getGrandTotalAttribute(): float
    {
        $sections = $this->getSectionsArray();
        $total = 0;
        foreach ($sections as $section) {
            if (is_array($section)) {
                $total += collect($section['items'] ?? [])->sum('amount');
            }
        }
        return $total;
    }

    public function getTaxAmountAttribute(): float
    {
        if (!$this->include_tax) return 0;

        $taxableSections = $this->taxable_sections ?? [];
        if (is_string($taxableSections)) {
            $taxableSections = json_decode($taxableSections, true) ?? [];
        }
        if (empty($taxableSections)) return 0;

        $taxableTotal = 0;
        foreach ($taxableSections as $sectionIndex) {
            $taxableTotal += $this->sectionSubtotal($sectionIndex);
        }

        return $taxableTotal * ($this->tax_percentage / 100);
    }

    public function getTotalBillingAttribute(): float
    {
        return $this->grand_total + $this->tax_amount;
    }

    public function getTotalAfterPanjarAttribute(): float
    {
        return $this->total_billing - $this->panjar;
    }

    public static function Terbilang(float $number): string
    {
        $number = abs($number);
        $words  = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($number < 12) {
            return ' ' . $words[(int)$number];
        } elseif ($number < 20) {
            return self::Terbilang($number - 10) . ' Belas';
        } elseif ($number < 100) {
            return self::Terbilang(floor($number / 10)) . ' Puluh' . self::Terbilang($number % 10);
        } elseif ($number < 200) {
            return ' Seratus' . self::Terbilang($number - 100);
        } elseif ($number < 1000) {
            return self::Terbilang(floor($number / 100)) . ' Ratus' . self::Terbilang($number % 100);
        } elseif ($number < 2000) {
            return ' Seribu' . self::Terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return self::Terbilang(floor($number / 1000)) . ' Ribu' . self::Terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return self::Terbilang(floor($number / 1000000)) . ' Juta' . self::Terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            return self::Terbilang(floor($number / 1000000000)) . ' Milyar' . self::Terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            return self::Terbilang(floor($number / 1000000000000)) . ' Triliun' . self::Terbilang($number % 1000000000000);
        }

        return '';
    }

    public function getTerbilangAttribute(): string
    {
        return trim(self::Terbilang($this->total_after_panjar)) . ' Rupiah.';
    }

    public static function romanNumeral(int $num): string
    {
        $map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
        return $map[$num - 1] ?? (string)$num;
    }
}
