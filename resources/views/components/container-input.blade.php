@props(['order' => null, 'existingGroups' => [], 'vendors' => [], 'orderId' => null, 'orderType' => 'import', 'availableCombineContainers' => []])

<div x-data="containerGroupsManager()" class="space-y-6">
    <section class="bg-white rounded-2xl border border-teal-200/80 shadow-md p-6 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-teal-500 via-teal-600 to-emerald-500"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b border-slate-100 gap-3">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-xl bg-teal-600 flex items-center justify-center text-white shadow-sm">
                    <span class="material-symbols-outlined text-2xl">package_2</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight font-display">Container Details</h3>
                        <span class="px-2 py-0.5 bg-teal-100 text-teal-800 text-[11px] font-bold rounded-full"
                            x-text="totalCargoSummaryBadge()">
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Konfigurasi jenis kontainer, tipe box (GP, OT, HC, RF), vendor pengangkut, combo dan combine</p>
                </div>
            </div>

            <button type="button" @click="addGroup()"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 hover:bg-teal-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-xs transition-all duration-200 hover:shadow">
                <span class="material-symbols-outlined text-base">add</span>
                <span>+ Tambah Jenis Container</span>
            </button>
        </div>

        <div class="space-y-6">
            <template x-for="(group, groupIndex) in groups" :key="groupIndex">
                <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50/70 relative transition-all duration-300">
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="size-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold flex items-center justify-center" x-text="groupIndex + 1"></span>
                            <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wide" x-text="'Alokasi Grup Kontainer #' + (groupIndex + 1)"></span>
                        </div>
                        <div class="flex items-center gap-3 ml-auto">
                            <button type="button" @click="removeGroup(groupIndex)" x-show="groups.length > 1"
                                class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                <span>Hapus Jenis Ini</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-5 bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Jenis Kontainer <span class="text-rose-500">*</span>
                            </label>
                            <select x-model="group.size" @change="updateGroupContainers(groupIndex)" required
                                :name="'groups['+groupIndex+'][size]'"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="20'">20' (Twenty Feet)</option>
                                <option value="40'">40' (Forty Feet)</option>
                                <option value="LCL">LCL (Less than Container Load)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Jumlah Box (Kuantitas) <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex items-center">
                                <input type="number" x-model.number="group.quantity" @input="updateGroupContainers(groupIndex)" min="1" max="50" required
                                    :name="'groups['+groupIndex+'][quantity]'"
                                    :disabled="group.size === 'LCL'"
                                    :class="group.size === 'LCL' ? 'opacity-40 cursor-not-allowed' : ''"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                                <span class="ml-2 text-xs font-semibold text-slate-500">Unit</span>
                            </div>
                        </div>

                        <div class="sm:col-span-2 flex items-center justify-start sm:justify-end text-xs text-slate-500">
                            <div class="bg-teal-50 text-teal-800 px-3 py-2 rounded-lg border border-teal-100 flex items-center gap-2">
                                <span class="material-symbols-outlined text-teal-600 text-sm">info</span>
                                <span x-show="group.size === '40\''">Ukuran 40' ideal untuk komoditas volume besar atau bobot maksimal 28 Ton.</span>
                                <span x-show="group.size === '20\''">Ukuran 20' cocok untuk kargo padat berat (Heavy Cargo) hingga 24 Ton.</span>
                                <span x-show="group.size === 'LCL'">Mode kargo konsolidasi LCL aktif.</span>
                                <span x-show="!group.size">Pilih ukuran kontainer atau LCL untuk melihat detail unit.</span>
                            </div>
                        </div>
                    </div>

                    <!-- LCL Mode Panel -->
                    <div x-show="group.size === 'LCL'" class="mt-4 p-4 rounded-xl bg-teal-50/80 border border-teal-200 text-teal-900">
                        <template x-for="id in (group.ids || [])" :key="id">
                            <input type="hidden" :name="'groups['+groupIndex+'][ids][]'" :value="id" :disabled="group.size !== 'LCL'">
                        </template>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-teal-600 text-xl mt-0.5">inventory</span>
                            <div class="flex-1">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-teal-900">Mode Muatan LCL Aktif</h4>
                                <p class="text-xs text-teal-800 mt-0.5 leading-relaxed">
                                    Hanya perlu memilih vendor pengangkut kargo konsolidasi. Nomor kontainer, tipe box, combo, dan combine tidak diperlukan.
                                </p>
                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-md">
                                    <div>
                                        <label class="block text-[11px] font-bold text-teal-900 mb-1">Vendor Pengangkut Konsolidasi *</label>
                                        <select x-model="group.lclVendor"
                                            :name="'groups['+groupIndex+'][lcl_vendor]'"
                                            :required="group.size === 'LCL'"
                                            class="w-full px-2.5 py-1.5 text-xs font-semibold bg-white border border-teal-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                            <option value="">-- Pilih Vendor --</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Non-LCL Containers Unit Cards -->
                    <div x-show="group.size !== 'LCL' && group.size !== ''" class="space-y-3">
                        <template x-for="(container, containerIndex) in group.containers" :key="containerIndex">
                            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-bold text-slate-700" x-text="'Unit #' + (containerIndex + 1)"></span>
                                    <div class="size-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-xs font-bold text-slate-800" x-text="group.size + ' ' + (container.type || 'Standard')"></span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 flex-1">
                                    <input type="hidden" x-model="container.id" :name="'groups['+groupIndex+'][containers]['+containerIndex+'][id]'">

                                    <!-- Nomor Kontainer -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">No Kontainer *</label>
                                        <input type="text" x-model="container.number"
                                            :name="'groups['+groupIndex+'][containers]['+containerIndex+'][number]'"
                                            :required="group.size !== 'LCL'"
                                            placeholder="Contoh: MSKU-982140-1"
                                            class="w-full px-2.5 py-1.5 text-xs font-mono font-bold bg-white border border-slate-200 rounded-lg uppercase focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                    </div>

                                    <!-- Tipe Kontainer -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Type Kontainer *</label>
                                        <select x-model="container.type"
                                            :name="'groups['+groupIndex+'][containers]['+containerIndex+'][type]'"
                                            :required="group.size !== 'LCL'"
                                            class="w-full px-2.5 py-1.5 text-xs font-semibold bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                            <option value="">-- Pilih --</option>
                                            <option value="GP">GP - General Purpose</option>
                                            <option value="OT">OT - Open Top</option>
                                            <option value="HC">HC - High Cube</option>
                                            <option value="RF">RF - Reefer</option>
                                        </select>
                                    </div>

                                    <!-- Vendor Pengangkut -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Vendor Pengangkut *</label>
                                        <select x-model="container.vendor"
                                            :name="'groups['+groupIndex+'][containers]['+containerIndex+'][vendor]'"
                                            :required="group.size !== 'LCL'"
                                            class="w-full px-2.5 py-1.5 text-xs font-semibold bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                            <option value="">-- Pilih --</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Combo Dropdown -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Combo</label>
                                        <select x-model="container.combo"
                                            :name="'groups['+groupIndex+'][containers]['+containerIndex+'][combo]'"
                                            class="w-full px-2.5 py-1.5 text-xs font-semibold bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                            <option value="">-- Tidak Combo --</option>
                                            <template x-for="(c, idx) in group.containers" :key="idx">
                                                <option x-show="idx !== containerIndex && c.type === container.type && c.number"
                                                    :value="c.number"
                                                    x-text="'Unit #' + (idx + 1) + ' - ' + c.number">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Combine Dropdown -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Combine</label>
                                        <select x-model="container.combine"
                                            :name="'groups['+groupIndex+'][containers]['+containerIndex+'][combine]'"
                                            class="w-full px-2.5 py-1.5 text-xs font-semibold bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                            <option value="">-- Tidak Combine --</option>
                                            <template x-if="container.combine && !combineCandidates(group, container).some(c => String(c.id) === String(container.combine))">
                                                <option :value="container.combine" x-text="'Pilihan tersimpan #' + container.combine"></option>
                                            </template>
                                            <template x-for="candidate in combineCandidates(group, container)" :key="candidate.id">
                                                <option :value="candidate.id" x-text="candidate.orderNumber + ' - ' + candidate.number"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="groups.length === 0" class="text-center text-slate-500 py-10 bg-slate-50 border border-dashed border-slate-300 rounded-2xl">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">package_2</span>
                <p class="text-xs font-bold text-slate-700">Belum ada grup kontainer</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Klik "+ Tambah Jenis Container" di atas untuk menambahkan kontainer.</p>
            </div>
        </div>
    </section>
