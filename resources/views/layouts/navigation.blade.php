@php
    $pageTitle = isset($header) ? trim(strip_tags((string) $header)) : 'Dashboard';
    $initials = collect(preg_split('/\s+/', trim(Auth::user()->name)))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
    $accountRole = Auth::user()->hasRole('owner') ? 'Owner' : (Auth::user()->hasRole('staff-accounting') ? 'Staff Accounting' : 'Staff');
@endphp

<div x-cloak x-show="mobileOpen" x-transition.opacity @click="mobileOpen = false" class="fixed inset-0 bg-slate-950/50 z-[60] md:hidden" aria-hidden="true"></div>

<aside id="app-sidebar" class="app-sidebar bg-[#0F172A] text-white flex flex-col shrink-0 h-screen sticky top-0 z-[70] border-r border-slate-800" :class="{'is-collapsed': sidebarCollapsed, 'is-open': mobileOpen}" aria-label="Navigasi utama">
    <div class="sidebar-brand flex items-center gap-3 h-[84px] px-6 border-b border-slate-800 shrink-0">
        <a href="{{ route('dashboard') }}" class="shrink-0 w-10 h-10 bg-teal-600 rounded-xl flex items-center justify-center font-extrabold text-sm text-white tracking-wide" aria-label="Suryasumatera - Dashboard">SS</a>
        <a href="{{ route('dashboard') }}" class="sidebar-brand-copy min-w-0 leading-tight">
            <span class="block font-bold text-[15px] text-white truncate">Suryasumatera</span>
            <span class="block text-[12px] text-slate-400">Portal Operasional</span>
        </a>
        <button type="button" @click="mobileOpen = false" class="ml-auto md:hidden p-1.5 rounded-lg hover:bg-slate-800" aria-label="Tutup menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto overflow-x-hidden px-4 py-6 space-y-7">
        <div>
            <p class="sidebar-section-label px-3 mb-3 text-[11px] text-slate-400 font-bold tracking-widest uppercase">Utama</p>
            <nav class="space-y-1.5" aria-label="Utama">
                <a href="{{ route('dashboard') }}" title="Dashboard" @click="mobileOpen = false" @if(request()->routeIs('dashboard')) aria-current="page" @endif class="sidebar-link relative flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'bg-teal-500/15 text-teal-300 border border-teal-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                    @if(request()->routeIs('dashboard')) <span class="absolute left-0 top-1 bottom-1 w-1 rounded-r bg-teal-400"></span> @endif
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z"/></svg>
                    <span class="sidebar-label truncate">Dashboard</span>
                </a>
                @hasanyrole('owner|staff-accounting|staff')
                    <a href="{{ route('orders.index') }}" title="Daftar Order" @click="mobileOpen = false" @if(request()->routeIs('orders.*', 'import-orders.*', 'export-orders.*')) aria-current="page" @endif class="sidebar-link relative flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('orders.*', 'import-orders.*', 'export-orders.*') ? 'bg-teal-500/15 text-teal-300 border border-teal-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        @if(request()->routeIs('orders.*', 'import-orders.*', 'export-orders.*')) <span class="absolute left-0 top-1 bottom-1 w-1 rounded-r bg-teal-400"></span> @endif
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4h6m-5-1h4a2 2 0 0 1 2 2h3a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h3a2 2 0 0 1 2-2ZM8 11h8M8 15h8"/></svg>
                        <span class="sidebar-label truncate">Daftar Order</span>
                    </a>
                @endhasanyrole
            </nav>
        </div>

        @hasanyrole('staff-accounting|staff')
            <div>
                <p class="sidebar-section-label px-3 mb-3 text-[11px] text-slate-400 font-bold tracking-widest uppercase">Master Data</p>
                <nav class="space-y-1.5" aria-label="Master Data">
                    <a href="{{ route('customers.index') }}" title="Customer" @click="mobileOpen = false" @if(request()->routeIs('customers.*')) aria-current="page" @endif class="sidebar-link relative flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('customers.*') ? 'bg-teal-500/15 text-teal-300 border border-teal-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        @if(request()->routeIs('customers.*')) <span class="absolute left-0 top-1 bottom-1 w-1 rounded-r bg-teal-400"></span> @endif
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2h14ZM9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span class="sidebar-label truncate">Customer</span>
                    </a>
                </nav>
            </div>
        @endhasanyrole

        @role('owner')
            <div>
                <p class="sidebar-section-label px-3 mb-3 text-[11px] text-slate-400 font-bold tracking-widest uppercase">Manajemen</p>
                <nav class="space-y-1.5" aria-label="Manajemen">
                    <a href="{{ route('vendor.register') }}" title="Vendor" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('vendor.*') ? 'bg-teal-500/15 text-teal-300' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h13v11H3V7ZM16 10h3l3 4v4h-6v-8ZM7 21a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm12 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg><span class="sidebar-label truncate">Vendor</span></a>
                    <a href="{{ route('operational-staff.index') }}" title="Staff Operasional" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('operational-staff.*') ? 'bg-teal-500/15 text-teal-300' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2h14ZM9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87"/></svg><span class="sidebar-label truncate">Staff Operasional</span></a>
                    <a href="{{ route('owner-assignments.index') }}" title="Review Penugasan" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('owner-assignments.*') ? 'bg-teal-500/15 text-teal-300' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6M3 3h18v18H3V3Z"/></svg><span class="sidebar-label truncate">Review Penugasan</span></a>
                    <a href="{{ route('users.manage') }}" title="Manajemen Karyawan" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('users.*') ? 'bg-teal-500/15 text-teal-300' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 20v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2H3ZM12 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg><span class="sidebar-label truncate">Manajemen Karyawan</span></a>
                </nav>
            </div>
        @endrole

        @role('staff-accounting')
            <div>
                <p class="sidebar-section-label px-3 mb-3 text-[11px] text-slate-400 font-bold tracking-widest uppercase">Operasional</p>
                <a href="{{ route('staff-assignments.index') }}" title="Penugasan Staff" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('staff-assignments.*') ? 'bg-teal-500/15 text-teal-300' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v17H4V4ZM8 8h8M8 12h8M8 16h5"/></svg><span class="sidebar-label truncate">Penugasan Staff</span></a>
            </div>
        @endrole

        @hasanyrole('owner|staff-accounting')
            <div>
                <p class="sidebar-section-label px-3 mb-3 text-[11px] text-slate-400 font-bold tracking-widest uppercase">Keuangan</p>
                <a href="{{ route('invoices.index') }}" title="Invoice" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('invoices.*') ? 'bg-teal-500/15 text-teal-300' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3h14v18l-3-2-4 2-4-2-3 2V3Zm3 5h8m-8 4h8"/></svg><span class="sidebar-label truncate">Invoice</span></a>
            </div>
        @endhasanyrole
    </div>

    <div class="relative shrink-0 px-4 py-3 border-t border-slate-800" @click.outside="sidebarAccountOpen = false">
        <button type="button" @click="sidebarAccountOpen = !sidebarAccountOpen" :aria-expanded="sidebarAccountOpen.toString()" aria-label="Menu akun" class="sidebar-user w-full flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 text-left focus:outline-none focus:ring-2 focus:ring-teal-500">
            <span class="shrink-0 w-10 h-10 bg-slate-700 border border-slate-600 rounded-full flex items-center justify-center text-teal-300 font-semibold text-xs">{{ $initials }}</span>
            <span class="sidebar-user-copy flex-1 min-w-0"><span class="block text-[13px] text-white font-semibold truncate">{{ Auth::user()->name }}</span><span class="block text-[11px] text-teal-400">{{ $accountRole }}</span></span>
            <svg class="sidebar-chevron w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
        </button>
        <div x-cloak x-show="sidebarAccountOpen" x-transition.opacity class="absolute bottom-full mb-2 left-3 right-3 bg-white text-slate-800 border border-slate-200 shadow-lg rounded-xl py-2 z-50">
            <p class="px-4 py-2 text-xs text-slate-500 truncate border-b border-slate-100">{{ Auth::user()->email }}</p>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Profil Akun</a>
            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Keluar</button></form>
        </div>
    </div>
