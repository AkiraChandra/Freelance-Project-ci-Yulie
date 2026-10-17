<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Detail Vendor') }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="mb-6 bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 border border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('vendor.register') }}"
                            class="inline-flex items-center px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-lg border border-white/20 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Kembali
                        </a>
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($vendor->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $vendor->name }}</h1>
                            <div class="flex items-center gap-3 mt-1">
                                @if ($vendor->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-500/20 text-green-300 border border-green-400/30">
                                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                                        AKTIF
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/20 text-red-300 border border-red-400/30">
                                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></span>
                                        NONAKTIF
                                    </span>
                                @endif
                                <span class="text-slate-400 text-xs">Terdaftar {{ $vendor->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('vendor.edit', $vendor) }}"
                        class="inline-flex items-center px-5 py-2.5 bg-white/15 hover:bg-white/25 text-white font-semibold rounded-xl border border-white/20 backdrop-blur-sm transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Vendor
                    </a>
                </div>
            </div>

            {{-- Summary Strip --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/80 p-5 shadow-sm hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $vendor->prices->count() }}</div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $vendor->prices->count() == 1 ? 'lokasi' : 'lokasi' }} terdaftar</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/80 p-5 shadow-sm hover:shadow-md hover:bg-white/80 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tarif Aktif</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800">{{ $vendor->prices->where('status', 'active')->count() }}</div>
                    <p class="text-xs text-slate-400 mt-0.5">dari total {{ $vendor->prices->count() }} lokasi</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/80 p-5 shadow-sm hover:shadow-md hover:bg-white/80 transition-all col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terdaftar</span>
                    </div>
                    <div class="text-xl font-extrabold text-slate-800">{{ $vendor->created_at->format('d M Y') }}</div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $vendor->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-100 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="text-lg font-bold text-slate-900">Daftar Tarif Pengiriman</h3>
                    <span class="text-xs text-slate-500 font-medium">{{ $vendor->prices->count() }} lokasi</span>
                </div>

                {{-- Price list --}}
                @if ($vendor->prices->isNotEmpty())
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <caption class="sr-only">Daftar tarif pengiriman {{ $vendor->name }}</caption>
                        <thead class="bg-slate-100 border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No.</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Lokasi</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">20ft</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">40ft</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">2x20ft</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($vendor->prices as $index => $price)
                                <tr class="hover:bg-teal-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full">
                                            {{ $price->lokasi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="inline-block bg-teal-100 text-teal-800 px-3 py-2 rounded-lg font-bold">
                                            Rp {{ number_format($price->price_20, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="inline-block bg-teal-100 text-teal-800 px-3 py-2 rounded-lg font-bold">
                                            Rp {{ number_format($price->price_40, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="inline-block bg-teal-100 text-teal-800 px-3 py-2 rounded-lg font-bold">
                                            Rp {{ number_format($price->price_2x20, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($price->status === 'active')
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                                                <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                                                AKTIF
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full">
                                                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                                NONAKTIF
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-20 text-center">
                    <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-slate-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Belum ada tarif pengiriman</h3>
                    <p class="text-slate-500 mb-6 text-sm">Tambahkan tarif untuk lokasi pengiriman vendor ini.</p>
                    <a href="{{ route('vendor.edit', $vendor) }}"
                        class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 text-white font-semibold rounded-xl transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Tarif
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
