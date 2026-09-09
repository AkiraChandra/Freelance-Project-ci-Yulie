<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ isset($parentInvoice) ? 'Buat Invoice Revisi (Susulan)' : 'Buat Invoice Baru' }}
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

            @if (isset($parentInvoice))
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 rounded-lg">
                    <p class="font-semibold">Invoice Revisi dari: {{ $parentInvoice->nota_number }}</p>
                    <p class="text-sm">Nota number baru akan di-generate otomatis dengan suffix berikutnya.</p>
                </div>
            @endif

            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-8"
                x-data="invoiceForm()" x-cloak>

                <form @submit.prevent="submitForm" method="POST" action="{{ route('invoices.store') }}">
                    @csrf

                    {{-- Order Selection --}}
                    <div class="mb-8 border-b pb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Pilih Order</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Order</label>
                                <select x-model="orderType" name="order_type"
                                    {{ isset($parentInvoice) ? 'disabled' : '' }}
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 disabled:bg-gray-100">
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="import">Import</option>
                                    <option value="export">Export</option>
                                </select>
                                @if (isset($parentInvoice))
                                    <input type="hidden" name="order_type" value="{{ $parentInvoice->order_type }}">
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                                <select x-model="orderId" name="order_id"
                                    {{ isset($parentInvoice) ? 'disabled' : '' }}
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 disabled:bg-gray-100">
                                    <option value="">-- Pilih Order --</option>
                                    <template x-if="orderType === 'import'">
                                        <template x-for="order in importOrders" :key="order.id">
                                            <option :value="order.id" x-text="order.import_order_number + ' — ' + (order.customer ? order.customer.customer_name : '-')"></option>
                                        </template>
                                    </template>
                                    <template x-if="orderType === 'export'">
                                        <template x-for="order in exportOrders" :key="order.id">
                                            <option :value="order.id" x-text="order.export_order_number + ' — ' + (order.customer ? order.customer.customer_name : '-')"></option>
                                        </template>
                                    </template>
                                </select>
                                @if (isset($parentInvoice))
                                    <input type="hidden" name="order_id" value="{{ $parentInvoice->order_id }}">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Judul Invoice --}}
                    <div class="mb-8 border-b pb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📄 Judul Invoice</h3>
                        <select name="invoice_title" x-model="invoiceTitle" required
                            class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm font-semibold focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            <option value="">-- Pilih Tipe Invoice --</option>
                            <option value="Reimbursement">Reimbursement</option>
                            <option value="Invoice">Invoice</option>
                        </select>
                        <input type="hidden" name="invoice_type" :value="invoiceTitle === 'Reimbursement' ? 'reimbursement' : 'invoice'">
                    </div>

                    {{-- Editable Header Fields --}}
                    <div class="mb-8 border-b pb-8" x-show="selectedOrder">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🏢 Header Invoice (bisa diedit)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nota No</label>
                                <input type="text" disabled
                                    :value="selectedOrder ? (selectedOrder.import_order_number || selectedOrder.export_order_number) : '-'"
                                    class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-gray-100 text-gray-500">
                                <p class="text-xs text-gray-400 mt-1">Auto-generate (tidak bisa diubah)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ex/ per kapal</label>
                                <input type="text" name="vessel_name" x-model="headerFields.vessel_name"
                                    class="w-full border-2 border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tgl</label>
                                <input type="text" name="vessel_date" x-model="headerFields.vessel_date"
                                    placeholder="dd.mm.yyyy"
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

                                {{-- Items --}}
                                <div class="space-y-2">
                                    <template x-for="(item, iIdx) in section.items" :key="iIdx">
                                        <div class="flex items-center gap-3">
                                            <input type="text" x-model="item.label"
                                                :name="'sections[' + sIdx + '][items][' + iIdx + '][label]'"
                                                placeholder="Label (cth: Port Charges)"
                                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-teal-400 focus:ring-1 focus:ring-teal-200">
                                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-56">
                                                <span class="bg-gray-100 px-3 py-2 text-sm text-gray-600 font-medium border-r border-gray-300">Rp</span>
                                                <input type="number" x-model.number="item.amount"
                                                    :name="'sections[' + sIdx + '][items][' + iIdx + '][amount]'"
                                                    placeholder="0"
                                                    class="flex-1 px-3 py-2 text-sm text-right focus:outline-none focus:ring-0 border-0">
                                            </div>
                                            <button type="button" @click="removeItem(sIdx, iIdx)"
                                                x-show="section.items.length > 1"
                                                class="text-red-400 hover:text-red-600 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <button type="button" @click="addItem(sIdx)"
                                        class="text-sm text-teal-600 hover:text-teal-800 font-semibold">
                                        + Tambah Baris
                                    </button>
                                    <div class="text-sm font-semibold text-gray-700">
                                        Subtotal: <span class="text-gray-900" x-text="formatRp(sectionTotal(sIdx))"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Trucking Info -->
                        <div x-show="selectedOrder" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
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
                        <a href="{{ route('invoices.index') }}"
                            class="text-gray-600 hover:text-gray-800 font-medium text-sm">
                            ← Kembali ke Daftar Invoice
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-slate-800 to-teal-700 hover:from-slate-900 hover:to-teal-800 text-white font-bold rounded-xl shadow-lg transition-all">
                            Simpan Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function invoiceForm() {
            const parentInvoice = @json($parentInvoice ?? null);

            return {
                orderType: '{{ $selectedOrderType ?? '' }}',
                orderId: '{{ $selectedOrderId ?? '' }}',
                importOrders: @json($importOrders),
                exportOrders: @json($exportOrders),
                invoiceTitle: parentInvoice ? parentInvoice.invoice_title || '' : '',
                headerFields: {
                    vessel_name: '',
                    vessel_date: '',
                    destination: '',
                    party_display: '',
                    product_name: '',
                    tonage: '',
                    merk: '',
                    container_display: '',
                },
                sections: [
                    { name: 'Reimbursement', items: [{ label: '', amount: 0 }] },
                    { name: 'Detail Invoice', items: [{ label: '', amount: 0 }] },
                    { name: 'Trucking', items: [{ label: '', amount: 0 }] }
                ],
                includeTax: parentInvoice ? !!parentInvoice.include_tax : false,
                taxPercentage: parentInvoice ? parseFloat(parentInvoice.tax_percentage) || 1.1 : 1.1,
                taxableSections: parentInvoice ? (parentInvoice.taxable_sections || []) : [],
                panjar: parentInvoice ? parseFloat(parentInvoice.panjar) || 0 : 0,

                init() {
                    // If parent (revision), pre-fill header from parent invoice
                    if (parentInvoice) {
                        this.headerFields = {
                            vessel_name: parentInvoice.vessel_name || '',
                            vessel_date: parentInvoice.vessel_date || '',
                            destination: parentInvoice.destination || '',
                            party_display: parentInvoice.party_display || '',
                            product_name: parentInvoice.product_name || '',
                            tonage: parentInvoice.tonage || '',
                            merk: parentInvoice.merk || '',
                            container_display: parentInvoice.container_display || '',
                        };
                        return;
                    }

                    // Watch for order selection to auto-fill header
                    this.$watch('orderId', (val) => {
                        if (!val) return;
                        const order = this.selectedOrder;
                        if (!order) return;
                        const isImport = this.orderType === 'import';
                        this.headerFields = {
                            vessel_name: order.vessel_name || '',
                            vessel_date: order.order_date || '',
                            destination: '',
                            party_display: order.party || '',
                            product_name: order.product_name || '',
                            tonage: '',
                            merk: isImport ? (order.pib_number || '') : ('PEB NO. ' + (order.peb_number || '')),
                            container_display: order.container_number || '',
                        };
                    });
                },

                get selectedOrder() {
                    if (!this.orderId) return null;
                    const list = this.orderType === 'import' ? this.importOrders : this.exportOrders;
                    return list.find(o => o.id == this.orderId) || null;
                },

                addItem(sIdx) {
                    this.sections[sIdx].items.push({ label: '', amount: 0 });
                },
                removeItem(sIdx, iIdx) {
                    this.sections[sIdx].items.splice(iIdx, 1);
                },
                sectionTotal(sIdx) {
                    return this.sections[sIdx].items.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
                },
                grandTotal() {
                    return this.sections.reduce((sum, s, idx) => sum + this.sectionTotal(idx), 0);
                },
                taxAmount() {
                    if (!this.includeTax) return 0;
                    if (!this.taxableSections || this.taxableSections.length === 0) return 0;
                    
                    let taxableTotal = 0;
                    this.taxableSections.forEach(sectionIndex => {
                        taxableTotal += this.sectionTotal(sectionIndex);
                    });
                    
                    return taxableTotal * (this.taxPercentage / 100);
                },
                totalBilling() {
                    return this.grandTotal() + this.taxAmount();
                },
                totalAfterPanjar() {
                    return this.totalBilling() - (parseFloat(this.panjar) || 0);
                },
                formatRp(val) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(val));
                },
                romanNumeral(num) {
                    const map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
                    return map[num - 1] || num;
                },
                submitForm() {
                    this.$el.submit();
                }
            }
        }
    </script>
</x-app-layout>