</aside>

<div class="app-main flex-1 flex flex-col min-h-screen">
    <header class="h-[84px] bg-white border-b border-slate-200 px-4 sm:px-7 flex items-center justify-between gap-4 shrink-0 sticky top-0 z-40">
        <div class="flex items-center gap-3 sm:gap-5 min-w-0">
            <button type="button" @click="if (window.innerWidth < 768) { mobileOpen = true } else { sidebarCollapsed = !sidebarCollapsed }" :aria-expanded="(window.innerWidth < 768 ? mobileOpen : !sidebarCollapsed).toString()" aria-controls="app-sidebar" class="p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-colors" aria-label="Toggle navigasi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h8"/></svg>
            </button>
            <span class="h-6 w-px bg-slate-200" aria-hidden="true"></span>
            <h1 class="text-lg sm:text-xl font-bold text-slate-950 truncate">{{ $pageTitle }}</h1>
        </div>
        <div class="flex items-center gap-3 sm:gap-6 shrink-0">
            <div class="hidden lg:flex items-center gap-2 text-[13px] text-slate-500 font-medium">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3v4m8-4v4M4 10h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/></svg>
                <span>{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
            </div>
            <div class="relative" @click.outside="profileOpen = false">
                <button type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen.toString()" aria-label="Buka menu profil" class="flex items-center gap-2 p-1.5 pr-3 rounded-full border border-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-colors">
                    <span class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">{{ $initials }}</span>
                    <span class="hidden sm:block max-w-40 truncate text-[13px] font-semibold text-slate-800">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-cloak x-show="profileOpen" x-transition.origin.top.right class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50">
                    <div class="px-4 py-2.5 border-b border-slate-100"><p class="font-bold text-[13px] truncate text-slate-900">{{ Auth::user()->name }}</p><p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p><p class="text-[11px] text-teal-700 font-semibold mt-1">{{ $accountRole }}</p></div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil Akun</a>
                    <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Keluar</button></form>
                </div>
            </div>
        </div>
    </header>
