<x-app-layout>
    <div class="py-6 max-w-6xl w-full mx-auto pb-36" x-data="orderProgressStepper('exportOrderForm')">
        <!-- Breadcrumb & Top Actions -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
                <a class="hover:text-teal-600 transition-colors" href="{{ route('dashboard') }}">Beranda</a>
                <span class="text-slate-300">/</span>
                <a class="hover:text-teal-600 transition-colors" href="{{ route('orders.index') }}">Order Ekspor</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Buat Order Ekspor Baru</span>
            </nav>
            <a class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-semibold shadow-xs hover:bg-slate-50 hover:border-slate-300 transition-all" href="{{ route('orders.select-type') }}">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span>Kembali ke Pilih Tipe</span>
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm">
                <h4 class="font-bold text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600 text-sm">error</span>
                    Terjadi Kesalahan Validasi:
                </h4>
                <ul class="list-disc list-inside space-y-1 text-xs font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Header Banner -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800 text-xs font-bold uppercase tracking-wider">Modul Ekspor Laut</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-500">
                        <span class="material-symbols-outlined text-xs text-emerald-600">check_circle</span> Sistem Terhubung ke {{ config('app.name') }}
                    </span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight font-display">Buat Order Ekspor</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                    Pendaftaran pesanan kargo ekspor laut, verifikasi nomor booking shipping & DO, alokasi armada kontainer, dan jadwal closing terminal.
                </p>
            </div>
            <div class="flex items-center gap-2 self-start md:self-auto">
                <div class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-right">
                    <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Status Draft</span>
                    <span class="text-xs font-bold text-teal-700" id="draftStatus">Tersimpan Otomatis</span>
                </div>
            </div>
        </div>

        <!-- Draft Banner (restore is opt-in, never auto-filled) -->
        <div id="draftBanner" class="hidden mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center gap-3">
            <span class="material-symbols-outlined text-amber-600">draft</span>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-extrabold text-amber-900">Ada draft order tersimpan di perangkat ini</p>
                <p id="draftBannerMeta" class="text-[11px] text-amber-700 font-medium">Pulihkan draft sebelumnya atau mulai dari awal.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btnResumeDraft" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-colors">Lanjutkan Draft</button>
                <button type="button" id="btnClearDraft" class="px-3.5 py-2 rounded-xl border border-amber-300 bg-white text-amber-800 hover:bg-amber-100 text-xs font-bold transition-colors">Mulai dari Awal</button>
            </div>
        </div>

        <!-- Validation Message -->
        <div id="formValidationMsg" class="hidden mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>
            <span class="text-xs font-bold">Ada field wajib yang belum diisi. Lengkapi dulu bagian yang ditandai merah sebelum menyimpan order.</span>
        </div>

        <!-- Horizontal Stepper (5 Tahap) with Animated Progress Line -->
        <div class="order-stepper-sticky bg-white rounded-2xl p-4 border border-slate-200 shadow-sm mb-8">
            <div class="relative" x-init="init()">
                <!-- Background Line Track & Animated Fill -->
                <div class="hidden sm:block order-progress-track">
                    <div class="order-progress-fill" :style="'width: ' + ((currentStep - 1) / 4 * 100) + '%;'"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 sm:gap-4 relative z-10">
                    <!-- Step 1 -->
                    <button type="button" @click="scrollToStep(1)"
                        :class="{
                            'is-current': currentStep === 1,
                            'is-done': currentStep > 1,
                            'bg-slate-50 border-slate-100': currentStep < 1
                        }"
                        class="order-progress-step flex items-center gap-3 p-2.5 rounded-xl border text-left cursor-pointer transition-all">
                        <div class="size-8 rounded-lg order-step-number bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs transition-colors">
                            <span x-show="currentStep <= 1">1</span>
                            <span x-show="currentStep > 1" class="material-symbols-outlined text-sm">check</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold order-step-title text-slate-800 truncate">Informasi Utama</p>
                            <p class="text-[11px] order-step-copy text-slate-400 font-medium truncate">Customer & No Order</p>
                        </div>
                    </button>

                    <!-- Step 2 -->
                    <button type="button" @click="scrollToStep(2)"
                        :class="{
                            'is-current': currentStep === 2,
                            'is-done': currentStep > 2,
                            'bg-slate-50 border-slate-100': currentStep < 2
                        }"
                        class="order-progress-step flex items-center gap-3 p-2.5 rounded-xl border text-left cursor-pointer transition-all">
                        <div class="size-8 rounded-lg order-step-number bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs transition-colors">
                            <span x-show="currentStep <= 2">2</span>
                            <span x-show="currentStep > 2" class="material-symbols-outlined text-sm">check</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold order-step-title text-slate-800 truncate">Data Pengiriman</p>
                            <p class="text-[11px] order-step-copy text-slate-400 font-medium truncate">Booking, DO & Vessel</p>
                        </div>
                    </button>

                    <!-- Step 3 -->
                    <button type="button" @click="scrollToStep(3)"
                        :class="{
                            'is-current': currentStep === 3,
                            'is-done': currentStep > 3,
                            'bg-slate-50 border-slate-100': currentStep < 3
                        }"
                        class="order-progress-step flex items-center gap-3 p-2.5 rounded-xl border text-left cursor-pointer transition-all">
                        <div class="size-8 rounded-lg order-step-number bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs transition-colors">
                            <span x-show="currentStep <= 3">3</span>
                            <span x-show="currentStep > 3" class="material-symbols-outlined text-sm">check</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold order-step-title text-slate-800 truncate">Container Details</p>
                            <p class="text-[11px] order-step-copy text-slate-400 font-medium truncate">Ukuran, Tipe & Vendor</p>
                        </div>
                    </button>

                    <!-- Step 4 -->
                    <button type="button" @click="scrollToStep(4)"
                        :class="{
                            'is-current': currentStep === 4,
                            'is-done': currentStep > 4,
                            'bg-slate-50 border-slate-100': currentStep < 4
                        }"
                        class="order-progress-step flex items-center gap-3 p-2.5 rounded-xl border text-left cursor-pointer transition-all">
                        <div class="size-8 rounded-lg order-step-number bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs transition-colors">
                            <span x-show="currentStep <= 4">4</span>
                            <span x-show="currentStep > 4" class="material-symbols-outlined text-sm">check</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold order-step-title text-slate-800 truncate">Tanggal Penting</p>
                            <p class="text-[11px] order-step-copy text-slate-400 font-medium truncate">Stuffing & Gate In Port</p>
                        </div>
                    </button>

                    <!-- Step 5 -->
                    <button type="button" @click="scrollToStep(5)"
                        :class="{
                            'is-current': currentStep === 5,
                            'is-done': currentStep > 5,
                            'bg-slate-50 border-slate-100': currentStep < 5
                        }"
                        class="order-progress-step flex items-center gap-3 p-2.5 rounded-xl border text-left cursor-pointer transition-all col-span-2 sm:col-span-1">
                        <div class="size-8 rounded-lg order-step-number bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs transition-colors">
                            <span>5</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold order-step-title text-slate-800 truncate">Status & Catatan</p>
                            <p class="text-[11px] order-step-copy text-slate-400 font-medium truncate">Instruksi Lapangan</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <form action="{{ route('export-orders.store') }}" method="POST" class="space-y-8" id="exportOrderForm">
            @csrf

            <!-- ==================== CARD 1: INFORMASI UTAMA ==================== -->
            <section id="section-step-1" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-700">
                            <span class="material-symbols-outlined text-xl">description</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">1. Informasi Utama</h3>
                            <p class="text-xs text-slate-500">Data identifikasi sistem dan entitas mitra pengirim kargo</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">Langkah 1 dari 5</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>Nomor Order Sistem</span>
                            <span class="inline-flex items-center gap-1 text-[10px] text-slate-400 font-semibold">
                                <span class="material-symbols-outlined text-xs">lock</span> Terkunci
                            </span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">tag</span>
                            </div>
                            <input class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500 cursor-not-allowed select-none" disabled type="text" value="AUTO-GENERATE (EXP/YYYY/XXXX)">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Dihasilkan otomatis berurutan saat order disimpan.</p>
                    </div>

                    <div>
                        <label for="order_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Order <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">calendar_month</span>
                            </div>
                            <input name="order_date" id="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}" required type="date"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Tanggal pembuatan berkas operasional.</p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700">
                                Nama Customer (Shipper) <span class="text-rose-500">*</span>
                            </label>
                            <a href="{{ route('customers.index') }}" target="_blank" class="text-[11px] font-bold text-teal-700 hover:text-teal-800 flex items-center gap-0.5 hover:underline">
                                <span class="material-symbols-outlined text-xs">add_circle</span> + Customer Baru
                            </a>
                        </div>
                        @php
                            $customerOptions = $customers->map(fn($c) => ['value' => $c->id, 'label' => "[{$c->customer_code}] {$c->customer_name}"])->values()->all();
                        @endphp
                        <div class="relative">
                            <x-searchable-select
                                name="customer_id"
                                :options="$customerOptions"
                                :selected="old('customer_id', '')"
                                placeholder="-- Pilih Customer --"
                                :required="true" />
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Terdaftar dengan kredit limit aktif sistem.</p>
                    </div>
                </div>
            </section>

            <!-- ==================== CARD 2: DATA PENGIRIMAN EKSPOR ==================== -->
            <section id="section-step-2" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-700">
                            <span class="material-symbols-outlined text-xl">sailing</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">2. Data Pengiriman Ekspor</h3>
                            <p class="text-xs text-slate-500">Legalitas shipping booking, manifest kapal, kepabeanan PEB & batas closing</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
                        <span class="material-symbols-outlined text-xs">timer</span> Closing Belawan BICT
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="shipping_number" class="block text-xs font-bold text-slate-700 mb-1.5">
                            No Shipping / Booking Confirmation <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">confirmation_number</span>
                            </div>
                            <input type="text" name="shipping_number" id="shipping_number" value="{{ old('shipping_number') }}" required
                                placeholder="Contoh: MSK-BLW-994208"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Diterbitkan oleh pihak agen shipping line.</p>
                    </div>

                    <div>
                        <label for="do_number" class="block text-xs font-bold text-slate-700 mb-1.5">
                            No Delivery Order (DO) / Surat Jalan Depo <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">local_shipping</span>
                            </div>
                            <input type="text" name="do_number" id="do_number" value="{{ old('do_number') }}" required
                                placeholder="Contoh: DO/EXP/2024/0911"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Surat jalan pengambilan container kosong.</p>
                    </div>

                    <div>
                        <label for="product_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Barang / Deskripsi Kargo <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">inventory_2</span>
                            </div>
                            <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required
                                placeholder="Deskripsi isi kargo muatan"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Cocokkan dengan manifest export permit.</p>
                    </div>

                    <div>
                        <label for="shipping_line" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Pelayaran (Shipping Line) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">directions_boat</span>
                            </div>
                            <input type="text" name="shipping_line" id="shipping_line" value="{{ old('shipping_line') }}" required
                                placeholder="Contoh: Maersk Line"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Operator armada pelayaran induk.</p>
                    </div>

                    <div>
                        <label for="vessel_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Kapal (Vessel Name) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">anchor</span>
                            </div>
                            <input type="text" name="vessel_name" id="vessel_name" value="{{ old('vessel_name') }}" required
                                placeholder="Contoh: CMA CGM Palais"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Kapal feeder/mother vessel di BICT Belawan.</p>
                    </div>

                    <div>
                        <label for="voy_number" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Voy Kapal (Voyage Number) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">navigation</span>
                            </div>
                            <input type="text" name="voy_number" id="voy_number" value="{{ old('voy_number') }}" required
                                placeholder="Contoh: VOY. 08E-SIN"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Nomor pelayaran resmi jadwal ekspor.</p>
                    </div>

                    <div>
                        <label for="closing_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tgl Closing Terminal <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">event_busy</span>
                            </div>
                            <input type="date" name="closing_date" id="closing_date" value="{{ old('closing_date') }}" required
                                class="w-full pl-9 pr-3 py-2 bg-white border border-amber-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all">
                        </div>
                        <p class="text-[11px] text-amber-700 font-medium mt-1">Batas akhir pintu terminal BICT menerima gate in.</p>
                    </div>

                    <div>
                        <label for="peb_number" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nomor Pengajuan PEB (CEISA 4.0)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">assured_workload</span>
                            </div>
                            <input type="text" name="peb_number" id="peb_number" value="{{ old('peb_number') }}"
                                placeholder="000000-000000-20241024-001290"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Wajib verifikasi NPE Bea Cukai sebelum gate in.</p>
                    </div>

                    <div>
                        <label for="depo" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Depo Pengambilan Empty Container
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">warehouse</span>
                            </div>
                            <input type="text" name="depo" id="depo" value="{{ old('depo') }}"
                                placeholder="Contoh: Depo Samudera Inti Medan"
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Lokasi supir menjemput box kosong (empty pick-up).</p>
                    </div>

                    <div class="md:col-span-3 bg-slate-50 border border-slate-200 rounded-xl p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-teal-700 text-xl">event_upcoming</span>
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Detention & Demurrage Free Time</span>
                                <span class="text-[11px] text-slate-500">Batas toleransi penahanan kontainer sebelum dikenakan denda detention per hari dari pelayaran.</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <input type="date" name="detention_deadline" id="detention_deadline" value="{{ old('detention_deadline') }}"
                                class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-teal-800 focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== CARD 3: CONTAINER DETAILS ==================== -->
            <div id="section-step-3">
                <x-container-input :order="null" :existingGroups="[]" :vendors="$vendors" :orderId="null" :availableCombineContainers="$availableCombineContainers" orderType="export" />
            </div>

            <!-- ==================== CARD 4: TANGGAL PENTING ==================== -->
            <section id="section-step-4" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-700">
                            <span class="material-symbols-outlined text-xl">event_available</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">4. Tanggal Penting Ekspor</h3>
                            <p class="text-xs text-slate-500">Jadwal krusial pergerakan kontainer dari depo, lokasi muat pabrik, hingga dermaga</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                        On Schedule
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/90">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="size-8 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-lg">local_shipping</span>
                            </div>
                            <div>
                                <label for="pickup_date" class="block text-xs font-bold text-slate-900">
                                    Tgl Pengantaran Box Kosong (Empty Drop) <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">
                                    Jadwal stuffing & pengantaran box kosong dari depo ke gudang/pabrik shipper.
                                </p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">schedule</span>
                            </div>
                            <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date') }}" required
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/90">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="size-8 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-lg">anchor</span>
                            </div>
                            <div>
                                <label for="return_date" class="block text-xs font-bold text-slate-900">
                                    Tgl Penarikan Keluar (Gate In Port) <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">
                                    Jadwal penarikan container full menuju CY Pelabuhan Belawan BICT.
                                </p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">schedule</span>
                            </div>
                            <input type="date" name="return_date" id="return_date" value="{{ old('return_date') }}" required
                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== CARD 5: STATUS & CATATAN ==================== -->
            <section id="section-step-5" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-700">
                            <span class="material-symbols-outlined text-xl">notes</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">5. Status & Catatan Operasional</h3>
                            <p class="text-xs text-slate-500">Inisialisasi status alur kargo serta catatan instruksi khusus untuk tim lapangan & supir</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">Tahap Akhir</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Status Order Operasional <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">flag</span>
                            </div>
                            <select name="status" id="status" required
                                class="w-full pl-9 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all appearance-none">
                                <option value="" disabled {{ old('status') ? '' : 'selected' }}>-- Pilih Status Order --</option>
                                <option value="on going" {{ old('status', 'on going') === 'on going' ? 'selected' : '' }}>On Going (Sedang Berjalan)</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai Muat)</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-base">expand_more</span>
                            </div>
                        </div>
                        <div class="mt-3 p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            <span class="text-[11px] font-bold text-emerald-800">Status "On Going" akan langsung mengirim SPK ke supir.</span>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="issue" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Masalah / Instruksi Khusus Lapangan
                        </label>
                        <div class="relative">
                            <textarea name="issue" id="issue" rows="4"
                                class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all leading-relaxed"
                                placeholder="Masukkan catatan penanganan khusus kargo, ketentuan seal shipping, atau instruksi jembatan timbang...">{{ old('issue') }}</textarea>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan otomatis tercetak pada Surat Perintah Kerja (SPK) supir pengangkut.</p>
                    </div>
                </div>
            </section>

            <!-- ==================== STICKY FOOTER ACTION BAR ==================== -->
            <footer class="fixed bottom-0 left-0 right-0 z-30 bg-white/95 backdrop-blur border-t border-slate-200 py-3.5 px-6 sm:px-8 shadow-lg">
                <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 self-start sm:self-auto">
                        <div class="size-9 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-700">
                            <span class="material-symbols-outlined text-xl">fact_check</span>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold text-slate-900 tracking-tight" id="footerCargoSummary">
                                Total Kargo: Terjadwal Dinamis • Modul Ekspor
                            </p>
                            <p class="text-[11px] text-slate-500 font-medium">
                                PT Suryasumatera Logistics • Belawan BICT
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('orders.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                            Batal
                        </a>
                        <button type="button" id="btnSaveDraft" class="px-4 py-2 rounded-xl border border-teal-200 bg-teal-50 text-teal-700 text-xs font-bold hover:bg-teal-100 transition-colors">
                            Simpan Draft
                        </button>
                        <button type="submit" id="btnSubmitOrder"
                            class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 active:scale-95 text-white text-xs font-bold tracking-wide shadow-md shadow-teal-600/20 hover:shadow-lg hover:shadow-teal-600/30 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">send</span>
                            <span id="btnSubmitText">Simpan Order Ekspor</span>
                        </button>
                    </div>
                </div>
            </footer>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        window.initOrderDraft({
            formId: 'exportOrderForm',
            storageKey: 'order_form_draft_export_create',
            containerStorageKey: 'container_data_export_create'
        });
    });
    </script>
</x-app-layout>