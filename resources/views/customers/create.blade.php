<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            Daftarkan Customer Baru
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button -->
            <a href="{{ route('customers.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow hover:shadow-md transition text-gray-700 font-semibold mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow">
                    <h4 class="font-bold mb-2">Terjadi Kesalahan:</h4>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-8 py-5 bg-gradient-to-r from-teal-50 to-emerald-50 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-gray-900">Informasi Customer</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Isi data customer baru di bawah ini</p>
                </div>

                <form action="{{ route('customers.store') }}" method="POST" class="p-8 space-y-6"
                    x-data="{ type: '{{ old('type') }}' }">
                    @csrf

                    <!-- Customer Code (read-only, auto-generated) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kode Customer (Auto-Generate)</label>
                        <div class="flex items-center gap-3">
                            <input type="text" value="{{ $generatedCode }}" disabled
                                class="w-36 px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 text-gray-500 font-mono font-bold text-center tracking-widest cursor-not-allowed">
                            <span class="text-xs text-gray-400">5-digit unik, di-generate otomatis saat simpan</span>
                        </div>
                    </div>

                    <!-- Customer Name -->
                    <div>
                        <label for="customer_name" class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Customer (Perusahaan) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="customer_name" id="customer_name"
                            value="{{ old('customer_name') }}" required
                            placeholder="Contoh: PT Maju Bersama"
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all font-medium">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            Tipe Customer <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Ekspor -->
                            <label class="cursor-pointer" @click="type = 'ekspor'">
                                <input type="radio" name="type" value="ekspor" x-model="type" class="sr-only" required>
                                <div class="flex items-center gap-3 p-4 border-2 rounded-xl transition-all"
                                    :class="type === 'ekspor' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300 bg-white'">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm flex-shrink-0"
                                        :class="type === 'ekspor' ? 'bg-blue-200 text-blue-700' : 'bg-blue-100 text-blue-600'">E</div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Ekspor</p>
                                        <p class="text-xs text-gray-500">Pengiriman ke luar negeri</p>
                                    </div>
                                    <div class="ml-auto flex-shrink-0">
                                        <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                            :class="type === 'ekspor' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="type === 'ekspor'"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <!-- Impor -->
                            <label class="cursor-pointer" @click="type = 'impor'">
                                <input type="radio" name="type" value="impor" x-model="type" class="sr-only">
                                <div class="flex items-center gap-3 p-4 border-2 rounded-xl transition-all"
                                    :class="type === 'impor' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-300 bg-white'">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm flex-shrink-0"
                                        :class="type === 'impor' ? 'bg-orange-200 text-orange-700' : 'bg-orange-100 text-orange-600'">I</div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Impor</p>
                                        <p class="text-xs text-gray-500">Pengiriman dari luar negeri</p>
                                    </div>
                                    <div class="ml-auto flex-shrink-0">
                                        <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                            :class="type === 'impor' ? 'border-orange-500 bg-orange-500' : 'border-gray-300'">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="type === 'impor'"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('customers.index') }}"
                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl shadow transition-colors">
                            Simpan Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
