<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('📋 Detail Vendor - ' . $vendor->name) }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="{{ route('vendor.register') }}" class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow hover:shadow-md transition-all text-black font-bold mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $vendor->name }}</h1>
                    <div class="flex items-center gap-4">
                        @if ($vendor->status === 'active')
                            <span class="px-4 py-2 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200 text-xs font-bold rounded-full inline-flex items-center">
                                <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                                AKTIF
                            </span>
                        @else
                            <span class="px-4 py-2 bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200 text-xs font-bold rounded-full inline-flex items-center">
                                <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span>
                                NONAKTIF
                            </span>
                        @endif
                        <p class="text-sm text-gray-600">{{ $vendor->prices->count() }} Lokasi • Terdaftar {{ $vendor->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('vendor.edit', $vendor) }}" class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-800 to-teal-700 hover:from-slate-900 hover:to-teal-800 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Vendor
                </a>
            </div>

            <!-- Price List Card -->
            <div class="bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200">

                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 border-b-2 border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">{{ $vendor->prices->count() }} Lokasi Pengiriman</h3>
                </div>

                @if($vendor->prices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Harga 20ft</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Harga 40ft</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Harga 2x20ft</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($vendor->prices as $index => $price)
                                <tr class="hover:bg-teal-50 dark:hover:bg-blue-900/10 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">
                                            📍 {{ $price->lokasi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <span class="inline-block bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 px-3 py-2 rounded-lg font-bold">
                                            Rp {{ number_format($price->price_20, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <span class="inline-block bg-teal-100 dark:bg-teal-900/50 text-teal-800 dark:text-teal-200 px-3 py-2 rounded-lg font-bold">
                                            Rp {{ number_format($price->price_40, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <span class="inline-block bg-emerald-100 dark:bg-teal-900/50 text-emerald-800 dark:text-teal-200 px-3 py-2 rounded-lg font-bold">
                                            Rp {{ number_format($price->price_2x20, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        @if($price->status === 'active')
                                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200 text-xs font-bold rounded-full inline-flex items-center">
                                                <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                                                AKTIF
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full">
                                                NONAKTIF
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Tidak ada data harga
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-20 text-center">
                    <svg class="mx-auto h-20 w-20 text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Data Harga</h3>
                    <p class="text-gray-600 mb-6 text-base">Tambahkan harga untuk lokasi pengiriman vendor ini</p>
                    <a href="{{ route('vendor.edit', $vendor) }}" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Harga Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
