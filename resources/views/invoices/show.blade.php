<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Detail Invoice</h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @php
                $order = $invoice->order;
                $isImport = $invoice->order_type === 'import';
                $notaNumber = $invoice->nota_number ?? '-';
            @endphp

            {{-- Header Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Invoice {{ $notaNumber }}</h1>
                    <p class="text-gray-500 text-sm">Dibuat {{ $invoice->created_at->format('d M Y, H:i') }} oleh {{ $invoice->creator->name ?? '-' }}</p>
                    @if ($invoice->revision > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mt-1">
                            Invoice Susulan ke-{{ $invoice->revision }}
                        </span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2" x-data="{ showPdfOptions: false }">
                    <a href="{{ route('invoices.edit', $invoice) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors shadow">
                        Edit Invoice
                    </a>
                    <a href="{{ route('invoices.revision', $invoice) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors shadow">
                        Buat Invoice Susulan
                    </a>
                    <div class="relative">
                        <button @click="showPdfOptions = !showPdfOptions"
                            class="inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                            📄 Cetak PDF ▾
                        </button>
                        <div x-show="showPdfOptions" x-cloak @click.away="showPdfOptions = false"
                            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-xl z-50 p-2">
                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                📋 Cetak Semua Section
                            </a>
                            @foreach ($invoice->sections as $idx => $section)
                                <a href="{{ route('invoices.pdf', [$invoice, 'sections' => $idx]) }}" target="_blank"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                    📝 Cetak: {{ $section['name'] ?? 'Section '.($idx+1) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('invoices.index') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        ← Kembali
                    </a>
                </div>
            </div>

            {{-- Order Info Card --}}
            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-6 mb-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">📋 Data Invoice</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Tipe</p>
                        <p class="font-semibold">{{ $isImport ? '📥 Import' : '📤 Export' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Nota No</p>
                        <p class="font-semibold font-mono">{{ $notaNumber }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Judul</p>
                        <p class="font-semibold">{{ $invoice->invoice_title ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="font-semibold">{{ $order->customer->customer_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Ex/ per kapal</p>
                        <p class="font-semibold">{{ $invoice->vessel_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tgl</p>
                        <p class="font-semibold">{{ $invoice->vessel_date ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Party</p>
                        <p class="font-semibold">{{ $invoice->party_display ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Jenis Barang</p>
                        <p class="font-semibold">{{ $invoice->product_name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Sections --}}
            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-6 mb-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">📝 Perincian</h3>
                @foreach ($invoice->sections as $idx => $section)
                    <div class="mb-6 {{ !$loop->last ? 'border-b pb-6' : '' }}">
                        <h4 class="font-bold text-gray-800 mb-3">
                            {{ \App\Models\Invoice::romanNumeral($idx + 1) }}) {{ $section['name'] ?? '' }}
                        </h4>
                        <table class="w-full text-sm">
                            <tbody>
                                @php $sectionSum = 0; @endphp
                                @foreach ($section['items'] ?? [] as $item)
                                    @php $sectionSum += $item['amount'] ?? 0; @endphp
                                    <tr class="border-b border-gray-100">
                                        <td class="py-1.5 pl-8 text-gray-700">{{ $item['label'] ?? '' }}</td>
                                        <td class="py-1.5 text-right font-mono text-gray-800 w-40">
                                            Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-t-2 border-gray-300">
                                    <td class="py-2 pl-8 font-semibold text-gray-800">Subtotal</td>
                                    <td class="py-2 text-right font-mono font-bold text-gray-900 w-40">
                                        Rp {{ number_format($sectionSum, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="bg-gradient-to-br from-teal-50 to-emerald-50 border-2 border-teal-200 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Ringkasan Tagihan</h3>
                <table class="w-full text-sm">
                    <tr>
                        <td class="py-1 text-gray-600">Grand Total</td>
                        <td class="py-1 text-right font-semibold">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                    </tr>
                    @if ($invoice->include_tax)
                        <tr>
                            <td class="py-1 text-gray-600">Pajak {{ $invoice->tax_percentage }}%</td>
                            <td class="py-1 text-right">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="border-t border-teal-200">
                        <td class="py-2 font-bold">Jumlah Tagihan Keseluruhan</td>
                        <td class="py-2 text-right font-bold text-lg text-teal-700">Rp {{ number_format($invoice->total_billing, 0, ',', '.') }}</td>
                    </tr>
                    @if ($invoice->panjar > 0)
                        <tr>
                            <td class="py-1 text-gray-600">Panjar</td>
                            <td class="py-1 text-right text-red-600">- Rp {{ number_format($invoice->panjar, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-t border-teal-300">
                            <td class="py-2 font-bold text-lg">Total Tagihan</td>
                            <td class="py-2 text-right font-bold text-xl text-green-700">Rp {{ number_format($invoice->total_after_panjar, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </table>
                <p class="mt-3 text-sm italic text-gray-600">Terbilang: {{ $invoice->terbilang }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
