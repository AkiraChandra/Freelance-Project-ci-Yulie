<x-app-layout>
    @php
        $importOngoing = \App\Models\ImportOrder::where('status', 'on going')->count();
        $importDone    = \App\Models\ImportOrder::where('status', 'done')->count();
        $exportOngoing = \App\Models\ExportOrder::where('status', 'on going')->count();
        $exportDone    = \App\Models\ExportOrder::where('status', 'done')->count();
        $totalOrders   = \App\Models\ImportOrder::count() + \App\Models\ExportOrder::count();
        $totalCustomers = \App\Models\Customer::count();
        $totalCompanies = \App\Models\Company::count();
        $totalVendors   = \App\Models\Vendor::count();
        $totalInvoices  = \App\Models\Invoice::count();
        $activeStaff    = \App\Models\OperationalStaff::where('status', 'active')->count();
        $pendingAssignments = \App\Models\OperationalStaffAssignment::where('status', 1)->count();
        $acceptedAssignments = \App\Models\OperationalStaffAssignment::where('status', 2)->count();
    @endphp

    @unless(Auth::user()->hasRole('staff'))
    <!-- Full-width hero banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 -mt-8 pt-8">
        <div class="absolute inset-0">
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-1/4 w-72 h-72 bg-cyan-400/8 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 right-0 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl"></div>
            <svg class="absolute bottom-0 left-0 w-full opacity-100" viewBox="0 0 1440 60" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="waveGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#e2e8f0"/>
                        <stop offset="50%" style="stop-color:#f1f5f9"/>
                        <stop offset="100%" style="stop-color:#ccfbf1"/>
                    </linearGradient>
                </defs>
                <path fill="url(#waveGrad)" d="M0,60 L0,20 Q360,60 720,20 Q1080,-20 1440,20 L1440,60 Z"/>
            </svg>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 pt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/10">
                            <span class="text-teal-300 font-extrabold text-sm">SS</span>
                        </div>
                        <p class="text-teal-400 text-xs font-bold tracking-[0.2em] uppercase">PT. Suryasumatera Indahsejahtera</p>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="text-slate-400 mt-2 text-sm max-w-md">Kelola seluruh operasional EMKL — import, export, invoice, dan penugasan staff dari satu dashboard.</p>
                </div>
                <div class="mt-5 sm:mt-0 flex flex-col items-end gap-2">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 border border-white/10">
                        <p class="text-[11px] text-teal-300 font-semibold uppercase tracking-wider">{{ now()->translatedFormat('l') }}</p>
                        <p class="text-xl font-extrabold text-white">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Sistem Aktif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endunless

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 {{ Auth::user()->hasRole('staff') ? 'mt-0' : '-mt-6' }} relative z-20 pb-12">

        @role('owner')
            {{-- ===== OWNER DASHBOARD ===== --}}

            <!-- Overview Stats Row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Import</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $importOngoing }}</div>
                    <p class="text-xs text-slate-400 mt-1">On Going <span class="text-slate-300">· {{ $importDone }} selesai</span></p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-4 4m4-4l4 4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Export</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $exportOngoing }}</div>
                    <p class="text-xs text-slate-400 mt-1">On Going <span class="text-slate-300">· {{ $exportDone }} selesai</span></p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M15 11a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Staff</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $activeStaff }}</div>
                    <p class="text-xs text-slate-400 mt-1">Staff Aktif</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border {{ $pendingAssignments > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-white/80' }} p-5 hover:shadow-md hover:bg-white/80 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br {{ $pendingAssignments > 0 ? 'from-amber-500 to-orange-500 shadow-amber-500/20' : 'from-slate-400 to-slate-500 shadow-slate-400/10' }} rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        @if($pendingAssignments > 0)
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full animate-pulse">PERLU REVIEW</span>
                        @else
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Review</span>
                        @endif
                    </div>
                    <div class="text-3xl font-extrabold {{ $pendingAssignments > 0 ? 'text-amber-600' : 'text-slate-800' }}">{{ $pendingAssignments }}</div>
                    <p class="text-xs text-slate-400 mt-1">Menunggu Persetujuan</p>
                </div>
            </div>

            <!-- Feature Cards -->
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-4">Kelola Fitur</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                <!-- Orders -->
                <a href="{{ route('orders.index') }}" class="group bg-gradient-to-br from-slate-800 to-blue-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="bg-blue-500/20 text-blue-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-blue-400/20">{{ $totalOrders }} total</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Order</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Kelola semua order import & export. Lihat status, edit, dan pantau progress.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-blue-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Buka Order <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Vendors -->
                <a href="{{ route('vendor.register') }}" class="group bg-gradient-to-br from-slate-800 to-orange-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <span class="bg-orange-500/20 text-orange-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-orange-400/20">{{ $totalVendors }} vendor</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Vendor Trucking</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Daftarkan vendor baru, kelola harga, dan lihat detail vendor.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-orange-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Kelola Vendor <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Staff Operasional -->
                <a href="{{ route('operational-staff.index') }}" class="group bg-gradient-to-br from-slate-800 to-teal-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M15 11a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span class="bg-teal-500/20 text-teal-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-teal-400/20">{{ $activeStaff }} aktif</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Staff Operasional</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Tambah, edit, dan kelola staff operasional lapangan.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-teal-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Kelola Staff <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Review Penugasan -->
                <a href="{{ route('owner-assignments.index') }}" class="group bg-gradient-to-br from-slate-800 to-amber-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden {{ $pendingAssignments > 0 ? 'ring-2 ring-amber-500/30' : '' }}">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            @if($pendingAssignments > 0)
                                <span class="bg-amber-500/20 text-amber-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-amber-400/20 animate-pulse">{{ $pendingAssignments }} pending</span>
                            @else
                                <span class="bg-white/10 text-slate-400 text-xs font-bold px-2.5 py-1 rounded-lg border border-white/10">0 pending</span>
                            @endif
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Review Penugasan</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Setujui atau tolak penugasan staff dari accounting.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-amber-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Review Sekarang <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Invoice -->
                <a href="{{ route('invoices.index') }}" class="group bg-gradient-to-br from-slate-800 to-violet-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-violet-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="bg-violet-500/20 text-violet-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-violet-400/20">{{ $totalInvoices }} invoice</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Invoice</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Buat, kelola, dan cetak PDF invoice untuk setiap order.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-violet-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Buka Invoice <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Manajemen Karyawan -->
                <a href="{{ route('users.manage') }}" class="group bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                            </div>
                            <span class="bg-white/10 text-slate-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-white/10">{{ \App\Models\User::count() }} user</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Manajemen Karyawan</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Atur role dan hak akses karyawan.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Kelola User <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Statistik / Ringkasan Data -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-teal-900 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between shadow-lg text-white mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                        <svg class="w-6 h-6 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg">Ringkasan Operasional EMKL</h4>
                        <p class="text-slate-300 text-sm">{{ $totalOrders }} total order · {{ $totalCustomers }} customer aktif</p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center gap-3">
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl text-sm transition-colors shadow-sm">
                        Daftar Order
                    </a>
                    <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl backdrop-blur-sm border border-white/10 text-sm transition-colors">
                        Customer
                    </a>
                </div>
            </div>
        @endrole

        @role('staff-accounting')
            {{-- ===== STAFF ACCOUNTING DASHBOARD ===== --}}

            <!-- Stats Row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Order</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $importOngoing + $exportOngoing }}</div>
                    <p class="text-xs text-slate-400 mt-1">On Going <span class="text-slate-300">· {{ $totalOrders }} total</span></p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Invoice</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $totalInvoices }}</div>
                    <p class="text-xs text-slate-400 mt-1">Total Invoice</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M15 11a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Penugasan</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $acceptedAssignments }}</div>
                    <p class="text-xs text-slate-400 mt-1">Disetujui <span class="text-slate-300">· {{ $pendingAssignments }} pending</span></p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Customer</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $totalCustomers }}</div>
                    <p class="text-xs text-slate-400 mt-1">Customer Terdaftar</p>
                </div>
            </div>

            <!-- Feature Cards -->
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-4">Menu Utama</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                <!-- Customer -->
                <a href="{{ route('customers.index') }}" class="group bg-gradient-to-br from-slate-800 to-emerald-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="bg-emerald-500/20 text-emerald-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-emerald-400/20">{{ $totalCustomers }} data</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Customer</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Lihat, tambah, dan edit data customer untuk order dan invoice.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-emerald-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Buka Customer <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Order -->
                <a href="{{ route('orders.index') }}" class="group bg-gradient-to-br from-slate-800 to-blue-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="bg-blue-500/20 text-blue-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-blue-400/20">{{ $totalOrders }} total</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Order</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Pantau semua order import & export. Lihat status dan detail pengiriman.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-blue-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Lihat Order <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Penugasan & Gaji -->
                <a href="{{ route('staff-assignments.index') }}" class="group bg-gradient-to-br from-slate-800 to-teal-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm0 0l5-5"/></svg>
                            </div>
                            @if($pendingAssignments > 0)
                                <span class="bg-amber-500/20 text-amber-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-amber-400/20">{{ $pendingAssignments }} pending</span>
                            @else
                                <span class="bg-teal-500/20 text-teal-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-teal-400/20">{{ $acceptedAssignments }} selesai</span>
                            @endif
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Penugasan & Gaji Staff</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Buat penugasan, tetapkan fee, dan pantau persetujuan.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-teal-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Kelola Penugasan <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Invoice -->
                <a href="{{ route('invoices.index') }}" class="group bg-gradient-to-br from-slate-800 to-violet-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-violet-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="bg-violet-500/20 text-violet-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-violet-400/20">{{ $totalInvoices }} invoice</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Invoice</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Buat nota tagihan, kelola revisi, dan generate PDF.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-violet-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Buka Invoice <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Status Penugasan -->
                <a href="{{ route('staff-assignments.index') }}" class="group bg-gradient-to-br from-slate-800 to-amber-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="bg-amber-500/20 text-amber-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-amber-400/20">{{ $pendingAssignments }} pending</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Status Penugasan</h4>
                        <p class="text-sm text-slate-300 leading-relaxed">{{ $acceptedAssignments }} penugasan disetujui · {{ $pendingAssignments }} menunggu persetujuan owner.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-amber-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Pantau Penugasan <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Tambah Penugasan -->
                <a href="{{ route('staff-assignments.create') }}" class="group bg-gradient-to-br from-slate-800 to-teal-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-teal-500/20 ring-1 ring-teal-400/10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </div>
                            <span class="bg-teal-500/20 text-teal-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-teal-400/20">+ Baru</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Tambah Penugasan</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Buat penugasan baru untuk staff operasional dengan detail fee.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-teal-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Buat Sekarang <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            </div>
        @endrole

        @hasanyrole('staff')
            {{-- ===== STAFF DASHBOARD (STITCH DESIGN ALIGNED) ===== --}}
            <div class="staff-dashboard">
            <style>
                @media (prefers-reduced-motion: no-preference) {
                    .animate-fade-in-up {
                        opacity: 0;
                        animation: staffFadeInUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                    }
                    .staff-hover-lift {
                        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
                    }
                    .staff-hover-lift:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 12px 32px -4px rgba(12, 29, 63, 0.1);
                    }
                    .staff-hover-lift:active {
                        transform: scale(0.99);
                    }
                }
                @keyframes staffFadeInUp {
                    from { opacity: 0; transform: translateY(6px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
            <!-- 1. WELCOME PANEL -->
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0F172A] via-[#13283F] to-[#0A3D42] text-white p-7 border border-slate-800 shadow-sm animate-fade-in-up mb-7" style="animation-delay: 40ms;">
                <div class="relative z-10 max-w-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[11px] font-bold tracking-wider uppercase text-teal-400 bg-teal-500/10 border border-teal-500/20 px-2.5 py-0.5 rounded-full">
                            RUANG KERJA STAFF
                        </span>
                        <span class="text-[11px] text-slate-400 font-medium">PT. Suryasumatera Indahsejahtera</span>
                    </div>
                    <h2 class="text-[28px] font-extrabold text-white tracking-tight leading-tight">
                        Selamat datang, {{ Auth::user()->name }}
                    </h2>
                    <p class="text-[14px] text-slate-300 mt-2 font-normal leading-relaxed">
                        Kelola order impor, ekspor, dan data pendukung dari satu tempat.
                    </p>
                </div>
                <!-- Maritime Line Illustration -->
                <div class="absolute right-0 top-0 bottom-0 w-80 pointer-events-none opacity-20 flex items-center justify-end pr-8">
                    <svg class="w-72 h-36 text-teal-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 320 160">
                        <path d="M0 135 C 40 130, 80 140, 120 135 C 160 130, 200 140, 240 135 C 280 130, 320 140, 360 135" stroke-dasharray="2 3"></path>
                        <path d="M20 145 C 60 140, 100 150, 140 145 C 180 140, 220 150, 260 145 C 300 140, 340 150, 380 145"></path>
                        <path d="M70 125 L 250 125 L 235 90 L 110 90 Z" fill="currentColor" fill-opacity="0.08"></path>
                        <path d="M235 90 L 255 125"></path>
                        <path d="M70 125 L 110 90"></path>
                        <path d="M205 90 L 205 65 L 225 65 L 225 90"></path>
                        <path d="M210 65 L 210 50 L 218 50 L 218 65"></path>
                        <rect height="15" rx="1" width="22" x="120" y="75"></rect>
                        <rect height="15" rx="1" width="22" x="144" y="75"></rect>
                        <rect height="15" rx="1" width="22" x="168" y="75"></rect>
                        <rect height="15" rx="1" width="22" x="132" y="60"></rect>
                        <rect height="15" rx="1" width="22" x="156" y="60"></rect>
                    </svg>
                </div>
            </section>

            <!-- 2. FOUR SUMMARY CARDS -->
            <section class="animate-fade-in-up mb-7" style="animation-delay: 80ms;">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- CARD 1: Impor Berjalan -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <span class="text-[13px] font-semibold text-slate-500">Impor Berjalan</span>
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="staff-count-up text-[34px] font-extrabold text-slate-900 tracking-tight font-mono leading-none" data-target="{{ $importOngoing }}">{{ $importOngoing }}</div>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Order berstatus on going</p>
                        </div>
                    </div>
                    <!-- CARD 2: Ekspor Berjalan -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <span class="text-[13px] font-semibold text-slate-500">Ekspor Berjalan</span>
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="staff-count-up text-[34px] font-extrabold text-slate-900 tracking-tight font-mono leading-none" data-target="{{ $exportOngoing }}">{{ $exportOngoing }}</div>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Order berstatus on going</p>
                        </div>
                    </div>
                    <!-- CARD 3: Customer Terdaftar -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <span class="text-[13px] font-semibold text-slate-500">Customer Terdaftar</span>
                            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="staff-count-up text-[34px] font-extrabold text-slate-900 tracking-tight font-mono leading-none" data-target="{{ $totalCustomers }}">{{ $totalCustomers }}</div>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Total data customer</p>
                        </div>
                    </div>
                    <!-- CARD 4: ORDER SELESAI -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <span class="text-[13px] font-semibold text-slate-500">Order Selesai</span>
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="m5 12 4 4L19 6" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="staff-count-up text-[34px] font-extrabold text-slate-900 tracking-tight font-mono leading-none" data-target="{{ $importDone + $exportDone }}">{{ $importDone + $exportDone }}</div>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Total order selesai</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. PRIMARY ACTIONS — CREATE ORDERS -->
            <section class="animate-fade-in-up mb-7" style="animation-delay: 120ms;">
                <div class="mb-3.5">
                    <h2 class="text-[17px] font-bold text-slate-900">Buat Order Baru</h2>
                    <p class="text-[13px] text-slate-500">Pilih jenis order untuk mulai mengisi data pengiriman.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- IMPORT CARD -->
                    <a href="{{ route('import-orders.create') }}" class="staff-hover-lift group block rounded-2xl p-6 bg-white border border-slate-200/90 hover:border-blue-300 shadow-sm transition-all relative overflow-hidden focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-600"></div>
                        <div class="flex items-start justify-between gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M19 14l-7 7m0 0l-7-7m7 7V3" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 bg-blue-50 border border-blue-200/80 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                INBOUND EMKL
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-[18px] font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                                Order Impor
                            </h3>
                            <p class="text-[14px] text-slate-600 mt-1 leading-relaxed">
                                Buat order impor dan isi data pengiriman serta kontainer.
                            </p>
                            <div class="mt-3 inline-flex items-center gap-2 text-[12px] font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                <span>B/L • Kapal &amp; Voyage • Kontainer</span>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-blue-600 font-semibold text-[13px]">
                            <span>Buat Order Impor</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </a>
                    <!-- EXPORT CARD -->
                    <a href="{{ route('export-orders.create') }}" class="staff-hover-lift group block rounded-2xl p-6 bg-white border border-slate-200/90 hover:border-emerald-300 shadow-sm transition-all relative overflow-hidden focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-600"></div>
                        <div class="flex items-start justify-between gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                OUTBOUND EMKL
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-[18px] font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">
                                Order Ekspor
                            </h3>
                            <p class="text-[14px] text-slate-600 mt-1 leading-relaxed">
                                Buat order ekspor dan lengkapi dokumen serta data kontainer.
                            </p>
                            <div class="mt-3 inline-flex items-center gap-2 text-[12px] font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>DO • Closing Date • Kontainer</span>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-emerald-600 font-semibold text-[13px]">
                            <span>Buat Order Ekspor</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            </section>

            <!-- 4. MAIN MENU CARDS -->
            <section class="animate-fade-in-up mb-8" style="animation-delay: 160ms;">
                <div class="mb-3.5">
                    <h2 class="text-[17px] font-bold text-slate-900">Menu Utama &amp; Aktivitas</h2>
                    <p class="text-[13px] text-slate-500">Akses order dan ringkasan pengiriman terbaru.</p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <!-- CARD A: DAFTAR ORDER -->
                    <a href="{{ route('orders.index') }}" class="staff-hover-lift group flex flex-col justify-between rounded-2xl p-5 bg-white border border-slate-200/90 hover:border-slate-300 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200 group-hover:bg-teal-50 group-hover:text-teal-700 group-hover:border-teal-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <span class="text-[12px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                    {{ $importOngoing + $exportOngoing }} berjalan
                                </span>
                            </div>
                            <h3 class="text-[16px] font-bold text-slate-900 group-hover:text-teal-700 transition-colors">
                                Daftar Order
                            </h3>
                            <p class="text-[13px] text-slate-600 mt-1 leading-relaxed">
                                Lihat dan kelola seluruh order impor dan ekspor aktif.
                            </p>
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-medium">Impor &amp; Ekspor</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-medium">Status On Going</span>
                            </div>
                        </div>
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between text-slate-700 group-hover:text-teal-700 font-semibold text-[13px]">
                            <span>Lihat Daftar Order</span>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-600 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </a>

                    <!-- CARD B: ORDER TERBARU -->
                    <div class="rounded-2xl p-5 bg-white border border-slate-200/90 shadow-sm min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <h3 class="text-[15px] font-bold text-slate-900">Order Terbaru</h3>
                            <span class="text-[11px] text-slate-500">3 terakhir</span>
                        </div>
                        <div class="space-y-2.5">
                            @forelse($recentOrders as $recent)
                                @php
                                    $statusClass = match ($recent['status']) {
                                        'on going' => 'bg-teal-50 text-teal-700',
                                        'completed', 'done' => 'bg-emerald-50 text-emerald-700',
                                        'cancelled' => 'bg-rose-50 text-rose-700',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <a href="{{ $recent['edit_url'] }}" class="block p-3 rounded-xl border border-slate-100 bg-slate-50 hover:bg-teal-50/50 hover:border-teal-200 transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-[13px] font-bold text-slate-800 break-all">{{ $recent['number'] }}</p>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full shrink-0 {{ $statusClass }}">{{ ucfirst($recent['status']) }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate mt-1">{{ $recent['type'] }} · {{ $recent['customer'] }}</p>
                                </a>
                            @empty
                                <p class="text-xs text-slate-500 py-8 text-center">Belum ada order. Mulai dari Buat Order Baru.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- CARD C: TENGGAT PENGIRIMAN -->
                    <div class="rounded-2xl p-5 bg-white border border-slate-200/90 shadow-sm min-w-0">
                        <div class="mb-4">
                            <h3 class="text-[15px] font-bold text-slate-900">Tenggat Pengiriman</h3>
                            <p class="text-[11px] text-slate-500 mt-1">Order berjalan · hari ini hingga 7 hari ke depan</p>
                        </div>
                        <div class="space-y-2.5">
                            @forelse($upcomingDeadlines as $deadline)
                                <a href="{{ $deadline['edit_url'] }}" class="flex items-start gap-3 p-3 rounded-xl border border-slate-100 bg-slate-50 hover:bg-amber-50 hover:border-amber-200 transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <div class="shrink-0 rounded-lg px-2 py-1 text-center {{ $deadline['date']->isToday() ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700' }}">
                                        <p class="text-lg font-bold leading-tight tabular-nums">{{ $deadline['date']->format('d') }}</p>
                                        <p class="text-[10px] font-semibold">{{ $deadline['date']->translatedFormat('M') }}</p>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-bold text-slate-800 break-all">{{ $deadline['number'] }}</p>
                                        <p class="text-[11px] text-slate-500 mt-1">{{ $deadline['label'] }} · {{ $deadline['date']->format('d/m/Y') }}</p>
                                        @if($deadline['date']->isToday())
                                            <p class="text-[10px] font-semibold text-rose-600 mt-1">Jatuh tempo hari ini</p>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-slate-500 py-8 text-center">Tidak ada tenggat pengiriman dalam 7 hari ke depan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const counters = document.querySelectorAll('.staff-count-up');
                    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                    counters.forEach(function (counter) {
                        const target = Number(counter.dataset.target);
                        if (!Number.isFinite(target)) return;
                        if (reducedMotion || target === 0) {
                            counter.textContent = target;
                            return;
                        }

                        const duration = 700;
                        const startedAt = performance.now();
                        const tick = function (now) {
                            const progress = Math.min((now - startedAt) / duration, 1);
                            const eased = 1 - Math.pow(1 - progress, 3);
                            counter.textContent = Math.round(eased * target);
                            if (progress < 1) window.requestAnimationFrame(tick);
                        };
                        window.requestAnimationFrame(tick);
                    });
                });
            </script>
            </div>
        @endhasanyrole
    </div>
</x-app-layout>
