<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('🚚 Manajemen Vendor Trucking') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Daftar Vendor</h1>
                    <p class="text-gray-600 dark:text-gray-400">Kelola vendor trucking dan tarif pengiriman dengan mudah</p>
                </div>
                <button onclick="toggleFormSection()" class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Vendor Baru
                </button>
            </div>

            <!-- Vendor List Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-8">
                @if ($vendors->count() > 0)
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 border-b-2 border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $vendors->count() }} Vendor Terdaftar</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Vendor</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Lokasi/Tujuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Terdaftar</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($vendors as $vendor)
                                    <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                                    {{ substr($vendor->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 dark:text-white text-base">{{ $vendor->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">#{{ str_pad($vendor->id, 5, '0', STR_PAD_LEFT) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @if ($vendor->prices->count() > 0)
                                                    @foreach ($vendor->prices->take(2) as $price)
                                                        <span class="inline-block bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">
                                                            📍 {{ $price->lokasi }}
                                                        </span>
                                                    @endforeach
                                                    @if ($vendor->prices->count() > 2)
                                                        <span class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold px-3 py-1 rounded-full">
                                                            +{{ $vendor->prices->count() - 2 }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400 text-xs italic">-</span>
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
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $vendor->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('vendor.show', $vendor) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-900 transition-all duration-200 transform hover:scale-110" title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('vendor.edit', $vendor) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900 transition-all duration-200 transform hover:scale-110" title="Edit">
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
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                            {{ $vendors->links() }}
                        </div>
                    @endif
                @else
                    <div class="px-6 py-20 text-center">
                        <svg class="mx-auto h-20 w-20 text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Vendor</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 text-base">Mulai dengan menambahkan vendor pertama Anda sekarang</p>
                        <button onclick="toggleFormSection()" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Vendor Pertama
                        </button>
                    </div>
                @endif
            </div>

            <!-- Form Section -->
            <div id="formSection" class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white">➕ Daftarkan Vendor Baru</h3>
                    <p class="text-blue-100 mt-1">Masukkan informasi vendor dan tarif pengiriman</p>
                </div>

                <form action="{{ route('vendor.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf

                    <!-- Vendor Name -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-900 dark:text-white mb-3">Nama Vendor <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required 
                            class="w-full px-5 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition-all" 
                            placeholder="Contoh: PT Mitra Jaya Logistik">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-4">Status Vendor <span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="status" value="active" checked class="w-5 h-5 text-blue-600 cursor-pointer accent-blue-600">
                                <span class="text-gray-700 dark:text-gray-300 font-medium">✓ Aktif</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="status" value="inactive" class="w-5 h-5 text-gray-400 cursor-pointer accent-gray-400">
                                <span class="text-gray-700 dark:text-gray-300 font-medium">✗ Non-Aktif</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price List Section -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/40 dark:to-gray-800/40 p-8 rounded-2xl border-2 border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-900 dark:text-white">Daftar Harga Lokasi <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">💡 Minimal 1 lokasi harga diperlukan</p>
                            </div>
                            <button type="button" onclick="addPriceRow()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-bold rounded-lg transition-all duration-200 transform hover:scale-105 whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Lokasi
                            </button>
                        </div>

                        <div id="priceContainer" class="space-y-5">
                            <div class="price-row bg-white dark:bg-gray-800 p-6 rounded-xl border-2 border-gray-300 dark:border-gray-600">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Lokasi/Tujuan <span class="text-red-500">*</span></label>
                                        <input type="text" name="prices[0][lokasi]" required 
                                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                                            placeholder="Contoh: UG, JATENG, JATIM">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Status</label>
                                        <select name="prices[0][status]" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900">
                                            <option value="active">✓ Aktif</option>
                                            <option value="inactive">✗ Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Harga Size 20 (Rp) <span class="text-red-500">*</span></label>
                                        <input type="number" name="prices[0][price_20]" required step="1" 
                                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                                            placeholder="1300000">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Harga Size 40 (Rp) <span class="text-red-500">*</span></label>
                                        <input type="number" name="prices[0][price_40]" required step="1" 
                                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                                            placeholder="1700000">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Harga Size 2X20 (Rp) <span class="text-red-500">*</span></label>
                                        <input type="number" name="prices[0][price_2x20]" required step="1" 
                                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                                            placeholder="2100000">
                                    </div>
                                </div>

                                <button type="button" onclick="removePriceRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-bold flex items-center transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Lokasi
                                </button>
                            </div>
                        </div>

                        @error('prices')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-4 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6">
                        <button type="button" onclick="toggleFormSection()" class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105">
                            ✓ Daftarkan Vendor
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
            newRow.className = 'price-row bg-white dark:bg-gray-800 p-6 rounded-xl border-2 border-gray-300 dark:border-gray-600';
            newRow.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Lokasi/Tujuan <span class="text-red-500">*</span></label>
                        <input type="text" name="prices[${priceRowCount}][lokasi]" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                            placeholder="Contoh: UG, JATENG, JATIM">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Status</label>
                        <select name="prices[${priceRowCount}][status]" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900">
                            <option value="active">✓ Aktif</option>
                            <option value="inactive">✗ Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Harga Size 20 (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="prices[${priceRowCount}][price_20]" required step="1" 
                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                            placeholder="1300000">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Harga Size 40 (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="prices[${priceRowCount}][price_40]" required step="1" 
                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                            placeholder="1700000">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Harga Size 2X20 (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="prices[${priceRowCount}][price_2x20]" required step="1" 
                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" 
                            placeholder="2100000">
                    </div>
                </div>

                <button type="button" onclick="removePriceRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-bold flex items-center transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Lokasi
                </button>
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
