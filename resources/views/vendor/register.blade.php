<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-900 leading-tight">
            {{ __('🚚 Manajemen Vendor Trucking') }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <style>
            /* Fix for browser autofill styling */
            input:-webkit-autofill,
            input:-webkit-autofill:hover,
            input:-webkit-autofill:focus,
            input:-webkit-autofill:active {
                -webkit-box-shadow: 0 0 0 30px white inset !important;
                -webkit-text-fill-color: #000000 !important;
            }
            
            input:-webkit-autofill::first-line {
                -webkit-text-fill-color: #000000 !important;
            }

            /* Ensure text is always visible */
            input[type="text"],
            input[type="number"],
            input[type="email"],
            input[type="password"],
            input[type="date"],
            input[type="time"],
            select {
                color: #000000 !important;
            }
        </style>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ search: '' }">
            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
                    <h4 class="font-bold mb-2">⚠️ Terjadi Kesalahan:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex items-center animate-pulse">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Header Section -->
            <div class="mb-8 bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 border border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Daftar Vendor</h1>
                    <p class="text-slate-300">Kelola vendor trucking dan tarif pengiriman dengan mudah</p>
                </div>
                    <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                            </svg>
                            <input type="text" x-model="search" placeholder="Cari vendor..."
                                class="pl-9 pr-4 py-2.5 border border-white/20 rounded-lg bg-white/10 text-white placeholder-slate-400 text-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all w-full sm:w-52">
                        </div>
                        <button onclick="toggleFormSection()" class="inline-flex items-center px-6 py-3 bg-white/15 hover:bg-white/25 text-white font-bold rounded-lg border border-white/20 backdrop-blur-sm transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Vendor Baru
                        </button>
                    </div>
                </div>
            </div>

            <!-- Vendor List Card -->
            <div class="bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200 mb-8">
                @if ($vendors->count() > 0)
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900">{{ $vendors->count() }} Vendor Terdaftar</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-slate-200">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Vendor</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Lokasi/Tujuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Terdaftar</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($vendors as $vendor)
                                    <tr class="hover:bg-teal-50/50 transition-colors duration-150"
                                        x-show="search === '' || '{{ strtolower($vendor->name) }}'.includes(search.toLowerCase())">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-slate-700 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                                    {{ substr($vendor->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900 text-base">{{ $vendor->name }}</p>
                                                    <p class="text-xs text-slate-500">#{{ str_pad($vendor->id, 5, '0', STR_PAD_LEFT) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @if ($vendor->prices->count() > 0)
                                                    @foreach ($vendor->prices->take(2) as $price)
                                                        <span class="inline-block bg-teal-100 text-teal-700 text-xs font-semibold px-3 py-1 rounded-full">
                                                            📍 {{ $price->lokasi }}
                                                        </span>
                                                    @endforeach
                                                    @if ($vendor->prices->count() > 2)
                                                        <span class="inline-block bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-1 rounded-full">
                                                            +{{ $vendor->prices->count() - 2 }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-slate-500 text-xs italic">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
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
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">
                                            {{ $vendor->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('vendor.show', $vendor) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/50 text-teal-600 dark:text-teal-300 hover:bg-teal-200 dark:hover:bg-teal-900 transition-all duration-200 transform hover:scale-110" title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('vendor.edit', $vendor) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/50 text-teal-600 dark:text-teal-300 hover:bg-teal-200 dark:hover:bg-teal-900 transition-all duration-200 transform hover:scale-110" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('vendor.destroy', $vendor) }}" method="POST" class="inline" onsubmit="return confirm('🗑️ Apakah Anda yakin ingin menghapus vendor ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900 transition-all duration-200 transform hover:scale-110" title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($vendors->hasPages())
                        <div class="px-6 py-4 bg-slate-100 border-t border-slate-200">
                            {{ $vendors->links() }}
                        </div>
                    @endif
                @else
                    <div class="px-6 py-20 text-center">
                        <svg class="mx-auto h-20 w-20 text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Belum Ada Vendor</h3>
                        <p class="text-slate-600 mb-6 text-base">Mulai dengan menambahkan vendor pertama Anda sekarang</p>
                        <button onclick="toggleFormSection()" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Vendor Pertama
                        </button>
                    </div>
                @endif
            </div>

            <!-- Form Section -->
            <div id="formSection" class="hidden bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200">
                <div class="px-6 py-5 bg-gradient-to-r from-slate-800 to-teal-800 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center border border-white/20">
                            <svg class="w-5 h-5 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Daftarkan Vendor Baru</h3>
                            <p class="text-sm text-slate-300 mt-0.5">Isi identitas vendor dan tarif pengiriman.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('vendor.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <!-- Vendor Info -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm">01</div>
                            <div>
                                <h4 class="font-bold text-slate-900">Informasi Vendor</h4>
                                <p class="text-xs text-slate-500">Data utama vendor trucking.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Vendor <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                    placeholder="Contoh: PT Mitra Jaya Logistik">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Status Vendor <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex items-center gap-3 p-3 border border-green-200 bg-green-50/60 rounded-xl cursor-pointer hover:bg-green-50 transition-colors">
                                        <input type="radio" name="status" value="active" checked class="w-4 h-4 accent-teal-600">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        <span class="text-sm font-semibold text-green-800">Aktif</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 border border-slate-200 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                                        <input type="radio" name="status" value="inactive" class="w-4 h-4 accent-slate-500">
                                        <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                                        <span class="text-sm font-semibold text-slate-700">Nonaktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price List Section -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-sm">02</div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Tarif Pengiriman</h4>
                                    <p class="text-xs text-slate-500">Minimal satu lokasi tarif harus diisi.</p>
                                </div>
                            </div>
                            <button type="button" onclick="addPriceRow()"
                                class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-50 hover:bg-teal-100 text-teal-700 text-sm font-semibold rounded-xl border border-teal-200 transition-colors whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Lokasi
                            </button>
                        </div>

                        <div id="priceContainer" class="space-y-4">
                            <div class="price-row bg-slate-50 rounded-xl border border-slate-200 p-4 sm:p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi 01</span>
                                    <button type="button" onclick="removePriceRow(this)" class="inline-flex items-center text-red-600 hover:text-red-800 text-xs font-semibold transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi/Tujuan <span class="text-red-500">*</span></label>
                                        <input type="text" name="prices[0][lokasi]" required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                            placeholder="Contoh: UG, JATENG, JATIM">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status Tarif</label>
                                        <select name="prices[0][status]" class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga 20ft <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">Rp</span>
                                            <input type="number" name="prices[0][price_20]" required step="1"
                                                class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                                placeholder="1.300.000">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga 40ft <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">Rp</span>
                                            <input type="number" name="prices[0][price_40]" required step="1"
                                                class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                                placeholder="1.700.000">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga 2x20ft <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">Rp</span>
                                            <input type="number" name="prices[0][price_2x20]" required step="1"
                                                class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                                placeholder="2.100.000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('prices')
                            <p class="mt-3 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
                        <button type="button" onclick="toggleFormSection()"
                            class="px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/20 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 text-white font-semibold rounded-xl shadow transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Daftarkan Vendor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let priceRowCount = 1;

        function toggleFormSection() {
            const formSection = document.getElementById('formSection');
            formSection.classList.toggle('hidden');
            if (!formSection.classList.contains('hidden')) {
                setTimeout(() => {
                    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }

        function addPriceRow() {
            const container = document.getElementById('priceContainer');
            const newRow = document.createElement('div');
            newRow.className = 'price-row bg-slate-50 rounded-xl border border-slate-200 p-4 sm:p-5';
            newRow.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi 0${priceRowCount + 1}</span>
                    <button type="button" onclick="removePriceRow(this)" class="inline-flex items-center text-red-600 hover:text-red-800 text-xs font-semibold transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi/Tujuan <span class="text-red-500">*</span></label>
                        <input type="text" name="prices[${priceRowCount}][lokasi]" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                            placeholder="Contoh: UG, JATENG, JATIM">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status Tarif</label>
                        <select name="prices[${priceRowCount}][status]" class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga 20ft <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">Rp</span>
                            <input type="number" name="prices[${priceRowCount}][price_20]" required step="1"
                                class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                placeholder="1.300.000">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga 40ft <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">Rp</span>
                            <input type="number" name="prices[${priceRowCount}][price_40]" required step="1"
                                class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                placeholder="1.700.000">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga 2x20ft <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">Rp</span>
                            <input type="number" name="prices[${priceRowCount}][price_2x20]" required step="1"
                                class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                                placeholder="2.100.000">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            priceRowCount++;
        }

        function removePriceRow(button) {
            const container = document.getElementById('priceContainer');
            const rows = container.querySelectorAll('.price-row');
            if (rows.length > 1) {
                button.closest('.price-row').remove();
            } else {
                alert('⚠️ Minimal harus ada satu lokasi harga');
            }
        }
    </script>
</x-app-layout>
