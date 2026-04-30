<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            💰 Penugasan & Gaji Staff Operasional
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ search: '', showHistory: false }">

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
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Daftar Penugasan Staff</h1>
                    <p class="text-gray-600">Kelola assignment dan biaya staff operasional per orderan</p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari staff/order..."
                            class="pl-9 pr-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white text-gray-900 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-52">
                    </div>
                    <button @click="showHistory = !showHistory"
                        :class="showHistory ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-300'"
                        class="inline-flex items-center px-4 py-2.5 font-semibold rounded-lg transition-all duration-200 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="showHistory ? 'Sembunyikan History' : 'Tampilkan History'"></span>
                    </button>
                    <a href="{{ route('staff-assignments.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg shadow-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Penugasan
                    </a>
                </div>
            </div>

            {{-- Summary Cards --}}
            @php
                $pending  = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_REQUEST);
                $accepted = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_ACCEPTED);
                $declined = $assignments->where('status', \App\Models\OperationalStaffAssignment::STATUS_DECLINED);
            @endphp
            {{-- Default (pending only) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8" x-show="!showHistory">
                <div class="bg-white rounded-xl border border-yellow-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Menunggu Persetujuan</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pending->count() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Biaya Pending</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($pending->sum('fee'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Staff Terlibat</p>
                    <p class="text-3xl font-bold text-indigo-700 mt-1">{{ $pending->pluck('operational_staff_id')->unique()->count() }}</p>
                </div>
            </div>
            {{-- History (all) --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8" x-show="showHistory" x-cloak>
                <div class="bg-white rounded-xl border border-yellow-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pending->count() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-green-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Disetujui</p>
                    <p class="text-3xl font-bold text-green-700 mt-1">{{ $accepted->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($accepted->sum('fee'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl border border-red-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $declined->count() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Semua</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $assignments->count() }}</p>
                </div>
            </div>

            {{-- Assignments Table --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
                @if ($assignments->count() > 0)
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">
                            <span x-show="!showHistory">{{ $pending->count() }} Penugasan Pending</span>
                            <span x-show="showHistory" x-cloak>{{ $assignments->count() }} Semua Penugasan</span>
                        </h3>
                        <span x-show="showHistory" x-cloak class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-3 py-1 rounded-full">
                            Menampilkan semua termasuk history
                        </span>
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
                                    <tr class="hover:bg-green-50 transition-colors duration-150"
                                        x-show="
                                            (showHistory || {{ $assignment->status === \App\Models\OperationalStaffAssignment::STATUS_REQUEST ? 'true' : 'false' }}) &&
                                            (search === '' || '{{ $staffName }}'.includes(search.toLowerCase()) || '{{ strtolower($orderNumber) }}'.includes(search.toLowerCase()))
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
                                            <span class="font-bold text-green-700">Rp {{ number_format($assignment->fee, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $assignment->notes ?? '-' }}
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
                                            <div class="flex justify-center">
                                                @if ($assignment->isRequest())
                                                    <form method="POST" action="{{ route('staff-assignments.destroy', $assignment) }}"
                                                        onsubmit="return confirm('Yakin hapus penugasan ini? Request akan dibatalkan.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition-colors">
                                                            🗑️ Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Sudah difinalisasi</span>
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
    </div>
</x-app-layout>
