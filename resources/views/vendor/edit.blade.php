<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('✏️ Edit Vendor - ' . $vendor->name) }}
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
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <a href="{{ route('vendor.register') }}" class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow hover:shadow-md transition-all text-black font-bold mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-4xl font-bold text-gray-900">{{ $vendor->name }}</h1>
                <p class="text-gray-600 mt-2">Pembaruan vendor dan daftar harga lokasi</p>
            </div>

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
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Edit Form -->
            <form action="{{ route('vendor.update', $vendor) }}" method="POST" id="mainForm" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Vendor Info Card -->
                <div class="bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200">
                    <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 border-b-2 border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-0">Informasi Vendor</h3>
                    </div>

                    <div class="p-8 space-y-6">
                        <!-- Vendor Name -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-900 mb-3">Nama Vendor <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required 
                                class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all font-semibold" 
                                placeholder="Contoh: PT Mitra Jaya Logistik">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-4">Status Vendor <span class="text-red-500">*</span></label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="status" value="active" {{ $vendor->status === 'active' ? 'checked' : '' }} class="w-5 h-5 text-blue-600 cursor-pointer accent-teal-600">
                                    <span class="text-gray-700 font-medium">✓ Aktif</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="status" value="inactive" {{ $vendor->status === 'inactive' ? 'checked' : '' }} class="w-5 h-5 text-gray-400 cursor-pointer accent-gray-400">
                                    <span class="text-gray-700 font-medium">✗ Non-Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price List Card -->
                <div class="bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200">
                    <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 border-b-2 border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 mb-0">Daftar Harga Lokasi</h3>
                        <button type="button" onclick="addPriceRow()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-bold rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Lokasi
                        </button>
                    </div>

                    <div class="p-8">

                    <div id="priceContainer" class="space-y-6">
                            @forelse ($vendor->prices as $index => $price)
                                <div class="price-row bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/40 dark:to-gray-800/40 p-6 rounded-xl border-2 border-gray-300">
                                    <input type="hidden" name="prices[{{ $index }}][id]" value="{{ $price->id }}">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Lokasi/Tujuan <span class="text-red-500">*</span></label>
                                            <input type="text" name="prices[{{ $index }}][lokasi]" value="{{ old('prices.' . $index . '.lokasi', $price->lokasi) }}" required 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                                                placeholder="Contoh: UG, JATENG, JATIM">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Status</label>
                                            <select name="prices[{{ $index }}][status]" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold">
                                                <option value="active" {{ $price->status === 'active' ? 'selected' : '' }}>✓ Aktif</option>
                                                <option value="inactive" {{ $price->status === 'inactive' ? 'selected' : '' }}>✗ Non-Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 20 (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" name="prices[{{ $index }}][price_20]" value="{{ old('prices.' . $index . '.price_20', $price->price_20) }}" required step="1" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                                                placeholder="1300000">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 40 (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" name="prices[{{ $index }}][price_40]" value="{{ old('prices.' . $index . '.price_40', $price->price_40) }}" required step="1" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                                                placeholder="1700000">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 2X20 (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" name="prices[{{ $index }}][price_2x20]" value="{{ old('prices.' . $index . '.price_2x20', $price->price_2x20) }}" required step="1" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
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
                            @empty
                                <div class="price-row bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/40 dark:to-gray-800/40 p-6 rounded-xl border-2 border-gray-300">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Lokasi/Tujuan <span class="text-red-500">*</span></label>
                                            <input type="text" name="prices[0][lokasi]" required 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                                                placeholder="Contoh: UG, JATENG, JATIM">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Status</label>
                                            <select name="prices[0][status]" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold">
                                                <option value="active">✓ Aktif</option>
                                                <option value="inactive">✗ Non-Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 20 (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" name="prices[0][price_20]" required step="1" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                                                placeholder="1300000">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 40 (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" name="prices[0][price_40]" required step="1" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                                                placeholder="1700000">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 2X20 (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" name="prices[0][price_2x20]" required step="1" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-black placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
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
                            @endforelse
                        </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 mt-8 border-t-2 border-gray-200">
                        <a href="{{ route('vendor.register') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-center">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-slate-800 to-teal-700 hover:from-slate-900 hover:to-teal-800 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105">
                            ✓ Simpan Perubahan
                        </button>
                    </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let priceRowCount = {{ count($vendor->prices) > 0 ? count($vendor->prices) : 1 }};

        function addPriceRow() {
            const container = document.getElementById('priceContainer');
            const newRow = document.createElement('div');
            newRow.className = 'price-row bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/40 dark:to-gray-800/40 p-6 rounded-xl border-2 border-gray-300';
            newRow.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Lokasi/Tujuan <span class="text-red-500">*</span></label>
                        <input type="text" name="prices[${priceRowCount}][lokasi]" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                            placeholder="Contoh: UG, JATENG, JATIM">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Status</label>
                        <select name="prices[${priceRowCount}][status]" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold">
                            <option value="active">✓ Aktif</option>
                            <option value="inactive">✗ Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 20 (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="prices[${priceRowCount}][price_20]" required step="1" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                            placeholder="1300000">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 40 (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="prices[${priceRowCount}][price_40]" required step="1" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
                            placeholder="1700000">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Harga Size 2X20 (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="prices[${priceRowCount}][price_2x20]" required step="1" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 font-semibold" 
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
