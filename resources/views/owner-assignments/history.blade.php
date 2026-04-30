<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            📋 History Penugasan Staff Operasional
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ search: '', filterStatus: 'all' }">

            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">History Penugasan</h1>
                    <p class="text-gray-600">Penugasan yang sudah difinalisasi (disetujui / ditolak)</p>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-3 flex-wrap items-center">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari staff/order..."
                            class="pl-9 pr-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white text-gray-900 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-48">
                    </div>
                    <select x-model="filterStatus" class="px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white">
                        <option value="all">Semua Status</option>
                        <option value="accepted">✅ Disetujui</option>
                        <option value="declined">❌ Ditolak</option>
                    </select>
                    <a href="{{ route('owner-assignments.index') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-colors text-sm">
                        ⏳ Pending Review
                    </a>
                </div>
            </div>

            {{-- Summary --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                @php
                    $accepted = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED);
                    $declined = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_DECLINED);
                @endphp
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Disetujui</p>
                    <p class="text-3xl font-bold text-green-700 mt-1">{{ $accepted->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($accepted->sum('fee'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Ditolak</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $declined->count() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Dibayarkan</p>
                    <p class="text-3xl font-bold text-indigo-700 mt-1">Rp {{ number_format($accepted->sum('fee'), 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
                @if ($assignments->count() > 0)
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">{{ $assignments->count() }} Record History</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Staff Operasional</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe Order</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No. Order</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Biaya</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Diajukan Oleh</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Difinalisasi Oleh</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tgl Finalisasi</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($assignments as $assignment)
                                    @php
                                        $orderNumber  = $assignment->order
                                            ? ($assignment->order_type === 'export' ? $assignment->order->export_order_number : $assignment->order->import_order_number)
                                            : '(tidak ditemukan)';
                                        $statusKey    = $assignment->status === \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED ? 'accepted' : 'declined';
                                        $staffNameLow = strtolower($assignment->operationalStaff->name ?? '');
                                    @endphp
                                    <tr class="transition-colors duration-150"
                                        :class="{{ $assignment->status === \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED ? "'hover:bg-green-50'" : "'hover:bg-red-50'" }}"
                                        x-show="
                                            (filterStatus === 'all' || filterStatus === '{{ $statusKey }}') &&
                                            (search === '' || '{{ $staffNameLow }}'.includes(search.toLowerCase()) || '{{ strtolower($orderNumber) }}'.includes(search.toLowerCase()))
                                        ">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-3 shadow">
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
                                            <span class="font-bold {{ $assignment->status === \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED ? 'text-green-700' : 'text-gray-400 line-through' }}">
                                                Rp {{ number_format($assignment->fee, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $assignment->notes ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $assignment->assignedBy->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $assignment->reviewedBy->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $assignment->reviewed_at?->format('d/m/Y H:i') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($assignment->status === \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED)
                                                <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Disetujui
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Ditolak
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-16 text-center text-gray-400">
                        <div class="text-5xl mb-4">📭</div>
                        <p class="text-lg font-semibold text-gray-500">Belum ada history</p>
                        <p class="text-sm mt-1">History akan muncul setelah penugasan disetujui atau ditolak</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
