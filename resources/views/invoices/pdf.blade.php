<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 20mm 18mm 15mm 18mm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        table { border-collapse: collapse; }
        td, th { vertical-align: top; padding: 0; }
        .b { font-weight: bold; }
        .u { text-decoration: underline; }
        .r { text-align: right; }
        .c { text-align: center; }
        .i { font-style: italic; }
    </style>
</head>
<body>
    @php
        $order = $invoice->order;
        $isImport = $invoice->order_type === 'import';
        $notaNumber = $invoice->nota_number ?? '-';
        $customerName = strtoupper($order->customer->customer_name ?? '-');
        $blNumber = $isImport ? ($order->bl_number ?? '-') : ($order->shipping_number ?? '-');

        // Use invoice's own header fields (editable), fallback to order data
        $vesselName = $invoice->vessel_name ?: ($order->vessel_name ?? '-');
        $vesselDate = $invoice->vessel_date ?: ($order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') : '-');
        $destination = $invoice->destination ?: '-';
        $party = $invoice->party_display ?: ($order->party ?? '-');
        $productName = strtoupper($invoice->product_name ?: ($order->product_name ?? '-'));
        $tonage = $invoice->tonage ?: '-';
        $merk = $invoice->merk ?: ($isImport ? ('INV/No. Pendaftaran : ' . ($order->pib_number ?? '-')) : ('PEB NO. ' . ($order->peb_number ?? '-')));
        $containerNumber = $invoice->container_display ?: ($order->container_number ?? '-');
        $invoiceTitle = strtoupper($invoice->invoice_title ?? ($isImport ? 'PERINCIAN IMPORT' : 'INVOICE'));

        $allSections = $invoice->sections ?? [];
        $printSections = [];
        if ($sectionIndices === null) {
            $printSections = $allSections;
        } else {
            foreach ($sectionIndices as $idx) {
                if (isset($allSections[$idx])) {
                    $printSections[$idx] = $allSections[$idx];
                }
            }
        }

        // Format number helper
        $fmt = fn($v) => number_format((float)$v, 2, ',', '.');
    @endphp

    {{-- ═══════ HEADER ═══════ --}}
    <table style="width:100%; margin-bottom:2px;">
        <tr>
            <td style="width:58%;">
                <span class="b" style="font-size:9pt; text-transform:uppercase; letter-spacing:0.5px;">EXPEDISI MUATAN KAPAL LAUT</span><br>
                <span class="b" style="font-size:14pt;">PT. Suryasumatera Indahsejahtera</span><br>
                <span class="b u" style="font-size:10pt;">MEDAN</span>
            </td>
            <td style="width:42%; font-size:10pt;">
                Medan, {{ now()->format('d.m.Y') }}<br>
                Kepada Yth<br>
                <span class="b">{{ $customerName }}</span><br>
                <span class="b u">{{ strtoupper($order->customer->city ?? 'MEDAN') }}</span>
            </td>
        </tr>
    </table>

    <br>

    {{-- ═══════ META ═══════ --}}
    <table style="width:100%; font-size:10pt;">
        <tr>
            <td style="width:110px;">Nota No.</td>
            <td>: {{ $notaNumber }}</td>
        </tr>
    </table>
    <div style="font-size:9pt; margin:2px 0;">
        Pembayaran Ongkos2 barang tersebut di bawah ini {{ $isImport ? 'B/L-SI-AWB' : 'INV/L-SI-AWB' }} NO. {{ $blNumber }}
    </div>
    <table style="width:100%; font-size:10pt;">
        <tr>
            <td style="width:110px;">Ex/ per kapal</td>
            <td>
                : <span style="border-bottom:1px solid #000; padding:0 8px;">{{ $vesselName }}</span>
                &nbsp;&nbsp;&nbsp;Tgl : <span style="border-bottom:1px solid #000; padding:0 8px;">{{ $vesselDate }}</span>
                &nbsp;&nbsp;&nbsp;Tujuan : <span style="border-bottom:1px solid #000; padding:0 8px;">{{ $destination }}</span>
            </td>
        </tr>
        <tr>
            <td>Party</td>
            <td>
                : <span style="border-bottom:1px solid #000; padding:0 8px;">{{ $party }}</span>
                &nbsp;&nbsp;&nbsp;Jenis Barang : <span style="border-bottom:1px solid #000; padding:0 8px;">{{ $productName }}</span>
                &nbsp;&nbsp;&nbsp;Tonage : <span style="border-bottom:1px solid #000; padding:0 8px;">{{ $tonage }}</span>
            </td>
        </tr>
        <tr>
            <td>Merk</td>
            <td>
                : &nbsp;&nbsp;&nbsp;{{ $merk }}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cont : <span style="border-bottom:1px solid #000; padding:0 8px;">{{ $containerNumber }}</span>
            </td>
        </tr>
    </table>

    {{-- Divider --}}
    <div style="border-top:1.5px solid #000; margin:8px 0 2px;"></div>

    {{-- ═══════ TITLE ═══════ --}}
    <div class="c b" style="font-size:13pt; margin:12px 0 10px;">{{ $invoiceTitle }}</div>

    {{-- ═══════ ITEMS TABLE ═══════ --}}
    @php $grandTotal = 0; $sectionNum = 0; @endphp

    <table style="width:100%; font-size:10pt;">
        {{-- Column widths --}}
        <colgroup>
            <col style="width:28px;">   {{-- col 1: roman numeral --}}
            <col style="width:220px;">  {{-- col 2: item label --}}
            <col style="width:28px;">   {{-- col 3: Rp. (item) --}}
            <col style="width:110px;">  {{-- col 4: item amount --}}
            <col style="width:28px;">   {{-- col 5: Rp. (subtotal) --}}
            <col style="width:110px;">  {{-- col 6: subtotal amount --}}
        </colgroup>

        @foreach ($printSections as $sIdx => $section)
            @php $sectionNum++; $sectionSum = 0; @endphp

            {{-- Section header --}}
            <tr>
                <td class="b" style="padding-top:8px;">{{ \App\Models\Invoice::romanNumeral($sectionNum) }})</td>
                <td class="b" style="padding-top:8px;" colspan="5">{{ $section['name'] ?? '' }}</td>
            </tr>

            {{-- Items --}}
            @foreach ($section['items'] ?? [] as $item)
                @php $amt = (float)($item['amount'] ?? 0); $sectionSum += $amt; @endphp
                <tr>
                    <td></td>
                    <td style="padding-left:20px;">{{ $item['label'] ?? '' }}</td>
                    <td>Rp.</td>
                    <td class="r">{{ $fmt($amt) }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach

            {{-- Section subtotal --}}
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="border-top:1px solid #000;"></td>
                <td>Rp.</td>
                <td class="r" style="border-top:1px solid #000;">{{ $fmt($sectionSum) }}</td>
            </tr>
            @php $grandTotal += $sectionSum; @endphp
        @endforeach

        {{-- ═══════ TAX SECTION ═══════ --}}
        @if ($invoice->include_tax)
            <tr><td colspan="6" style="height:8px;"></td></tr>
            <tr>
                <td></td>
                <td style="padding-left:20px;">Handling All In</td>
                <td>Rp.</td>
                <td class="r">{{ $fmt($grandTotal) }}</td>
                <td></td>
                <td></td>
            </tr>
            @php $tax = $grandTotal * ($invoice->tax_percentage / 100); @endphp
            <tr>
                <td></td>
                <td style="padding-left:20px;">PPN {{ number_format($invoice->tax_percentage, 1, ',', '') }}%</td>
                <td style="border-bottom:1px solid #000;">Rp.</td>
                <td class="r" style="border-bottom:1px solid #000;">{{ $fmt($tax) }}</td>
                <td></td>
                <td></td>
            </tr>
            @php $grandTotal += $tax; @endphp
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Rp.</td>
                <td class="r" style="border-top:1px solid #000;">{{ $fmt($grandTotal) }}</td>
            </tr>
        @endif
    </table>

    {{-- ═══════ GRAND TOTALS ═══════ --}}
    <table style="width:100%; font-size:10pt; margin-top:8px;">
        <colgroup>
            <col style="width:386px;">  {{-- label area --}}
            <col style="width:28px;">   {{-- Rp. --}}
            <col style="width:110px;">  {{-- amount --}}
        </colgroup>
        <tr>
            <td class="b">Jumlah Tagihan Keseluruhan</td>
            <td>Rp.</td>
            <td class="r" style="border-top:1px solid #000; border-bottom:1px solid #000;">{{ $fmt($grandTotal) }}</td>
        </tr>
        @if ($invoice->panjar > 0)
            <tr>
                <td>Panjar I</td>
                <td>Rp.</td>
                <td class="r">{{ $fmt($invoice->panjar) }}</td>
            </tr>
            @php $totalTagihan = $grandTotal - $invoice->panjar; @endphp
            <tr>
                <td class="b">Total Tagihan</td>
                <td>Rp.</td>
                <td class="r" style="border-top:1px solid #000; border-bottom:1px solid #000;">{{ $fmt($totalTagihan) }}</td>
            </tr>
        @else
            @php $totalTagihan = $grandTotal; @endphp
        @endif
    </table>

    {{-- ═══════ TERBILANG ═══════ --}}
    <div style="margin-top:14px; font-size:10pt;">
        <p>Terb :</p>
        <p class="i" style="padding-left:30px;">{{ ucfirst(trim(\App\Models\Invoice::terbilang($totalTagihan))) }} rupiah.</p>
    </div>

    {{-- ═══════ FOOTER ═══════ --}}
    <table style="width:100%; font-size:10pt; margin-top:18px;">
        <tr>
            <td style="width:55%; vertical-align:top;">
                <p class="b">NB: Nota belum termasuk biaya Dangerous Cargo</p>
                <p class="b">akan segera di tagih.</p>
                <br>
                <p>Lampiran:</p>
                <p>{{ $notaNumber }}</p>
            </td>
            <td style="width:45%; text-align:center; padding-top:50px;">
                <p>(_______________________)</p>
            </td>
        </tr>
    </table>
</body>
</html>
