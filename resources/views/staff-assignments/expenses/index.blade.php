<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            💰 Kelola Biaya Detail Penugasan
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Back Button --}}
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('staff-assignments.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow hover:shadow-md transition text-gray-700 font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Penugasan
                </a>
                
                @if ($assignment->order)
                    <a href="{{ route('staff-assignments.order-detail', ['orderType' => $assignment->order_type, 'orderId' => $assignment->order_id]) }}" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white rounded-lg shadow-lg transition-all font-semibold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Lihat Laporan Order Ini
                    </a>
                @endif
            </div>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
                    <h4 class="font-bold mb-2">Terjadi Kesalahan:</h4>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Assignment Info Card --}}
            @php
                $orderNumber = $assignment->order
                    ? ($assignment->order_type === 'export' ? $assignment->order->export_order_number : $assignment->order->import_order_number)
                    : '(order tidak ditemukan)';
            @endphp
            
            <div class="bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 mb-6 border border-white/10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Staff Info --}}
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            {{ strtoupper(substr($assignment->operationalStaff->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs text-slate-300 font-medium">Staff Operasional</p>
                            <p class="text-lg font-bold text-white">{{ $assignment->operationalStaff->name ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Order Info --}}
                    <div>
                        <p class="text-xs text-slate-300 font-medium mb-1">No. Order</p>
                        <p class="text-lg font-bold text-white font-mono">{{ $orderNumber }}</p>
                        @if ($assignment->order_type === 'export')
                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-0.5 rounded-full mt-1">📤 Ekspor</span>
                        @else
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full mt-1">📥 Impor</span>
                        @endif
                    </div>

                    {{-- Status --}}
                    <div>
                        <p class="text-xs text-slate-300 font-medium mb-1">Status Penugasan</p>
                        @if ($assignment->status === \App\Models\OperationalStaffAssignment::STATUS_REQUEST)
                            <span class="inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1.5 rounded-full">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                                Menunggu Persetujuan
                            </span>
                        @elseif ($assignment->status === \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED)
                            <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-sm font-semibold px-3 py-1.5 rounded-full">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 text-sm font-semibold px-3 py-1.5 rounded-full">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Form Tambah Biaya --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl border-2 border-teal-200 overflow-hidden sticky top-4">
                        <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-teal-600 border-b">
                            <h3 class="font-bold text-white text-lg">➕ Tambah Biaya Baru</h3>
                            <p class="text-xs text-teal-100 mt-1">Input biaya yang dikeluarkan untuk penugasan ini</p>
                        </div>
                        
                        <form action="{{ route('staff-assignments.expenses.store', $assignment) }}" method="POST" class="p-6 space-y-4">
                            @csrf
                            
                            <div>
                                <label for="expense_date" class="block text-sm font-bold text-gray-700 mb-2">
                                    Tanggal Biaya <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nominal (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0" step="1" required
                                    placeholder="50000"
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                                    Keterangan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}" required
                                    placeholder="tes, adm, pengeluaran barang, tanda tangan, dll"
                                    maxlength="255"
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                                <p class="text-xs text-gray-500 mt-1">Contoh: tes, adm, uang buruh, tanda tangan, pemeriksaan, karantina, gunting seal</p>
                            </div>

                            <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Simpan Biaya
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Daftar Biaya --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-5 bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-200 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">📋 Daftar Biaya</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $expenses->count() }} biaya tercatat</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium">Total Biaya</p>
                                <p class="text-2xl font-bold text-teal-700">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if ($expenses->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b-2 border-slate-200 bg-slate-50">
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tanggal</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Keterangan</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">Nominal</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @php
                                            $groupedByDate = $expenses->groupBy(fn($e) => $e->expense_date->format('Y-m-d'));
                                        @endphp
                                        @foreach ($groupedByDate as $date => $dateExpenses)
                                            <tr class="bg-gray-50">
                                                <td colspan="4" class="px-6 py-3 text-xs font-bold text-gray-600 uppercase">
                                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>
                                            @foreach ($dateExpenses as $expense)
                                                <tr class="hover:bg-teal-50/30 transition-colors">
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        {{ $expense->expense_date->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-900">
                                                        {{ $expense->description }}
                                                    </td>
                                                    <td class="px-6 py-4 text-right">
                                                        <span class="font-bold text-teal-700">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex justify-center">
                                                            @if (!$assignment->isFinalized())
                                                                <form method="POST" action="{{ route('staff-assignments.expenses.destroy', [$assignment, $expense]) }}"
                                                                    onsubmit="return confirm('Yakin hapus biaya ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded transition-colors">
                                                                        🗑️
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="text-xs text-gray-400 italic">Terkunci</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-gray-50 border-t border-gray-300">
                                                <td colspan="2" class="px-6 py-3 text-xs font-semibold text-gray-700">
                                                    Subtotal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="px-6 py-3 text-right">
                                                    <span class="font-bold text-gray-900">Rp {{ number_format($dateExpenses->sum('amount'), 0, ',', '.') }}</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-teal-50 border-t-2 border-teal-200">
                                            <td colspan="2" class="px-6 py-4 text-base font-bold text-gray-900 uppercase">
                                                TOTAL SEMUA BIAYA
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="text-2xl font-bold text-teal-700">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="py-16 text-center text-gray-400">
                                <div class="text-5xl mb-4">💸</div>
                                <p class="text-lg font-semibold text-gray-500">Belum ada biaya tercatat</p>
                                <p class="text-sm mt-1">Gunakan form di samping untuk menambahkan biaya</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
