<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600 mt-1">Kelola order Impor dan Ekspor</p>
                </div>
                <div class="mt-4 sm:mt-0 text-sm text-gray-500">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        @role('owner')
            <!-- Owner Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Ongoing Import Orders -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">📥 Impor On Going</h3>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ \App\Models\ImportOrder::where('status', 'on going')->count() }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\ImportOrder::where('status', 'on going')->count() }}</div>
                    <p class="text-sm text-gray-600">Order impor yang sedang berlangsung</p>
                    <a href="#" class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-medium text-sm">Lihat Detail →</a>
                </div>

                <!-- Ongoing Export Orders -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">📤 Ekspor On Going</h3>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ \App\Models\ExportOrder::where('status', 'on going')->count() }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\ExportOrder::where('status', 'on going')->count() }}</div>
                    <p class="text-sm text-gray-600">Order ekspor yang sedang berlangsung</p>
                    <a href="#" class="inline-block mt-4 text-green-600 hover:text-green-700 font-medium text-sm">Lihat Detail →</a>
                </div>

                <!-- Total Companies -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">🏢 Total Perusahaan</h3>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ \App\Models\Company::count() }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\Company::count() }}</div>
                    <p class="text-sm text-gray-600">Data perusahaan/customer</p>
                    <a href="{{ route('companies.index') }}" class="inline-block mt-4 text-purple-600 hover:text-purple-700 font-medium text-sm">Lihat Detail →</a>
                </div>

                <!-- Total Vendors -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">🚚 Total Vendor</h3>
                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ \App\Models\Vendor::count() }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\Vendor::count() }}</div>
                    <p class="text-sm text-gray-600">Vendor trucking terdaftar</p>
                    <a href="{{ route('vendor.register') }}" class="inline-block mt-4 text-orange-600 hover:text-orange-700 font-medium text-sm">Kelola Vendor →</a>
                </div>

                <!-- Operational Staff -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">👷 Staff Operasional</h3>
                        <span class="bg-teal-100 text-teal-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ \App\Models\OperationalStaff::where('status', 'active')->count() }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\OperationalStaff::where('status', 'active')->count() }}</div>
                    <p class="text-sm text-gray-600">Pekerja lapangan aktif</p>
                    <a href="{{ route('operational-staff.index') }}" class="inline-block mt-4 text-teal-600 hover:text-teal-700 font-medium text-sm">Kelola Staff →</a>
                </div>

                <!-- Pending Assignments -->
                @php $pendingAssignments = \App\Models\OperationalStaffAssignment::where('status', 1)->count(); @endphp
                <div class="bg-white rounded-lg shadow-sm border {{ $pendingAssignments > 0 ? 'border-yellow-300' : 'border-slate-200' }} p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">⏳ Perlu Review</h3>
                        <span class="{{ $pendingAssignments > 0 ? 'bg-yellow-100 text-yellow-800 animate-pulse' : 'bg-gray-100 text-gray-600' }} px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $pendingAssignments }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold {{ $pendingAssignments > 0 ? 'text-yellow-600' : 'text-gray-900' }} mb-2">{{ $pendingAssignments }}</div>
                    <p class="text-sm text-gray-600">Penugasan menunggu persetujuan</p>
                    <a href="{{ route('owner-assignments.index') }}" class="inline-block mt-4 text-yellow-600 hover:text-yellow-700 font-medium text-sm">Review Sekarang →</a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-sm p-8 text-white">
                    <h2 class="text-2xl font-bold mb-2">Manajemen Karyawan, Vendor & Order</h2>
                    <p class="text-purple-100 mb-4">Kelola user, vendor, data perusahaan, dan monitor order dengan mudah</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('users.manage') }}" class="bg-white text-purple-600 font-semibold py-2 px-6 rounded-lg hover:bg-purple-50 transition-colors">
                            👥 Manajemen Karyawan
                        </a>
                        <a href="{{ route('vendor.register') }}" class="bg-white/20 text-white font-semibold py-2 px-6 rounded-lg hover:bg-white/30 transition-colors border border-white/30">
                            🚚 Manajemen Vendor
                        </a>
                        <a href="{{ route('companies.index') }}" class="bg-white/20 text-white font-semibold py-2 px-6 rounded-lg hover:bg-white/30 transition-colors border border-white/30">
                            🏢 Data Perusahaan
                        </a>
                        <a href="{{ route('operational-staff.index') }}" class="bg-white/20 text-white font-semibold py-2 px-6 rounded-lg hover:bg-white/30 transition-colors border border-white/30">
                            👷 Staff Operasional
                        </a>
                        <a href="{{ route('owner-assignments.index') }}" class="bg-white/20 text-white font-semibold py-2 px-6 rounded-lg hover:bg-white/30 transition-colors border border-white/30 flex items-center gap-2">
                            ✅ Review Penugasan
                            @if ($pendingAssignments > 0)
                                <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingAssignments }}</span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}" class="bg-white/20 text-white font-semibold py-2 px-6 rounded-lg hover:bg-white/30 transition-colors border border-white/30">
                            📋 Lihat Order
                        </a>
                    </div>
                </div>
            </div>
        @endrole

        @role('staff-accounting|staff|manager')
            <!-- Staff Dashboard -->
            @hasanyrole('staff|manager')
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Buat Order Baru</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('import-orders.create') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white hover:shadow-lg transition-shadow">
                            <div class="text-3xl mb-2">📥</div>
                            <h3 class="text-xl font-bold mb-1">Order Impor</h3>
                            <p class="text-blue-100 text-sm">Buat order impor baru</p>
                        </a>

                        <a href="{{ route('export-orders.create') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white hover:shadow-lg transition-shadow">
                            <div class="text-3xl mb-2">📤</div>
                            <h3 class="text-xl font-bold mb-1">Order Ekspor</h3>
                            <p class="text-green-100 text-sm">Buat order ekspor baru</p>
                        </a>
                    </div>
                </div>
            </div>
            @endhasanyrole

            <!-- Quick Links -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Akses Cepat</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('companies.index') }}" class="bg-purple-100 text-purple-700 font-semibold py-2 px-4 rounded-lg hover:bg-purple-200 transition-colors">
                            📋 Data Perusahaan
                        </a>
                        @role('staff-accounting')
                        <a href="{{ route('staff-assignments.index') }}" class="bg-teal-100 text-teal-700 font-semibold py-2 px-4 rounded-lg hover:bg-teal-200 transition-colors">
                            💰 Penugasan & Gaji Staff
                        </a>
                        <a href="{{ route('staff-assignments.create') }}" class="bg-green-100 text-green-700 font-semibold py-2 px-4 rounded-lg hover:bg-green-200 transition-colors">
                            ➕ Tambah Penugasan
                        </a>
                        @endrole
                        <a href="{{ route('profile.edit') }}" class="bg-gray-100 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                            ⚙️ Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        @endrole
    </div>
</x-app-layout>
