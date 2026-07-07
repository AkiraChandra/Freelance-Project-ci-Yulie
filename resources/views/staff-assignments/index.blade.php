<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            💰 Penugasan Staff Operasional
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ search: '', showHistory: false, activeTab: 'list' }">

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

            {{-- Header --}}
            <div class="mb-8 bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 border border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Penugasan Staff Operasional</h1>
                    <p class="text-slate-300">Assign staff ke order, lalu kelola detail biaya pengeluaran per staff</p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <div class="relative" x-show="activeTab === 'list'">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari staff/order..."
                            class="pl-9 pr-4 py-2.5 border border-white/20 rounded-lg bg-white/10 text-white placeholder-slate-400 text-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all w-full sm:w-52">
                    </div>
                    <button @click="showHistory = !showHistory" x-show="activeTab === 'list'"
                        :class="showHistory ? 'bg-teal-500 hover:bg-teal-400 text-white' : 'bg-white/15 hover:bg-white/25 text-white border border-white/20'"
                        class="inline-flex items-center px-4 py-2.5 font-semibold rounded-lg transition-all duration-200 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="showHistory ? 'Sembunyikan History' : 'Tampilkan History'"></span>
                    </button>
                    <a href="{{ route('staff-assignments.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Penugasan
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
            <div x-show="activeTab === 'list'" x-cloak>
            @php
                $pending  = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_REQUEST);
                $accepted = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED);
                $declined = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_DECLINED);
            @endphp
            {{-- Default (pending only) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8" x-show="!showHistory">
                <div class="bg-slate-50 rounded-xl border border-yellow-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Menunggu Persetujuan</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pending->count() }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl border border-slate-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Biaya Pending</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($pending->sum('fee'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl border border-slate-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Staff Terlibat</p>
                    <p class="text-3xl font-bold text-teal-700 mt-1">{{ $pending->pluck('operational_staff_id')->unique()->count() }}</p>
                </div>
            </div>
            {{-- History (all) --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8" x-show="showHistory" x-cloak>
                <div class="bg-slate-50 rounded-xl border border-yellow-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pending->count() }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl border border-green-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Disetujui</p>
                    <p class="text-3xl font-bold text-green-700 mt-1">{{ $accepted->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($accepted->sum('fee'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl border border-red-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $declined->count() }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl border border-slate-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Semua</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $assignments->count() }}</p>
                </div>
            </div>

            {{-- Assignments Table --}}
            <div class="bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200">
                @if ($assignments->count() > 0)
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-200 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">
                            <span x-show="!showHistory">{{ $pending->count() }} Penugasan Pending</span>
                            <span x-show="showHistory" x-cloak>{{ $assignments->count() }} Semua Penugasan</span>
                        </h3>
                        <span x-show="showHistory" x-cloak class="text-xs text-teal-600 font-semibold bg-teal-50 px-3 py-1 rounded-full">
                            Menampilkan semua termasuk history
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-slate-200">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Staff Operasional</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe Order</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No. Order</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total Biaya</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Di-input Oleh</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($assignments as $assignment)
                                    @php
                                        $orderNumber = $assignment->order
                                            ? ($assignment->order_type === 'export' ? $assignment->order->export_order_number : $assignment->order->import_order_number)
                                            : '(order tidak ditemukan)';
                                        $staffName = strtolower($assignment->operationalStaff->name ?? '');
                                    @endphp
                                    <tr class="hover:bg-teal-50/50 transition-colors duration-150"
                                        x-show="
                                            (showHistory || {{ $assignment->status === \App\Models\OperationalStaffAssignment::STATUS_REQUEST ? 'true' : 'false' }}) &&
                                            (search === '' || '{{ $staffName }}'.includes(search.toLowerCase()) || '{{ strtolower($orderNumber) }}'.includes(search.toLowerCase()))
                                        ">
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
                                            @php
                                                $totalExpenses = $assignment->total_expenses;
                                                $expensesCount = $assignment->expenses->count();
                                            @endphp
                                            @if($expensesCount > 0)
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-bold text-teal-700 text-base">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                                                    <span class="text-xs text-gray-500">{{ $expensesCount }} item</span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-sm text-amber-600 font-medium">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                    Belum diisi
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $assignment->assignedBy->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $assignment->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($assignment->status === \App\Models\OperationalStaffAssignment::STATUS_REQUEST)
                                                <span class="inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                                    Menunggu Persetujuan
                                                </span>
                                            @elseif ($assignment->status === \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED)
                                                <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                    Disetujui
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                    Ditolak
                                                </span>
                                                @if ($assignment->rejection_notes)
                                                    <p class="mt-1.5 text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg px-2.5 py-1.5 max-w-xs leading-relaxed">
                                                        <span class="font-semibold">Alasan:</span> {{ $assignment->rejection_notes }}
                                                    </p>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('staff-assignments.expenses.index', $assignment) }}" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-teal-100 hover:bg-teal-200 text-teal-700 text-xs font-semibold rounded-lg transition-colors">
                                                    💰 Kelola Biaya
                                                </a>
                                                @if ($assignment->isRequest())
                                                    <form method="POST" action="{{ route('staff-assignments.destroy', $assignment) }}"
                                                        onsubmit="return confirm('Yakin hapus penugasan ini? Request akan dibatalkan.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition-colors">
                                                            🗑️ Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-16 text-center text-gray-400">
                        <div class="text-5xl mb-4">💼</div>
                        <p class="text-lg font-semibold text-gray-500">Belum ada penugasan</p>
                        <p class="text-sm mt-1">Klik "Tambah Penugasan" untuk menghubungkan staff dengan orderan</p>
                    </div>
                @endif
            </div>
            </div>

            {{-- Laporan Per Order Tab --}}
            <div x-show="activeTab === 'report'" x-cloak>
                @php
                    // Group assignments by order
                    $orderGroups = $assignments
                        ->whereIn('status', [
                            \App\Models\OperationalStaffAssignment::STATUS_REQUEST,
                            \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED,
                        ])
                        ->groupBy(function($assignment) {
                            return $assignment->order_type . '_' . $assignment->order_id;
                        });
                @endphp

                @if($orderGroups->count() > 0)
                    {{-- Summary Cards for Report --}}
                    @php
                        $totalOrders = $orderGroups->count();
                        $totalStaffs = $assignments->whereIn('status', [1, 2])->pluck('operational_staff_id')->unique()->count();
                        $totalExpenses = $assignments->whereIn('status', [1, 2])->sum('total_expenses');
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-slate-50 rounded-xl border border-teal-200 shadow-sm p-5">
                            <p class="text-sm text-gray-500 font-medium">Total Order dengan Penugasan</p>
                            <p class="text-3xl font-bold text-teal-700 mt-1">{{ $totalOrders }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl border border-slate-200 shadow-sm p-5">
                            <p class="text-sm text-gray-500 font-medium">Total Staff Terlibat</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalStaffs }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl border border-teal-200 shadow-sm p-5">
                            <p class="text-sm text-gray-500 font-medium">Total Biaya Keseluruhan</p>
                            <p class="text-3xl font-bold text-teal-700 mt-1">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Order List --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-200">
                            <h3 class="text-lg font-bold text-gray-900">📊 Daftar Order dengan Biaya Detail</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Klik order untuk melihat detail biaya per staff</p>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($orderGroups as $key => $group)
                                @php
                                    $firstAssignment = $group->first();
                                    $order = $firstAssignment->order;
                                    $orderNumber = $firstAssignment->order_type === 'export' 
                                        ? $order->export_order_number ?? 'Unknown'
                                        : $order->import_order_number ?? 'Unknown';
                                    $staffCount = $group->count();
                                    $totalOrderExpenses = $group->sum('total_expenses');
                                    $customerName = $order->customer->name ?? '-';
                                @endphp
                                <div class="p-6 hover:bg-teal-50/30 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="font-mono text-lg font-bold text-gray-900">{{ $orderNumber }}</span>
                                                @if($firstAssignment->order_type === 'export')
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">📤 Ekspor</span>
                                                @else
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">📥 Impor</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3">Customer: <span class="font-semibold">{{ $customerName }}</span></p>
                                            <div class="flex items-center gap-4 text-sm">
                                                <span class="text-gray-600">
                                                    <span class="font-semibold text-teal-700">{{ $staffCount }}</span> staff ditugaskan
                                                </span>
                                                <span class="text-gray-400">•</span>
                                                <span class="text-gray-600">
                                                    Total biaya: <span class="font-bold text-teal-700">Rp {{ number_format($totalOrderExpenses, 0, ',', '.') }}</span>
                                                </span>
                                            </div>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach($group as $assignment)
                                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                                        {{ $assignment->operationalStaff->name ?? '-' }}
                                                        <span class="text-teal-600 font-semibold">Rp {{ number_format($assignment->total_expenses, 0, ',', '.') }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('staff-assignments.order-detail', ['orderType' => $firstAssignment->order_type, 'orderId' => $firstAssignment->order_id]) }}" 
                                                class="inline-flex items-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 py-16 text-center">
                        <div class="text-5xl mb-4">📊</div>
                        <p class="text-lg font-semibold text-gray-500">Belum ada laporan</p>
                        <p class="text-sm text-gray-400 mt-1">Tambahkan penugasan staff terlebih dahulu</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
