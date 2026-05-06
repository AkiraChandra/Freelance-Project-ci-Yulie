<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-xl border-b border-slate-200/60 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-9 h-9 bg-gradient-to-br from-slate-800 to-teal-700 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                            <span class="text-white font-extrabold text-sm">SS</span>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-base font-extrabold text-slate-800 leading-none">Suryasumatera</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-0.5 sm:-my-px sm:ms-8 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                        Dashboard
                    </a>

                    @role('owner')
                        <a href="{{ route('vendor.register') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('vendor.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Vendor
                        </a>
                        <a href="{{ route('operational-staff.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('operational-staff.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Staff
                        </a>
                        <a href="{{ route('owner-assignments.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('owner-assignments.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Review
                            @php $pendingCount = \App\Models\OperationalStaffAssignment::where('status', 1)->count(); @endphp
                            @if ($pendingCount > 0)
                                <span class="ml-1.5 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full inline-flex items-center justify-center animate-pulse">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('orders.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Order
                        </a>
                    @endrole

                    @hasanyrole('staff-accounting|staff|manager')
                        <a href="{{ route('customers.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('customers.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Customer
                        </a>
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('orders.*') || request()->routeIs('import-orders.*') || request()->routeIs('export-orders.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Order
                        </a>
                    @endhasanyrole

                    @role('staff-accounting')
                        <a href="{{ route('staff-assignments.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('staff-assignments.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Gaji Staff
                        </a>
                    @endrole

                    @hasanyrole('owner|staff-accounting')
                        <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'text-teal-700 bg-teal-50 shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                            Invoice
                        </a>
                    @endhasanyrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-slate-600 bg-white hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-slate-700 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="font-semibold text-slate-700">{{ Auth::user()->name }}</div>
                                <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-slate-100">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @role('owner')
                <x-responsive-nav-link :href="route('vendor.register')" :active="request()->routeIs('vendor.*')">
                    {{ __('Vendor') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('operational-staff.index')" :active="request()->routeIs('operational-staff.*')">
                    {{ __('Staff Operasional') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('owner-assignments.index')" :active="request()->routeIs('owner-assignments.*')">
                    {{ __('Review Penugasan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    {{ __('Order') }}
                </x-responsive-nav-link>
            @endrole
            @hasanyrole('staff-accounting|staff|manager')
                <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                    {{ __('Customer') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*') || request()->routeIs('import-orders.*') || request()->routeIs('export-orders.*')">
                    {{ __('Order') }}
                </x-responsive-nav-link>
            @endhasanyrole
            @role('staff-accounting')
                <x-responsive-nav-link :href="route('staff-assignments.index')" :active="request()->routeIs('staff-assignments.*')">
                    {{ __('Gaji Staff') }}
                </x-responsive-nav-link>
            @endrole
            @hasanyrole('owner|staff-accounting')
                <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
                    {{ __('Invoice') }}
                </x-responsive-nav-link>
            @endhasanyrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200">
            <div class="px-4">
                <div class="font-bold text-base text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
