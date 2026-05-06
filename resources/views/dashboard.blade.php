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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20 pb-12">

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

            <!-- Data Perusahaan -->
            <div class="bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                        <svg class="w-6 h-6 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-lg">Data Perusahaan</h4>
                        <p class="text-slate-300 text-sm">{{ $totalCompanies }} perusahaan · {{ $totalCustomers }} customer terdaftar</p>
                    </div>
                </div>
                <a href="{{ route('companies.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl backdrop-blur-sm border border-white/10 transition-colors text-sm">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
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

                <!-- Data Perusahaan -->
                <a href="{{ route('companies.index') }}" class="group bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span class="bg-white/10 text-slate-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-white/10">{{ $totalCompanies }} data</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Data Perusahaan</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Lihat daftar perusahaan yang terdaftar dalam sistem.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Lihat Data <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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

        @hasanyrole('staff|manager')
            {{-- ===== STAFF / MANAGER DASHBOARD ===== --}}

            <!-- Stats Row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Import</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $importOngoing }}</div>
                    <p class="text-xs text-slate-400 mt-1">On Going</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-4 4m4-4l4 4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Export</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $exportOngoing }}</div>
                    <p class="text-xs text-slate-400 mt-1">On Going</p>
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
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-sm border border-white/80 p-5 hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center shadow-lg shadow-slate-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perusahaan</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $totalCompanies }}</div>
                    <p class="text-xs text-slate-400 mt-1">Terdaftar</p>
                </div>
            </div>

            <!-- Buat Order Baru -->
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em] mb-4">Buat Order Baru</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <a href="{{ route('import-orders.create') }}" class="group relative bg-gradient-to-br from-slate-800 to-blue-900 rounded-2xl p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all shadow-lg overflow-hidden border border-blue-500/20 ring-1 ring-blue-400/10">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <svg class="w-7 h-7 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold mb-0.5">Order Impor</h3>
                            <p class="text-slate-400 text-sm">Buat order impor baru</p>
                        </div>
                        <svg class="w-5 h-5 ml-auto text-white/30 group-hover:text-blue-300 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                <a href="{{ route('export-orders.create') }}" class="group relative bg-gradient-to-br from-slate-800 to-emerald-900 rounded-2xl p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all shadow-lg overflow-hidden border border-emerald-500/20 ring-1 ring-emerald-400/10">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <svg class="w-7 h-7 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-4 4m4-4l4 4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold mb-0.5">Order Ekspor</h3>
                            <p class="text-slate-400 text-sm">Buat order ekspor baru</p>
                        </div>
                        <svg class="w-5 h-5 ml-auto text-white/30 group-hover:text-emerald-300 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
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
                        <p class="text-sm text-slate-400 leading-relaxed">Kelola data customer untuk keperluan order.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-emerald-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Buka Customer <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Order List -->
                <a href="{{ route('orders.index') }}" class="group bg-gradient-to-br from-slate-800 to-blue-900 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </div>
                            <span class="bg-blue-500/20 text-blue-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-blue-400/20">{{ $totalOrders }} total</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Daftar Order</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Lihat semua order yang sudah dibuat beserta statusnya.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-blue-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Lihat Order <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>

                <!-- Data Perusahaan -->
                <a href="{{ route('companies.index') }}" class="group bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-[1.02] transition-all border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-500/10 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span class="bg-white/10 text-slate-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-white/10">{{ $totalCompanies }} data</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1">Data Perusahaan</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Lihat daftar perusahaan yang terdaftar di sistem.</p>
                        <div class="mt-4 flex items-center text-xs font-semibold text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity">
                            Lihat Data <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            </div>
        @endhasanyrole
    </div>
</x-app-layout>
