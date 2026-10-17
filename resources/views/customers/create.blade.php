<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-950 leading-tight">
            Daftarkan Customer Baru
        </h2>
    </x-slot>

    <style>
        @keyframes customer-enter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes customer-pulse {
            50% { box-shadow: 0 0 0 5px rgba(13, 148, 136, .12); }
        }
        .customer-enter { animation: customer-enter .55s cubic-bezier(.2,.7,.2,1) both; }
        .customer-enter-delay { animation-delay: 100ms; }
        .customer-code-pulse { animation: customer-pulse 1.4s ease-out 1; }
        @media (prefers-reduced-motion: reduce) {
            .customer-enter, .customer-enter-delay, .customer-code-pulse { animation: none !important; }
            .customer-motion { transition: none !important; }
        }
    </style>

    <div class="min-h-screen bg-[#F8FAFC] px-4 py-6 sm:px-6 lg:px-8"
        x-data="{
            customerName: @js(old('customer_name', '')),
            city: @js(old('city', 'MEDAN')),
            saving: false,
            copied: false,
            copyCode() {
                navigator.clipboard.writeText(@js($generatedCode)).then(() => {
                    this.copied = true;
                    window.setTimeout(() => this.copied = false, 1800);
                });
            }
        }" @customer-city-changed="city = $event.detail">
        <div class="mx-auto max-w-[1100px]">
            <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center customer-enter">
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <a href="{{ route('customers.index') }}" class="transition-colors hover:text-teal-700">Customer</a>
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/></svg>
                    <span class="text-slate-900">Daftarkan Customer Baru</span>
                </nav>
                <a href="{{ route('customers.index') }}" class="group inline-flex min-h-11 w-fit items-center gap-1.5 text-sm font-semibold text-slate-600 transition-colors hover:text-[#0C1D3F]">
                    <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/></svg>
                    Kembali ke Customer
                </a>
            </div>

            @if ($errors->any())
                <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm customer-enter">
                    <div class="mb-2 flex items-center gap-2 font-bold">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.86a2 2 0 0 0-3.4 0Z"/></svg>
                        Periksa kembali data customer
                    </div>
                    <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12 lg:gap-8">
                <section class="customer-enter lg:col-span-8" aria-labelledby="customer-form-title">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-[0_4px_20px_-2px_rgba(12,29,63,0.04)] sm:p-8">
                        <div class="flex items-start gap-4 border-b border-slate-100 pb-6">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-teal-700 ring-1 ring-teal-100">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M19 8h4m-2-2v4"/></svg>
                            </div>
                            <div>
                                <h1 id="customer-form-title" class="text-xl font-bold tracking-tight text-[#0B1C30]">Informasi Customer</h1>
                                <p class="mt-1 text-sm leading-6 text-slate-600">Lengkapi data customer untuk mulai membuat order pengiriman logistik.</p>
                            </div>
                        </div>

                        <div class="my-6 flex items-center justify-between gap-2 rounded-xl border border-slate-200/70 bg-[#EFF4FF] px-3 py-4 sm:px-5">
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-700 text-xs font-bold text-white">1</span>
                                <span class="truncate text-xs font-bold text-teal-800 sm:text-sm">Informasi Dasar</span>
                            </div>
                            <span class="h-px min-w-3 flex-1 bg-teal-700/25"></span>
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#D3E4FE] text-xs font-bold text-slate-700">2</span>
                                <span class="hidden truncate text-xs font-semibold text-slate-700 sm:inline sm:text-sm">Kota Operasional</span>
                            </div>
                            <span class="h-px min-w-3 flex-1 bg-slate-300"></span>
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#DCE9FF] text-xs font-bold text-slate-600">3</span>
                                <span class="hidden truncate text-xs font-semibold text-slate-500 sm:inline sm:text-sm">Konfirmasi</span>
                            </div>
                        </div>

                        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6" @submit="saving = true">
                            @csrf
                            <div>
                                <label for="customer-code" class="mb-2 flex items-center justify-between gap-2 text-sm font-semibold text-[#0B1C30]">
                                    <span>Kode Customer</span>
                                    <span class="rounded-full bg-teal-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-teal-800">Dibuat otomatis</span>
                                </label>
                                <div class="relative flex items-center">
                                    <input id="customer-code" type="text" value="{{ $generatedCode }}" readonly aria-describedby="customer-code-help" class="customer-code-pulse h-11 w-full cursor-text select-all rounded-lg border border-slate-200 bg-[#EFF4FF] px-3.5 pr-28 font-mono text-sm font-semibold tracking-wider text-[#0B1C30] focus:outline-none">
                                    <button type="button" @click="copyCode" class="absolute right-1.5 inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-600 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-600" :aria-label="copied ? 'Kode tersalin' : 'Salin kode customer'">
                                        <svg x-show="!copied" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/></svg>
                                        <svg x-cloak x-show="copied" class="h-4 w-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg>
                                        <span x-text="copied ? 'Tersalin' : 'Salin'"></span>
                                    </button>
                                </div>
                                <p id="customer-code-help" class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path stroke-linecap="round" stroke-width="1.8" d="M12 11v5m0-8h.01"/></svg>
                                    Kode dibuat otomatis oleh sistem saat customer disimpan.
                                </p>
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <label for="customer_name" class="text-sm font-semibold text-[#0B1C30]">Nama Customer <span class="text-red-600">*</span></label>
                                    <span class="text-xs tabular-nums text-slate-500"><span x-text="customerName.length">0</span>/255</span>
                                </div>
                                <input type="text" name="customer_name" id="customer_name" x-model="customerName" value="{{ old('customer_name') }}" maxlength="255" required autocomplete="organization" placeholder="Contoh: PT Maju Bersama" aria-describedby="customer-name-error" class="customer-motion h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 text-sm font-medium text-[#0B1C30] placeholder:text-slate-400 transition duration-200 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/15 @error('customer_name') border-red-500 @enderror">
                                @error('customer_name')<p id="customer-name-error" class="mt-2 flex items-center gap-1.5 text-xs font-medium text-red-700"><span aria-hidden="true">!</span>{{ $message }}</p>@enderror
                            </div>

                            

                            <div>
                                <label for="city" class="mb-2 block text-sm font-semibold text-[#0B1C30]">Kota Operasional</label>
                                <x-searchable-city-select :options="$cityOptions" :selected="old('city', 'MEDAN')" id="city" />
                                @error('city')<p class="mt-1 text-xs font-medium text-red-700">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                                <a href="{{ route('customers.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-300 px-5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400">Batal</a>
                                <button type="submit" :disabled="saving" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-teal-700 px-6 text-sm font-bold text-white shadow-sm transition duration-200 hover:bg-teal-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2 disabled:cursor-wait disabled:opacity-70">
                                    <svg x-show="!saving" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3h12l4 4v14H3V3h2Zm2 0v6h10V3M7 21v-8h10v8"/></svg>
                                    <svg x-cloak x-show="saving" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"/></svg>
                                    <span x-text="saving ? 'Menyimpan...' : 'Simpan Customer'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <aside class="customer-enter customer-enter-delay lg:sticky lg:top-24 lg:col-span-4" aria-labelledby="customer-preview-title">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-[0_4px_20px_-2px_rgba(12,29,63,0.04)] sm:p-6">
                        <div class="mb-5 flex items-center justify-between gap-2">
                            <h2 id="customer-preview-title" class="font-bold text-[#0B1C30]">Preview Customer</h2>
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-teal-700/15 bg-teal-50 px-2.5 py-1 text-[11px] font-bold text-teal-800">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-teal-600"></span>Siap disimpan
                            </span>
                        </div>

                        <div class="relative flex min-h-[220px] flex-col justify-between overflow-hidden rounded-xl bg-gradient-to-br from-[#0C1D3F] via-[#0C1D3F] to-[#000416] p-5 text-white shadow-md">
                            <svg class="pointer-events-none absolute -bottom-7 -right-7 h-44 w-44 text-white opacity-[0.09]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 120 120" aria-hidden="true"><path d="M10 80q20-10 50 0t50 0M10 90q25-10 55 0t45 0M25 68l10-38h50l10 38zM45 30V15m30 15V15"/><rect x="40" y="10" width="10" height="6"/><rect x="70" y="10" width="10" height="6"/></svg>
                            <div class="relative z-10 flex items-center justify-between gap-2">
                                <span class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-[.16em] text-[#B7C6F1]">
                                    <svg class="h-4 w-4 text-[#89F5E7]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 12 2 2 4-4m5.6-1A8.5 8.5 0 1 1 12 3.5"/></svg>
                                    Suryasumatera Logistics
                                </span>
                                <span class="rounded bg-white/10 px-2 py-1 font-mono text-[10px] font-semibold tracking-wider text-[#89F5E7]">{{ $generatedCode }}</span>
                            </div>
                            <div class="relative z-10 my-5 min-w-0">
                                <span class="mb-1 block text-[10px] font-bold uppercase tracking-widest text-[#B7C6F1]">Nama Customer</span>
                                <p class="truncate text-lg font-bold tracking-tight" :class="customerName.trim() ? 'text-white' : 'italic text-[#B7C6F1]/80'" x-text="customerName.trim() || 'Nama customer belum diisi'"></p>
                            </div>
                            <div class="relative z-10 flex items-center justify-between gap-2 border-t border-white/15 pt-3">
                                <span class="inline-flex items-center rounded-full border border-teal-300/30 bg-teal-400/15 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-teal-100">Customer</span>
                                <span class="flex min-w-0 items-center gap-1 text-xs font-semibold text-[#D9E2FF]">
                                    <svg class="h-4 w-4 shrink-0 text-[#89F5E7]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5" stroke-width="1.8"/></svg>
                                    <span class="truncate uppercase" x-text="city.trim() || 'Kota belum diisi'"></span>
                                </span>
                            </div>
                        </div>

                        <div class="mt-5 rounded-xl border border-slate-100 bg-[#EFF4FF] p-3.5">
                            <div class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 5 6v5c0 4.5 3 7.5 7 10 4-2.5 7-5.5 7-10V6l-7-3Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 12 2 2 4-4"/></svg>
                                <p class="text-xs leading-5 text-slate-600">Satu customer dipakai untuk order impor dan ekspor. Pastikan nama dan kota operasional sudah benar sebelum disimpan.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