</div>

<script>
function containerGroupsManager() {
    return {
        groups: [],
        availableCombineContainers: @js($availableCombineContainers),
        storageKey: 'container_data_{{ $orderType }}_{{ $orderId ?? "create" }}',

        init() {
            @if(session()->hasOldInput('groups'))
                this.groups = @js(old('groups', []));
            @elseif($order !== null)
                this.groups = @js($existingGroups);
            @else
                // Draft kontainer hanya dipulihkan setelah user memilih "Lanjutkan Draft".
                this.groups = [];
                window.addEventListener('order-container-draft-restore', () => {
                    try {
                        const saved = JSON.parse(localStorage.getItem(this.storageKey) || '[]');
                        this.groups = Array.isArray(saved) ? saved : Object.values(saved || {});
                        this.updateFooterCargoSummary();
                    } catch (e) {
                        this.groups = [];
                    }
                }, { once: true });
            @endif

            if (!Array.isArray(this.groups)) this.groups = Object.values(this.groups || {});
            this.groups = this.groups.filter(group => group && typeof group === 'object').map(group => ({
                ...this.blankGroup(),
                ...group,
                lclVendor: group.lcl_vendor ?? group.lclVendor ?? '',
                ids: Array.isArray(group.ids) ? group.ids.filter(Boolean) : [],
                containers: Object.values(group.containers || {}).map(container => ({
                    id: '', number: '', type: '', vendor: '', combo: '', combine: '', ...container
                }))
            }));
            if (this.groups.length === 0) this.groups = [this.blankGroup()];

            this.$watch('groups', (value) => {
                @if($order === null)
                    try {
                        localStorage.setItem(this.storageKey, JSON.stringify(value));
                    } catch (e) {}
                @endif
                this.updateFooterCargoSummary();
            });

            this.updateFooterCargoSummary();
        },

        blankGroup() {
            return {
                size: "40'",
                quantity: 1,
                lclVendor: '',
                ids: [],
                containers: [{
                    id: '', number: '', type: 'GP', vendor: '', combo: '', combine: ''
                }]
            };
        },

        addGroup() {
            const group = this.blankGroup();
            this.groups.push(group);
            this.updateFooterCargoSummary();
        },

        removeGroup(index) {
            this.groups.splice(index, 1);
            this.updateFooterCargoSummary();
        },

        updateGroupContainers(groupIndex) {
            const group = this.groups[groupIndex];
            const newCount = Math.max(1, parseInt(group.quantity) || 1);

            if (group.size === 'LCL') {
                group.containers = [];
                const ids = Array.isArray(group.ids) ? group.ids.slice(0, newCount) : [];
                group.ids = ids.filter(Boolean);
                this.updateFooterCargoSummary();
                return;
            }

            group.ids = [];
            if (!group.size) {
                group.containers = [];
                this.updateFooterCargoSummary();
                return;
            }

            const currentCount = group.containers.length;
            if (newCount > currentCount) {
                for (let i = currentCount; i < newCount; i++) {
                    group.containers.push({
                        id: '',
                        number: '',
                        type: 'GP',
                        vendor: '',
                        combo: '',
                        combine: ''
                    });
                }
            } else if (newCount < currentCount) {
                group.containers = group.containers.slice(0, newCount);
            }
            this.updateFooterCargoSummary();
        },

        combineCandidates(group, container) {
            if (!group.size || !container.type || !container.vendor) return [];
            return this.availableCombineContainers.filter(c =>
                c.size === group.size &&
                c.type === container.type &&
                c.vendor === container.vendor
            );
        },

        totalCargoSummaryBadge() {
            let totalBoxes = 0;
            let countLcl = 0;
            this.groups.forEach(g => {
                if (g.size === 'LCL') {
                    countLcl++;
                } else if (g.size) {
                    totalBoxes += (parseInt(g.quantity) || 1);
                }
            });
            return totalBoxes + ' Unit Box' + (countLcl > 0 ? ' + LCL' : '');
        },

        updateFooterCargoSummary() {
            const footerEl = document.getElementById('footerCargoSummary');
            if (!footerEl) return;

            let totalBoxes = 0;
            let count40 = 0;
            let count20 = 0;
            let countLcl = 0;

            this.groups.forEach(g => {
                const qty = parseInt(g.quantity) || 1;
                if (g.size === "40'") {
                    count40 += qty;
                    totalBoxes += qty;
                } else if (g.size === "20'") {
                    count20 += qty;
                    totalBoxes += qty;
                } else if (g.size === 'LCL') {
                    countLcl += 1;
                }
            });

            const parts = [];
            if (count40 > 0) parts.push(count40 + 'x 40ft');
            if (count20 > 0) parts.push(count20 + 'x 20ft');
            if (countLcl > 0) parts.push(countLcl + 'x LCL');

            const desc = parts.length > 0 ? parts.join(', ') : 'Belum ditentukan';
            footerEl.innerText = 'Total Kargo: ' + totalBoxes + ' Kontainer • ' + desc;
        }
    };
}
</script>