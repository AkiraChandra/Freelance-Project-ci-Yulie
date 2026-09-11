@php $isRevision = $isRevision ?? false; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ $isRevision ? 'Buat Invoice Susulan — dari ' . $invoice->nota_number : 'Edit Invoice — ' . $invoice->nota_number }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
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
                    <p class="font-semibold">Mode Invoice Susulan</p>
                    <p class="text-sm">Invoice baru akan dibuat dengan nomor yang mengikuti pola susulan. Anda bisa menambah, menghapus, atau mengubah section dan baris.</p>
                </div>
            @else
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 text-amber-800 p-4 rounded-lg">
                    <p class="font-semibold">Mode Edit</p>
                    <p class="text-sm">Anda hanya bisa mengubah nilai baris yang sudah ada (label & amount). Untuk menambah atau menghapus baris, gunakan tombol <strong>"Buat Invoice Susulan"</strong> di halaman detail.</p>
                </div>
            @endif

            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-8"
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
                        <select name="invoice_title" x-model="invoiceTitle" required
                            class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm font-semibold focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            <option value="">-- Pilih Judul Invoice --</option>
                            <option value="Invoice">Invoice</option>
                        </select>
                        <input type="hidden" name="invoice_type" value="invoice">
                    </div>

                    {{-- Editable Header Fields --}}
                    <div class="mb-8 border-b pb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Header Invoice</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ex/ per kapal</label>
                                <input type="text" name="vessel_name" x-model="headerFields.vessel_name"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tgl</label>
                                <input type="text" name="vessel_date" x-model="headerFields.vessel_date"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                                <input type="text" name="destination" x-model="headerFields.destination"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Party</label>
                                <input type="text" name="party_display" x-model="headerFields.party_display"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</label>
                                <input type="text" name="product_name" x-model="headerFields.product_name"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tonage</label>
                                <input type="text" name="tonage" x-model="headerFields.tonage"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Merk / PEB / PIB</label>
                                <input type="text" name="merk" x-model="headerFields.merk"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cont</label>
                                <input type="text" name="container_display" x-model="headerFields.container_display"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                        </div>
                    </div>

                    {{-- Sections --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">📝 Perincian / Sections (Fixed 3 Section)</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            <strong>Section 1:</strong> Reimbursement (tidak dihitung pajak) • 
                            <strong>Section 2:</strong> Detail Invoice • 
                            <strong>Section 3:</strong> Trucking
                        </p>

                        <template x-for="(section, sIdx) in sections" :key="sIdx">
                            <div class="mb-6 border-2 border-gray-200 rounded-xl p-5 bg-gray-50">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-sm font-bold text-gray-500" x-text="romanNumeral(sIdx + 1) + ')'"></span>
                                    <input type="text" x-model="section.name" readonly
                                        :name="'sections[' + sIdx + '][name]'"
                                        class="flex-1 border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold bg-gray-100 cursor-not-allowed">
                                </div>

                                <div class="space-y-2">
                                    <template x-for="(item, iIdx) in section.items" :key="iIdx">
                                        <div class="flex items-center gap-3">
                                            <input type="text" x-model="item.label"
                                                :name="'sections[' + sIdx + '][items][' + iIdx + '][label]'"
                                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-teal-400 focus:ring-1 focus:ring-teal-200">
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
                                            class="text-sm text-teal-600 hover:text-teal-800 font-semibold">
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
                        
                        <!-- Trucking Info -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-sm font-bold text-blue-900 mb-2">ℹ️ Info Trucking</h4>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• <strong>Section 3 (Trucking)</strong> digunakan untuk biaya trucking dari orderan</li>
                                <li>• Dapat dipilih untuk dikenakan pajak atau tidak (lihat opsi pajak di bawah)</li>
                                <li>• Isikan detail biaya trucking sesuai dengan container yang digunakan</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Tax & Panjar --}}
                    <div class="mb-8 border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">💰 Pajak & Panjar</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <label class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl p-4 cursor-pointer select-none">
                                <input type="checkbox" x-model="includeTax" name="include_tax" value="1"
                                    class="w-5 h-5 rounded accent-teal-600">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Termasuk Pajak</p>
                                    <p class="text-xs text-gray-500">PPN akan ditambahkan dari section yang dipilih</p>
                                </div>
                            </label>
                            
                            <!-- Taxable Sections Selection -->
                            <div x-show="includeTax" class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                <p class="text-sm font-bold text-amber-900 mb-3">Pilih Section yang Dikenakan Pajak:</p>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" disabled checked
                                            class="w-4 h-4 rounded accent-gray-400 cursor-not-allowed">
                                        <span class="text-gray-400"><del>Section 1: Reimbursement</del> (tidak dikenakan pajak)</span>
                                    </label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" x-model="taxableSections" name="taxable_sections[]" value="1"
                                            class="w-4 h-4 rounded accent-teal-600">
                                        <span class="text-gray-800 font-medium">Section 2: Detail Invoice</span>
                                    </label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" x-model="taxableSections" name="taxable_sections[]" value="2"
                                            class="w-4 h-4 rounded accent-teal-600">
                                        <span class="text-gray-800 font-medium">Section 3: Trucking</span>
                                    </label>
                                </div>
                                <p class="text-xs text-amber-700 mt-3">💡 Pilih satu atau kedua section untuk dihitung pajaknya</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">% Pajak</label>
                                    <input type="number" step="0.1" x-model.number="taxPercentage" name="tax_percentage"
                                        :disabled="!includeTax"
                                        class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 disabled:bg-gray-100 disabled:text-gray-400">
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
                    </div>

                    {{-- Summary --}}
                    <div class="mb-8 bg-gradient-to-br from-teal-50 to-emerald-50 border-2 border-teal-200 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">📊 Ringkasan</h3>
                        <div class="space-y-2 text-sm">
                            <template x-for="(section, sIdx) in sections" :key="'sum-'+sIdx">
                                <div class="flex justify-between">
                                    <span class="text-gray-600" x-text="section.name || '(Section ' + (sIdx+1) + ')'"></span>
                                    <span class="font-semibold" x-text="formatRp(sectionTotal(sIdx))"></span>
                                </div>
                            </template>
                            <div class="border-t border-teal-200 my-2 pt-2 flex justify-between font-semibold">
                                <span>Grand Total</span>
                                <span x-text="formatRp(grandTotal())"></span>
                            </div>
                            <template x-if="includeTax && taxableSections.length > 0">
                                <div class="bg-amber-50 border border-amber-200 rounded p-2 my-2">
                                    <div class="flex justify-between text-gray-600 text-xs mb-1">
                                        <span>Basis Pajak:</span>
                                        <span></span>
                                    </div>
                                    <template x-for="sIdx in taxableSections" :key="'tax-'+sIdx">
                                        <div class="flex justify-between text-gray-600 text-xs pl-3">
                                            <span x-text="'• ' + sections[sIdx].name"></span>
                                            <span x-text="formatRp(sectionTotal(sIdx))"></span>
                                        </div>
                                    </template>
                                    <div class="flex justify-between text-gray-700 font-medium text-xs mt-1 pt-1 border-t border-amber-300">
                                        <span x-text="'PPN ' + taxPercentage + '%'"></span>
                                        <span x-text="formatRp(taxAmount())"></span>
                                    </div>
                                </div>
                            </template>
                            <div class="flex justify-between font-bold text-base border-t border-teal-200 pt-2">
                                <span>Jumlah Tagihan Keseluruhan</span>
                                <span class="text-teal-700" x-text="formatRp(totalBilling())"></span>
                            </div>
                            <template x-if="panjar > 0">
                                <div class="flex justify-between text-gray-600">
                                    <span>Panjar</span>
                                    <span x-text="'- ' + formatRp(panjar)"></span>
                                </div>
                            </template>
                            <template x-if="panjar > 0">
                                <div class="flex justify-between font-bold text-lg border-t border-teal-300 pt-2">
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
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-slate-800 to-teal-700 hover:from-slate-900 hover:to-teal-800 text-white font-bold rounded-xl shadow-lg transition-all">
                            {{ $isRevision ? 'Buat Invoice Revisi' : 'Update Invoice' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function invoiceEditForm() {
            let initialSections = @json($invoice->sections);
            const sectionNames = ['Reimbursement', 'Detail Invoice', 'Trucking'];
            
            // If sections don't match fixed 3, normalize them
            if (initialSections.length !== 3) {
                const tempSections = [];
                for (let i = 0; i < 3; i++) {
                    tempSections.push({
                        name: sectionNames[i],
                        items: initialSections[i]?.items || [{ label: '', amount: 0 }]
                    });
                }
                initialSections = tempSections;
            } else {
                // Ensure correct names
                initialSections = initialSections.map((section, idx) => ({
                    name: sectionNames[idx],
                    items: section.items || [{ label: '', amount: 0 }]
                }));
            }
            
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
                sections: initialSections,
                includeTax: {{ $invoice->include_tax ? 'true' : 'false' }},
                taxPercentage: {{ $invoice->tax_percentage }},
                taxableSections: @json($invoice->taxable_sections ?? []),
                panjar: {{ $invoice->panjar }},

                addItem(sIdx) { this.sections[sIdx].items.push({ label: '', amount: 0 }); },
                removeItem(sIdx, iIdx) { this.sections[sIdx].items.splice(iIdx, 1); },
                sectionTotal(sIdx) { return this.sections[sIdx].items.reduce((s, i) => s + (parseFloat(i.amount) || 0), 0); },
                grandTotal() { return this.sections.reduce((s, _, i) => s + this.sectionTotal(i), 0); },
                taxAmount() {
                    if (!this.includeTax) return 0;
                    if (!this.taxableSections || this.taxableSections.length === 0) return 0;
                    
                    let taxableTotal = 0;
                    this.taxableSections.forEach(sectionIndex => {
                        taxableTotal += this.sectionTotal(sectionIndex);
                    });
                    
                    return taxableTotal * (this.taxPercentage / 100);
                },
                totalBilling() { return this.grandTotal() + this.taxAmount(); },
                totalAfterPanjar() { return this.totalBilling() - (parseFloat(this.panjar) || 0); },
                formatRp(v) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v)); },
                romanNumeral(n) { return ['I','II','III','IV','V','VI','VII','VIII','IX','X'][n-1] || n; },
                submitForm() { this.$el.submit(); }
            }
        }
    </script>
</x-app-layout>
