<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Daftar Invoice</h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola dan cetak invoice per order</p>
                    </div>
                    <a href="{{ route('invoices.create') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Invoice Baru
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                @if ($invoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Nota No</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Judul</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Tipe</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Customer</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Total Tagihan</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Dibuat</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($invoices as $invoice)
                                    @php
                                        $order = $invoice->order;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <span class="font-mono font-bold text-purple-700 bg-purple-50 px-2 py-1 rounded text-xs">
                                                {{ $invoice->nota_number ?? '-' }}
                                            </span>
                                            @if ($invoice->revision > 0)
                                                <span class="ml-1 text-xs text-amber-600 font-medium">(Rev {{ $invoice->revision }})</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 text-xs">
                                            {{ Str::limit($invoice->invoice_title, 30) ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($invoice->order_type === 'import')
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">📥 Import</span>
                                            @else
                                                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">📤 Export</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ $order->customer->customer_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 font-bold text-gray-900">
                                            Rp {{ number_format($invoice->total_after_panjar, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">
                                            {{ $invoice->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium rounded-lg text-xs transition-colors">
                                                    👁️ Lihat
                                                </a>
                                                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg text-xs transition-colors">
                                                    📄 PDF
                                                </a>
                                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                                    onsubmit="return confirm('Hapus invoice ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg text-xs transition-colors">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16 text-gray-400">
                        <div class="text-5xl mb-4">📄</div>
                        <p class="text-lg font-semibold text-gray-500">Belum ada invoice</p>
                        <a href="{{ route('invoices.create') }}" class="inline-block mt-4 text-purple-600 hover:underline text-sm font-medium">Buat Invoice Baru →</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
