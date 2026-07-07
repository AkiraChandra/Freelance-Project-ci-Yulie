<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            💰 Detail Biaya Penugasan Staff - Order {{ $orderNumber }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Back Button --}}
            <a href="{{ route('staff-assignments.index') }}" 
                class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow hover:shadow-md transition text-gray-700 font-semibold mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Penugasan Staff
            </a>

            {{-- Order Info Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 mb-6 border border-white/10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-slate-300 font-medium mb-1">No. Order</p>
                        <p class="text-2xl font-bold text-white font-mono">{{ $orderNumber }}</p>
                        @if($orderType === 'export')
                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">📤 Ekspor</span>
                        @else
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">📥 Impor</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-slate-300 font-medium mb-1">Customer</p>
                        <p class="text-xl font-bold text-white">{{ $order->customer->name ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-300 font-medium mb-1">Total Biaya Keseluruhan</p>
                        <p class="text-3xl font-bold text-teal-300">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-300 mt-1">{{ $assignments->count() }} staff ditugaskan</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mb-6 flex gap-3">
                <button onclick="window.print()" 
                    class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-lg shadow border border-gray-300 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <button onclick="exportToExcel()" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </button>
            </div>

            {{-- Detail Biaya Per Staff --}}
            <div class="space-y-6">
                @foreach($assignments as $assignment)
                    @php
                        $staffName = $assignment->operationalStaff->name ?? 'Unknown Staff';
                        $staffExpenses = $assignment->expenses;
                        $staffTotal = $staffExpenses->sum('amount');
                        $groupedByDate = $staffExpenses->groupBy(function($expense) {
                            return $expense->expense_date->format('Y-m-d');
                        });
                    @endphp

                    <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-200 overflow-hidden">
                        {{-- Staff Header --}}
                        <div class="px-6 py-4 bg-gradient-to-r from-teal-600 to-teal-700 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                    {{ strtoupper(substr($staffName, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ strtoupper($staffName) }}</h3>
                                    <p class="text-xs text-teal-100">{{ $staffExpenses->count() }} biaya tercatat</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-teal-100 font-medium">Total Staff</p>
                                <p class="text-2xl font-bold text-white">Rp {{ number_format($staffTotal, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Expenses by Date --}}
                        <div class="p-6">
                            @if($groupedByDate->count() > 0)
                                <div class="space-y-4">
                                    @foreach($groupedByDate->sortKeysDesc() as $date => $dateExpenses)
                                        <div class="border-l-4 border-teal-500 pl-4 py-2">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="font-bold text-gray-800 text-sm">
                                                    📅 {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                                </h4>
                                                <span class="text-xs text-gray-500 font-medium">
                                                    {{ $dateExpenses->count() }} item
                                                </span>
                                            </div>
                                            <div class="space-y-2">
                                                @foreach($dateExpenses as $expense)
                                                    <div class="flex items-center justify-between bg-gray-50 hover:bg-teal-50/50 rounded-lg px-4 py-3 transition-colors">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                                                <span class="text-teal-700 font-bold text-xs">{{ strtoupper(substr($staffName, 0, 1)) }}</span>
                                                            </div>
                                                            <span class="text-gray-700 font-medium">{{ $expense->description }}</span>
                                                        </div>
                                                        <span class="font-bold text-teal-700 text-base">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <p class="text-sm">Belum ada biaya untuk staff ini</p>
                                </div>
                            @endif
                        </div>

                        {{-- Staff Total Footer --}}
                        <div class="px-6 py-3 bg-gray-50 border-t-2 border-gray-200 flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-600">Subtotal {{ $staffName }}</span>
                            <span class="text-lg font-bold text-teal-700">Rp {{ number_format($staffTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Grand Total --}}
            <div class="mt-8 bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 border border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-300 text-sm font-medium mb-1">GRAND TOTAL</p>
                        <p class="text-white text-lg">Order {{ $orderNumber }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-bold text-teal-300">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-300 mt-1">Total dari {{ $assignments->count() }} staff</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function exportToExcel() {
            // Simple CSV export
            let csv = 'No Order,Tanggal,Staff,Keterangan,Nominal\n';
            
            @foreach($assignments as $assignment)
                @foreach($assignment->expenses as $expense)
                    csv += '"{{ $orderNumber }}","{{ $expense->expense_date->format('d/m/Y') }}","{{ $assignment->operationalStaff->name }}","{{ $expense->description }}","{{ $expense->amount }}"\n';
                @endforeach
            @endforeach
            
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'biaya-order-{{ $orderNumber }}-{{ date("Y-m-d") }}.csv';
            link.click();
        }

        // Print styles
        window.addEventListener('beforeprint', () => {
            document.querySelector('.py-8').classList.add('print:p-4');
        });
    </script>
    @endpush

    <style>
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</x-app-layout>
