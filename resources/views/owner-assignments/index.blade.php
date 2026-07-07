<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            ✅ Persetujuan Penugasan Staff Operasional
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen" x-data="{ search: '', declineId: null, declineFee: '', rejectionNotes: '', activeTab: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-8 bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 border border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Review Penugasan</h1>
                    <p class="text-slate-300">Setujui atau tolak request penugasan dari Staff Accounting</p>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-3">
                    <div class="relative" x-show="activeTab === 'list'">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari staff/order..."
                            class="pl-9 pr-4 py-2.5 border border-white/20 rounded-lg bg-white/10 text-white placeholder-slate-400 text-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all w-full sm:w-52">
                    </div>
                    <a href="{{ route('owner-assignments.history') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-white/15 hover:bg-white/25 text-white font-semibold rounded-lg border border-white/20 backdrop-blur-sm transition-colors text-sm">
                        📋 Lihat History
                    </a>
                </div>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div class="mb-6 bg-white rounded-xl shadow-md border border-gray-200 p-1">
                <div class="flex gap-2">
                    <button @click="activeTab = 'list'" 
                        :class="activeTab === 'list' ? 'bg-teal-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
                        class="flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        List Penugasan
                    </button>
                    <button @click="activeTab = 'report'" 
                        :class="activeTab === 'report' ? 'bg-teal-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
                        class="flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Laporan Per Order
                    </button>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div x-show="activeTab === 'list'" x-cloak class="mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-xl border border-yellow-200 shadow-sm p-5">
                        <p class="text-sm text-gray-500 font-medium">Menunggu Review</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $assignments->count() }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl border border-slate-200 shadow-sm p-5">
                        <p class="text-sm text-gray-500 font-medium">Total Biaya Pending</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($assignments->sum('total_expenses'), 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl border border-slate-200 shadow-sm p-5">
                        <p class="text-sm text-gray-500 font-medium">Staff Terlibat</p>
                        <p class="text-3xl font-bold text-teal-700 mt-1">{{ $assignments->pluck('operational_staff_id')->unique()->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div x-show="activeTab === 'list'" x-cloak class="bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200">
                @if ($assignments->count() > 0)
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50/80 border-b-2 border-yellow-200 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-yellow-500 rounded-full animate-pulse"></span>
                        <h3 class="text-lg font-bold text-gray-900">{{ $assignments->count() }} Menunggu Persetujuan</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-slate-200">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Staff Operasional</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe Order</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No. Order</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Biaya</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Diajukan Oleh</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($assignments as $assignment)
                                    @php
                                        $orderNumber = $assignment->order
                                            ? ($assignment->order_type === 'export' ? $assignment->order->export_order_number : $assignment->order->import_order_number)
                                            : '(order tidak ditemukan)';
                                    @endphp
                                    <tr class="hover:bg-amber-50 transition-colors duration-150"
                                        x-show="search === '' || '{{ strtolower($assignment->operationalStaff->name ?? '') }}'.includes(search.toLowerCase()) || '{{ strtolower($orderNumber) }}'.includes(search.toLowerCase()) || '{{ strtolower($assignment->assignedBy->name ?? '') }}'.includes(search.toLowerCase())">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-slate-700 to-teal-600 rounded-full flex items-center justify-center text-white font-bold mr-3 shadow">
                                                    {{ strtoupper(substr($assignment->operationalStaff->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $assignment->operationalStaff->name ?? '-' }}</p>
                                                    @if ($assignment->operationalStaff?->contact)
                                                        <p class="text-xs text-gray-500">{{ $assignment->operationalStaff->contact }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($assignment->order_type === 'export')
                                                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">📤 Ekspor</span>
                                            @else
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">📥 Impor</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-gray-800">{{ $orderNumber }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-gray-900">Rp {{ number_format($assignment->total_expenses, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $assignment->notes ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $assignment->assignedBy->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $assignment->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2">
                                                {{-- Approve --}}
                                                <form method="POST" action="{{ route('owner-assignments.approve', $assignment) }}"
                                                    onsubmit="return confirm('Setujui penugasan ini? Biaya sebesar Rp {{ number_format($assignment->total_expenses, 0, ',', '.') }} akan dicatat sebagai sudah dibayar.')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                                                        ✅ Setujui
                                                    </button>
                                                </form>
                                                {{-- Decline --}}
                                                <button type="button"
                                                    @click="declineId = {{ $assignment->id }}; declineFee = 'Rp {{ number_format($assignment->total_expenses, 0, ',', '.') }}'; rejectionNotes = ''"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition-colors">
                                                    ❌ Tolak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-16 text-center text-gray-400">
                        <div class="text-5xl mb-4">🎉</div>
                        <p class="text-lg font-semibold text-gray-500">Tidak ada penugasan yang menunggu review</p>
                        <p class="text-sm mt-1">Semua request sudah diproses</p>
                        <a href="{{ route('owner-assignments.history') }}" class="inline-block mt-4 text-blue-600 hover:underline text-sm font-medium">Lihat History →</a>
                    </div>
                @endif
            </div>

            {{-- Report View (Laporan Per Order) --}}
            <div x-show="activeTab === 'report'" x-cloak>
                @if ($assignments->count() > 0)
                    @php
                        // Group assignments by order
                        $ordersData = [];
                        foreach ($assignments as $assignment) {
                            $orderType = $assignment->order_type;
                            $orderId = $assignment->order_id;
                            $orderKey = "$orderType-$orderId";
                            
                            if (!isset($ordersData[$orderKey])) {
                                $order = $assignment->order;
                                $orderNumber = $order 
                                    ? ($orderType === 'export' ? $order->export_order_number : $order->import_order_number)
                                    : '(order tidak ditemukan)';
                                
                                $ordersData[$orderKey] = [
                                    'type' => $orderType,
                                    'id' => $orderId,
                                    'number' => $orderNumber,
                                    'order' => $order,
                                    'assignments' => collect(),
                                ];
                            }
                            
                            $ordersData[$orderKey]['assignments']->push($assignment);
                        }
                    @endphp

                    <div class="grid grid-cols-1 gap-6">
                        @foreach ($ordersData as $orderData)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                                {{-- Order Header --}}
                                <div class="bg-gradient-to-r from-slate-700 to-teal-700 px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold text-white mb-1">
                                                @if ($orderData['type'] === 'export')
                                                    📤 Ekspor
                                                @else
                                                    📥 Impor
                                                @endif
                                                {{ $orderData['number'] }}
                                            </h3>
                                            <p class="text-slate-200 text-sm">{{ $orderData['order']?->customer?->name ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-slate-200 text-sm">{{ count($orderData['assignments']) }} Staff</p>
                                            <p class="text-2xl font-bold text-white">Rp {{ number_format($orderData['assignments']->sum('total_expenses'), 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Assignments List --}}
                                <div class="divide-y divide-gray-200">
                                    @foreach ($orderData['assignments'] as $assignment)
                                        <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                                            {{-- Staff Name --}}
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-slate-700 to-teal-600 rounded-full flex items-center justify-center text-white font-bold shadow">
                                                    {{ strtoupper(substr($assignment->operationalStaff->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $assignment->operationalStaff->name ?? '-' }}</p>
                                                    <p class="text-xs text-gray-500">Rp {{ number_format($assignment->total_expenses, 0, ',', '.') }}</p>
                                                </div>
                                            </div>

                                            {{-- Expenses (if any) --}}
                                            @if ($assignment->expenses->count() > 0)
                                                <div class="ml-13 bg-gray-50 rounded-lg p-3 text-sm">
                                                    <p class="font-semibold text-gray-700 mb-2">📋 Detail Biaya:</p>
                                                    @php
                                                        $expensesByDate = $assignment->expenses
                                                            ->sortByDesc('expense_date')
                                                            ->groupBy(fn($e) => $e->expense_date->format('Y-m-d'));
                                                    @endphp
                                                    @foreach ($expensesByDate as $date => $dateExpenses)
                                                        <div class="mb-2">
                                                            <p class="text-xs font-bold text-gray-600">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                                                            <ul class="list-disc list-inside text-gray-600 text-xs ml-2">
                                                                @foreach ($dateExpenses as $expense)
                                                                    <li>{{ $expense->description }} - Rp {{ number_format($expense->amount, 0, ',', '.') }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center text-gray-400 bg-white rounded-2xl shadow-lg border border-gray-200">
                        <div class="text-5xl mb-4">🎉</div>
                        <p class="text-lg font-semibold text-gray-500">Tidak ada penugasan yang menunggu review</p>
                        <p class="text-sm mt-1">Semua request sudah diproses</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Decline Modal --}}
        <div x-show="declineId !== null" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @keydown.escape.window="declineId = null">
            <div class="bg-slate-50 rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4"
                @click.stop>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Tolak Penugasan</h3>
                        <p class="text-sm text-gray-500">Biaya: <span class="font-semibold text-gray-700" x-text="declineFee"></span></p>
                    </div>
                </div>

                <form :action="`/owner-assignments/${declineId}/decline`" method="POST">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Alasan Penolakan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea
                        name="rejection_notes"
                        x-model="rejectionNotes"
                        rows="3"
                        maxlength="500"
                        placeholder="Contoh: Biaya tidak sesuai, perlu dikonfirmasi ulang..."
                        class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:border-red-400 focus:ring-2 focus:ring-red-100 transition-all resize-none"></textarea>
                    <p class="text-xs text-gray-400 mt-1 text-right" x-text="rejectionNotes.length + '/500'"></p>

                    <div class="flex justify-end gap-3 mt-5">
                        <button type="button" @click="declineId = null"
                            class="px-5 py-2.5 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 text-sm transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm transition-colors shadow">
                            ❌ Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
