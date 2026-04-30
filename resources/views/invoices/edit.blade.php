@php $isRevision = $isRevision ?? false; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ $isRevision ? 'Buat Invoice Revisi (Susulan) — dari ' . $invoice->nota_number : 'Edit Invoice — ' . $invoice->nota_number }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <ul class="list-disc pl-4 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info banner --}}
            @if ($isRevision)
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 rounded-lg">
                    <p class="font-semibold">Mode Revisi (Susulan)</p>
                    <p class="text-sm">Invoice baru akan dibuat dengan nota number baru (suffix A, B, C...). Anda bisa menambah, menghapus, atau mengubah section dan baris.</p>
                </div>
            @else
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 text-amber-800 p-4 rounded-lg">
                    <p class="font-semibold">Mode Edit</p>
                    <p class="text-sm">Anda hanya bisa mengubah nilai baris yang sudah ada (label & amount). Untuk menambah atau menghapus baris, gunakan tombol <strong>"Buat Invoice Revisi"</strong> di halaman detail.</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8"
                x-data="invoiceEditForm()" x-cloak>

                <form @submit.prevent="submitForm" method="POST" action="{{ $isRevision ? route('invoices.revision.store', $invoice) : route('invoices.update', $invoice) }}">
                    @csrf
                    @if (!$isRevision) @method('PATCH') @endif

                    {{-- Order Info (read-only) --}}
                    <div class="mb-8 border-b pb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Order</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tipe</label>
                                <p class="px-3 py-2.5 bg-gray-100 border-2 border-gray-200 rounded-lg text-sm font-semibold">{{ $invoice->order_type === 'import' ? '📥 Import' : '📤 Export' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nota No</label>
                                <p class="px-3 py-2.5 bg-gray-100 border-2 border-gray-200 rounded-lg text-sm font-mono font-semibold">{{ $invoice->nota_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Customer</label>
                                <p class="px-3 py-2.5 bg-gray-100 border-2 border-gray-200 rounded-lg text-sm font-semibold">{{ $invoice->order->customer->customer_name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Judul Invoice --}}
                    <div class="mb-8 border-b pb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📄 Judul Invoice</h3>
                        <input type="text" name="invoice_title" x-model="invoiceTitle"
                            placeholder="cth: PERINCIAN IMPORT / INVOICE"
                            class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm font-semibold focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                    </div>

                    {{-- Editable Header Fields --}}
                    <div class="mb-8 border-b pb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🏢 Header Invoice</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ex/ per kapal</label>
                                <input type="text" name="vessel_name" x-model="headerFields.vessel_name"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tgl</label>
                                <input type="text" name="vessel_date" x-model="headerFields.vessel_date"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                                <input type="text" name="destination" x-model="headerFields.destination"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Party</label>
                                <input type="text" name="party_display" x-model="headerFields.party_display"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</label>
                                <input type="text" name="product_name" x-model="headerFields.product_name"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tonage</label>
                                <input type="text" name="tonage" x-model="headerFields.tonage"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Merk / PEB / PIB</label>
                                <input type="text" name="merk" x-model="headerFields.merk"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cont</label>
                                <input type="text" name="container_display" x-model="headerFields.container_display"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                            </div>
                        </div>
                    </div>

                    {{-- Sections --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Perincian / Sections</h3>
                            @if ($isRevision)
                                <button type="button" @click="addSection()"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    + Tambah Section
                                </button>
                            @endif
                        </div>

                        <template x-for="(section, sIdx) in sections" :key="sIdx">
                            <div class="mb-6 border-2 border-gray-200 rounded-xl p-5 bg-gray-50">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-sm font-bold text-gray-500" x-text="romanNumeral(sIdx + 1) + ')'"></span>
                                    <input type="text" x-model="section.name"
                                        :name="'sections[' + sIdx + '][name]'"
                                        class="flex-1 border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                                    @if ($isRevision)
                                        <button type="button" @click="removeSection(sIdx)" x-show="sections.length > 1"
                                            class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold rounded-lg transition-colors">
                                            Hapus Section
                                        </button>
                                    @endif
                                </div>

                                <div class="space-y-2">
                                    <template x-for="(item, iIdx) in section.items" :key="iIdx">
                                        <div class="flex items-center gap-3">
                                            <input type="text" x-model="item.label"
                                                :name="'sections[' + sIdx + '][items][' + iIdx + '][label]'"
                                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-purple-400 focus:ring-1 focus:ring-purple-200">
                                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-56">
                                                <span class="bg-gray-100 px-3 py-2 text-sm text-gray-600 font-medium border-r border-gray-300">Rp</span>
                                                <input type="number" x-model.number="item.amount"
                                                    :name="'sections[' + sIdx + '][items][' + iIdx + '][amount]'"
                                                    class="flex-1 px-3 py-2 text-sm text-right focus:outline-none focus:ring-0 border-0">
                                            </div>
                                            @if ($isRevision)
                                                <button type="button" @click="removeItem(sIdx, iIdx)" x-show="section.items.length > 1"
                                                    class="px-2 py-2 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg text-sm">
                                                    ✕
                                                </button>
                                            @endif
                                        </div>
                                    </template>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    @if ($isRevision)
                                        <button type="button" @click="addItem(sIdx)"
                                            class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
                                            + Tambah Baris
                                        </button>
                                    @else
                                        <div></div>
                                    @endif
                                    <div class="text-sm font-semibold text-gray-700">
                                        Subtotal: <span class="text-gray-900" x-text="formatRp(sectionTotal(sIdx))"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Tax & Panjar --}}
                    <div class="mb-8 border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">💰 Pajak & Panjar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl p-4 cursor-pointer select-none">
                                <input type="checkbox" x-model="includeTax" name="include_tax" value="1"
                                    class="w-5 h-5 rounded accent-purple-600">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Termasuk Pajak</p>
                                    <p class="text-xs text-gray-500">PPN akan ditambahkan</p>
                                </div>
                            </label>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">% Pajak</label>
                                <input type="number" step="0.1" x-model.number="taxPercentage" name="tax_percentage"
                                    :disabled="!includeTax"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-purple-500 disabled:bg-gray-100 disabled:text-gray-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Panjar (Uang Muka)</label>
                                <div class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden">
                                    <span class="bg-gray-100 px-3 py-2.5 text-sm text-gray-600 font-medium border-r-2 border-gray-300">Rp</span>
                                    <input type="number" x-model.number="panjar" name="panjar"
                                        placeholder="0"
                                        class="flex-1 px-3 py-2.5 text-sm focus:outline-none focus:ring-0 border-0">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="mb-8 bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">📊 Ringkasan</h3>
                        <div class="space-y-2 text-sm">
                            <template x-for="(section, sIdx) in sections" :key="'sum-'+sIdx">
                                <div class="flex justify-between">
                                    <span class="text-gray-600" x-text="section.name || '(Section ' + (sIdx+1) + ')'"></span>
                                    <span class="font-semibold" x-text="formatRp(sectionTotal(sIdx))"></span>
                                </div>
                            </template>
                            <div class="border-t border-purple-200 my-2 pt-2 flex justify-between font-bold text-base">
                                <span>Jumlah Tagihan Keseluruhan</span>
                                <span class="text-purple-700" x-text="formatRp(totalBilling())"></span>
                            </div>
                            <template x-if="panjar > 0">
                                <div class="flex justify-between font-bold text-lg border-t border-purple-300 pt-2">
                                    <span>Total Tagihan</span>
                                    <span class="text-green-700" x-text="formatRp(totalAfterPanjar())"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-between items-center">
                        <a href="{{ route('invoices.show', $invoice) }}"
                            class="text-gray-600 hover:text-gray-800 font-medium text-sm">
                            ← Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all">
                            {{ $isRevision ? 'Buat Invoice Revisi' : 'Update Invoice' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function invoiceEditForm() {
            return {
                invoiceTitle: @js($invoice->invoice_title ?? ''),
                headerFields: {
                    vessel_name: @js($invoice->vessel_name ?? ''),
                    vessel_date: @js($invoice->vessel_date ?? ''),
                    destination: @js($invoice->destination ?? ''),
                    party_display: @js($invoice->party_display ?? ''),
                    product_name: @js($invoice->product_name ?? ''),
                    tonage: @js($invoice->tonage ?? ''),
                    merk: @js($invoice->merk ?? ''),
                    container_display: @js($invoice->container_display ?? ''),
                },
                sections: @json($invoice->sections),
                includeTax: {{ $invoice->include_tax ? 'true' : 'false' }},
                taxPercentage: {{ $invoice->tax_percentage }},
                panjar: {{ $invoice->panjar }},

                addSection() { this.sections.push({ name: '', items: [{ label: '', amount: 0 }] }); },
                removeSection(idx) { this.sections.splice(idx, 1); },
                addItem(sIdx) { this.sections[sIdx].items.push({ label: '', amount: 0 }); },
                removeItem(sIdx, iIdx) { this.sections[sIdx].items.splice(iIdx, 1); },
                sectionTotal(sIdx) { return this.sections[sIdx].items.reduce((s, i) => s + (parseFloat(i.amount) || 0), 0); },
                grandTotal() { return this.sections.reduce((s, _, i) => s + this.sectionTotal(i), 0); },
                taxAmount() { return this.includeTax ? this.grandTotal() * (this.taxPercentage / 100) : 0; },
                totalBilling() { return this.grandTotal() + this.taxAmount(); },
                totalAfterPanjar() { return this.totalBilling() - (parseFloat(this.panjar) || 0); },
                formatRp(v) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v)); },
                romanNumeral(n) { return ['I','II','III','IV','V','VI','VII','VIII','IX','X'][n-1] || n; },
                submitForm() { this.$el.submit(); }
            }
        }
    </script>
</x-app-layout>
