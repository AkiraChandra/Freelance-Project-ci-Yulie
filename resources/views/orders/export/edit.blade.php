<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Edit Order Ekspor — {{ $exportOrder->export_order_number }}</h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        @php
            $tv_parts = array_map('trim', explode(',', $exportOrder->trucking_vendor ?? ''));
            $tv1_saved = old('trucking_vendor_1', $tv_parts[0] ?? '');
            $tv2_saved = old('trucking_vendor_2', $tv_parts[1] ?? '');
        @endphp
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" x-data="{ party: '{{ old('party', $exportOrder->party) }}' }">

            <a href="{{ route('orders.index') }}"
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

            <form action="{{ route('export-orders.update', $exportOrder) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- INFO UTAMA --}}
                <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-900">Informasi Utama</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">No Order</label>
                            <input type="text" disabled value="{{ $exportOrder->export_order_number }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl bg-gray-100 text-gray-500 font-mono font-bold text-sm cursor-not-allowed">
                        </div>

                        <div>
                            <label for="order_date" class="block text-sm font-bold text-gray-700 mb-1">Tgl Order <span class="text-red-500">*</span></label>
                            <input type="date" name="order_date" id="order_date"
                                value="{{ old('order_date', $exportOrder->order_date->format('Y-m-d')) }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        @php
                            $customerOptions = $customers->map(fn($c) => ['value' => $c->id, 'label' => "[{$c->customer_code}] {$c->customer_name}"])->values()->all();
                        @endphp
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
                            <x-searchable-select
                                name="customer_id"
                                :options="$customerOptions"
                                :selected="old('customer_id', $exportOrder->customer_id)"
                                placeholder="-- Pilih Customer --"
                                :required="true" />
                        </div>
                    </div>
                </div>

                {{-- DATA PENGIRIMAN --}}
                <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-900">Data Pengiriman</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <label for="shipping_number" class="block text-sm font-bold text-gray-700 mb-1">No Shipping</label>
                            <input type="text" name="shipping_number" id="shipping_number" value="{{ old('shipping_number', $exportOrder->shipping_number) }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="do_number" class="block text-sm font-bold text-gray-700 mb-1">No DO <span class="text-red-500">*</span></label>
                            <input type="text" name="do_number" id="do_number" value="{{ old('do_number', $exportOrder->do_number) }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="product_name" class="block text-sm font-bold text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $exportOrder->product_name) }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="shipping_line" class="block text-sm font-bold text-gray-700 mb-1">Pelayaran <span class="text-red-500">*</span></label>
                            <input type="text" name="shipping_line" id="shipping_line" value="{{ old('shipping_line', $exportOrder->shipping_line) }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="vessel_name" class="block text-sm font-bold text-gray-700 mb-1">Nama Kapal <span class="text-red-500">*</span></label>
                            <input type="text" name="vessel_name" id="vessel_name" value="{{ old('vessel_name', $exportOrder->vessel_name) }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="voy_number" class="block text-sm font-bold text-gray-700 mb-1">Voy Kapal <span class="text-red-500">*</span></label>
                            <input type="text" name="voy_number" id="voy_number" value="{{ old('voy_number', $exportOrder->voy_number) }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="closing_date" class="block text-sm font-bold text-gray-700 mb-1">Tgl Clossing <span class="text-red-500">*</span></label>
                            <input type="date" name="closing_date" id="closing_date" value="{{ old('closing_date', $exportOrder->closing_date?->format('Y-m-d')) }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="peb_number" class="block text-sm font-bold text-gray-700 mb-1">PEB Aju</label>
                            <input type="text" name="peb_number" id="peb_number" value="{{ old('peb_number', $exportOrder->peb_number) }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="party" class="block text-sm font-bold text-gray-700 mb-1">Party <span class="text-red-500">*</span></label>
                            <select name="party" id="party" x-model="party" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                                <option value="">-- Pilih Party --</option>
                                <option value="20'" {{ old('party', $exportOrder->party) === "20'" ? 'selected' : '' }}>20'</option>
                                <option value="40'" {{ old('party', $exportOrder->party) === "40'" ? 'selected' : '' }}>40'</option>
                                <option value="LCL" {{ old('party', $exportOrder->party) === 'LCL' ? 'selected' : '' }}>LCL</option>
                                <option value="Pallet" {{ old('party', $exportOrder->party) === 'Pallet' ? 'selected' : '' }}>Pallet</option>
                            </select>
                        </div>

                        <div>
                            <label for="depo" class="block text-sm font-bold text-gray-700 mb-1">Depo</label>
                            <input type="text" name="depo" id="depo" value="{{ old('depo', $exportOrder->depo) }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>
                    </div>
                </div>

                {{-- CONTAINER --}}
                <div x-show="party !== 'LCL'" class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-900">Data Container & Tanggal</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div class="sm:col-span-2">
                            <label for="container_number" class="block text-sm font-bold text-gray-700 mb-1">No Container</label>
                            <input type="text" name="container_number" id="container_number" value="{{ old('container_number', $exportOrder->container_number) }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="pickup_date" class="block text-sm font-bold text-gray-700 mb-1">Tgl Pengantaran</label>
                            <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date', $exportOrder->pickup_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        <div>
                            <label for="return_date" class="block text-sm font-bold text-gray-700 mb-1">Tgl Penarikan Keluar</label>
                            <input type="date" name="return_date" id="return_date" value="{{ old('return_date', $exportOrder->return_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                        </div>

                        @php
                            $vendorOptions = $vendors->map(fn($v) => ['value' => $v->name, 'label' => $v->name])->values()->all();
                        @endphp
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Trucking (Vendor)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Vendor 1</label>
                                    <x-searchable-select
                                        name="trucking_vendor_1"
                                        :options="$vendorOptions"
                                        :selected="old('trucking_vendor_1', $tv1_saved)"
                                        placeholder="-- Pilih Vendor --" />
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Vendor 2 (opsional)</label>
                                    <x-searchable-select
                                        name="trucking_vendor_2"
                                        :options="$vendorOptions"
                                        :selected="old('trucking_vendor_2', $tv2_saved)"
                                        placeholder="-- Tidak ada --" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STATUS & MASALAH --}}
                <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-900">Status & Catatan</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="status" class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">
                                <option value="on going" {{ old('status', $exportOrder->status) === 'on going' ? 'selected' : '' }}>On Going</option>
                                <option value="completed" {{ old('status', $exportOrder->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $exportOrder->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="issue" class="block text-sm font-bold text-gray-700 mb-1">Masalah (opsional)</label>
                            <textarea name="issue" id="issue" rows="3"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-sm">{{ old('issue', $exportOrder->issue) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('orders.index') }}"
                        class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl shadow transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('form').addEventListener('submit', function (e) {
            const v1 = document.querySelector('[name="trucking_vendor_1"]').value;
            const v2 = document.querySelector('[name="trucking_vendor_2"]').value;
            if (v1 && v2 && v1 === v2) {
                e.preventDefault();
                alert('Vendor 1 dan Vendor 2 tidak boleh sama!');
            }
        });
    });
    </script>
</x-app-layout>
