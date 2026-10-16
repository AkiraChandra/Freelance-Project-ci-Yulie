@props(['order' => null, 'existingGroups' => [], 'vendors' => [], 'orderId' => null, 'orderType' => 'import'])

<div x-data="containerGroupsManager()" x-init="init()">
    <div class="bg-white rounded-2xl shadow-2xl border-2 border-gray-300 overflow-hidden">
        <div class="px-6 py-5 bg-white border-b-2 border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Container Details
                </h3>
                <p class="text-xs text-gray-600 mt-1">Kelola jenis dan detail container untuk order ini</p>
            </div>
            <button type="button" @click="addGroup()"
                class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-bold rounded-lg shadow-lg transition-all flex items-center gap-2 hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jenis Container
            </button>
        </div>

        <div class="p-6 space-y-5">
            <template x-for="(group, groupIndex) in groups" :key="groupIndex">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-4 border-blue-400 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-shadow">
                    <!-- Group Header -->
                    <div class="flex justify-between items-center mb-5 pb-4 border-b-2 border-blue-300">
                        <h4 class="font-bold text-xl text-blue-900 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Container
                        </h4>
                        <button type="button" @click="removeGroup(groupIndex)" x-show="groups.length > 1"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-lg transition-colors shadow-md hover:shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Jenis Ini
                        </button>
                    </div>

                    <!-- Group Config: Jenis & Jumlah -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                        <div class="bg-white p-4 rounded-xl border-2 border-blue-200 shadow-md">
                            <label class="block text-sm font-bold text-blue-900 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Jenis Kontainer <span class="text-red-500">*</span>
                            </label>
                            <select x-model="group.size" @change="updateGroupContainers(groupIndex)" required
                                :name="'groups['+groupIndex+'][size]'"
                                class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg bg-white text-gray-900 focus:border-blue-600 focus:ring-4 focus:ring-blue-200 transition-all text-base font-medium">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="20'">📦 20'</option>
                                <option value="40'">📦 40'</option>
                                <option value="LCL">📦 LCL</option>
                            </select>
                        </div>

                        <div class="bg-white p-4 rounded-xl border-2 border-blue-200 shadow-md">
                            <label class="block text-sm font-bold text-blue-900 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                Jumlah Kontainer <span class="text-red-500">*</span>
                            </label>
                            <input type="number" x-model.number="group.quantity" @input="updateGroupContainers(groupIndex)" min="1" required
                                :name="'groups['+groupIndex+'][quantity]'"
                                class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg bg-white text-gray-900 focus:border-blue-600 focus:ring-4 focus:ring-blue-200 transition-all text-base font-medium">
                        </div>
                    </div>

                    <!-- LCL: Simple Vendor Only -->
                    <div x-show="group.size === 'LCL'" class="space-y-4">
                        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-5">
                            <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Vendor <span class="text-red-500">*</span>
                            </label>
                            <select x-model="group.lclVendor"
                                :name="'groups['+groupIndex+'][lcl_vendor]'"
                                :required="group.size === 'LCL'"
                                class="w-full px-4 py-3 border-2 border-yellow-300 rounded-lg bg-white text-gray-900 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-200 transition-all text-base font-medium">
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-sm text-yellow-800 mt-3 flex items-start gap-2 bg-yellow-100 p-3 rounded-lg">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Info LCL:</strong> Tidak perlu isi No Container, Type Container, Combo & Combine</span>
                            </p>
                        </div>
                    </div>

                    <!-- Non-LCL: Detailed Containers -->
                    <div x-show="group.size !== 'LCL' && group.size !== ''" class="space-y-4">
                        <div class="border-t-2 border-blue-300 pt-5">
                            <h5 class="font-bold text-blue-900 mb-4 flex items-center gap-2 text-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Detail Container (Total: <span x-text="group.containers.length" class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm"></span>)
                            </h5>

                            <template x-for="(container, containerIndex) in group.containers" :key="containerIndex">
                                <div class="bg-white border-3 border-gray-400 rounded-xl p-5 mb-4 shadow-lg hover:shadow-xl transition-shadow">
                                    <div class="flex items-center gap-3 mb-4 pb-3 border-b-2 border-gray-200">
                                        <span class="text-gray-900 font-bold text-base flex items-center gap-2">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            Container #<span x-text="containerIndex + 1"></span>
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- No Container -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                                No Kontainer <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" x-model="container.number"
                                                :name="'groups['+groupIndex+'][containers]['+containerIndex+'][number]'" required
                                                placeholder="Masukkan nomor container"
                                                @input="onContainerNumberChange(groupIndex, containerIndex)"
                                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-base focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                        </div>

                                        <!-- Type Container -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                                Type Kontainer <span class="text-red-500">*</span>
                                            </label>
                                            <select x-model="container.type"
                                                :name="'groups['+groupIndex+'][containers]['+containerIndex+'][type]'" required
                                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-base focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                                <option value="">-- Pilih Type --</option>
                                                <option value="GP">GP - General Purpose</option>
                                                <option value="OT">OT - Open Top</option>
                                                <option value="HC">HC - High Cube</option>
                                                <option value="RF">RF - Reefer</option>
                                            </select>
                                        </div>

                                        <!-- Vendor -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                                Vendor <span class="text-red-500">*</span>
                                            </label>
                                            <select x-model="container.vendor"
                                                :name="'groups['+groupIndex+'][containers]['+containerIndex+'][vendor]'" required
                                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-base focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                                <option value="">-- Pilih Vendor --</option>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Combo with Same Order -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                                Combo (Order Sama)
                                            </label>
                                            <select x-model="container.combo"
                                                @change="applyCombo(groupIndex, containerIndex)"
                                                :name="'groups['+groupIndex+'][containers]['+containerIndex+'][combo]'"
                                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-base focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                                <option value="">-- Tidak Combo --</option>
                                                <template x-for="(otherContainer, otherIdx) in getSameOrderContainers(groupIndex, containerIndex)" :key="'combo-'+otherIdx">
                                                    <option :value="otherContainer.number"
                                                        x-text="otherContainer.number + ' (' + otherContainer.type + ' - ' + otherContainer.vendor + ')'">
                                                    </option>
                                                </template>
                                            </select>
                                            <p class="text-xs text-gray-600 mt-1.5 italic">🔗 Sama order, size/type/vendor sama, no berbeda</p>
                                        </div>
                                    </div>

                                    <!-- Combine with Different Order -->
                                    <div class="mt-4 pt-4 border-t-2 border-dashed border-gray-300">
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            Combine dengan Order Lain
                                        </label>
                                        <select x-model="container.combine"
                                            @change="applyCombine(groupIndex, containerIndex)"
                                            :name="'groups['+groupIndex+'][containers]['+containerIndex+'][combine]'"
                                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-base focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all">
                                            <option value="">-- Tidak Combine --</option>
                                            <template x-for="(otherOrder, orderIdx) in availableCombineOrders" :key="'order-'+orderIdx">
                                                <optgroup :label="'Order: ' + otherOrder.order_code">
                                                    <template x-for="(c, cIdx) in otherOrder.containers" :key="'comb-'+cIdx">
                                                        <option :value="otherOrder.order_code + '/' + c.container_number"
                                                            x-text="c.container_number + ' (' + c.container_type + ' - ' + c.vendor + ')'">
                                                        </option>
                                                    </template>
                                                </optgroup>
                                            </template>
                                        </select>
                                        <p class="text-xs text-gray-600 mt-1.5 italic">🔗 Beda order, <strong>customer sama</strong>, size/type/vendor/no sama, max 2 container</p>
                                    </div>

                                    <!-- Info: Combo Target (Read-only display) -->
                                    <div x-show="getComboTarget(groupIndex, containerIndex)"
                                        class="mt-3 p-3 bg-green-50 border-2 border-green-300 rounded-lg">
                                        <p class="text-sm text-green-800">
                                            <strong>✓ Combo aktif:</strong> <span x-text="getComboTarget(groupIndex, containerIndex)"></span>
                                        </p>
                                    </div>

                                    <!-- Info: Combine Target (Read-only display) -->
                                    <div x-show="container.combineSource"
                                        class="mt-3 p-3 bg-purple-50 border-2 border-purple-300 rounded-lg">
                                        <p class="text-sm text-purple-800">
                                            <strong>✓ Dikombinasi oleh:</strong> <span x-text="container.combineSource"></span>
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <p x-show="groups.length === 0" class="text-center text-gray-500 py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-lg font-semibold">Belum ada container</span><br>
                <span class="text-sm">Klik tombol "Tambah Jenis Container" di atas untuk memulai</span>
            </p>
        </div>
    </div>

    <!-- Hidden inputs for combine source tracking -->
    <template x-for="(group, groupIndex) in groups" :key="'hidden-'+groupIndex">
        <template x-for="(container, containerIndex) in group.containers" :key="'hidden-c-'+containerIndex">
            <input type="hidden"
                :name="'groups['+groupIndex+'][containers]['+containerIndex+'][combine_source]'"
                :value="container.combineSource || ''">
        </template>
    </template>
</div>

<script>
function containerGroupsManager() {
    return {
        groups: [],
        storageKey: 'container_data_{{ $orderId ?? "create" }}',
        availableCombineOrders: [],
        combineSources: {}, // Track reverse combine relationships

        init() {
            @if(isset($existingGroups) && count($existingGroups) > 0)
                // Editing existing order - use server data and clear stale cached form data
                this.groups = @json($existingGroups);
                // Initialize combineSource tracking for existing data
                this.groups.forEach(group => {
                    group.containers.forEach(container => {
                        container.combineSource = container.combineSource || '';
                    });
                });
                localStorage.removeItem(this.storageKey);
            @else
                // Try to load from localStorage first
                const savedData = localStorage.getItem(this.storageKey);

                if (savedData) {
                    try {
                        this.groups = JSON.parse(savedData);
                        // Add combineSource property to each container
                        this.groups.forEach(group => {
                            group.containers.forEach(container => {
                                if (!container.hasOwnProperty('combineSource')) {
                                    container.combineSource = '';
                                }
                            });
                        });
                    } catch (e) {
                        this.groups = [{
                            size: '',
                            quantity: 1,
                            lclVendor: '',
                            containers: []
                        }];
                    }
                } else {
                    // Default: 1 group kosong
                    this.groups = [{
                        size: '',
                        quantity: 1,
                        lclVendor: '',
                        containers: []
                    }];
                }
            @endif

            // Load available combine orders (from different orders)
            this.loadAvailableCombineOrders();

            // Auto-save ke localStorage setiap kali data berubah
            this.$watch('groups', (value) => {
                if (!this.storageKey || this.storageKey.includes('create')) {
                    localStorage.setItem(this.storageKey, JSON.stringify(value));
                }
            }, { deep: true });
        },

        async loadAvailableCombineOrders() {
            try {
                const response = await fetch('/api/containers/available-for-combine?orderId={{ $orderId ?? "null" }}&orderType={{ $orderType }}');
                if (response.ok) {
                    const data = await response.json();
                    this.availableCombineOrders = data.orders || [];
                }
            } catch (e) {
                console.log('Could not load available combine orders');
            }
        },

        addGroup() {
            this.groups.push({
                size: '',
                quantity: 1,
                lclVendor: '',
                containers: []
            });
        },

        removeGroup(index) {
            this.groups.splice(index, 1);
        },

        updateGroupContainers(groupIndex) {
            const group = this.groups[groupIndex];

            if (group.size === 'LCL' || !group.size) {
                group.containers = [];
                return;
            }

            const newCount = parseInt(group.quantity) || 0;
            const currentCount = group.containers.length;

            if (newCount > currentCount) {
                for (let i = currentCount; i < newCount; i++) {
                    group.containers.push({
                        number: '',
                        type: '',
                        vendor: '',
                        combo: '',
                        combine: '',
                        combineSource: ''
                    });
                }
            } else if (newCount < currentCount) {
                group.containers = group.containers.slice(0, newCount);
            }
        },

        // Get containers from the SAME order (all groups) for Combo
        getSameOrderContainers(groupIndex, containerIndex) {
            const currentContainer = this.groups[groupIndex]?.containers[containerIndex];
            if (!currentContainer || !currentContainer.number || !currentContainer.type || !currentContainer.vendor) {
                return [];
            }

            const sameOrderContainers = [];

            this.groups.forEach((g, gIdx) => {
                g.containers.forEach((c, cIdx) => {
                    // Skip self
                    if (gIdx === groupIndex && cIdx === containerIndex) return;

                    // Must have same: type, vendor, size
                    if (c.number &&
                        c.type === currentContainer.type &&
                        c.vendor === currentContainer.vendor &&
                        g.size === this.groups[groupIndex].size) {
                        sameOrderContainers.push({
                            ...c,
                            groupIndex: gIdx,
                            containerIndex: cIdx
                        });
                    }
                });
            });

            return sameOrderContainers;
        },

        // Apply combo - bidirectional update
        applyCombo(groupIndex, containerIndex) {
            const container = this.groups[groupIndex].containers[containerIndex];
            const selectedComboNumber = container.combo;

            // Clear previous combo target's reciprocal relationship
            this.clearPreviousComboRelationship(groupIndex, containerIndex);

            if (!selectedComboNumber) {
                return;
            }

            // Find the target container and set reciprocal combo
            for (let gIdx = 0; gIdx < this.groups.length; gIdx++) {
                const group = this.groups[gIdx];
                for (let cIdx = 0; cIdx < group.containers.length; cIdx++) {
                    const targetContainer = group.containers[cIdx];
                    if (targetContainer.number === selectedComboNumber) {
                        // Set bidirectional relationship
                        targetContainer.combo = container.number;
                        this.$nextTick(() => {
                            // Force UI update
                            this.groups[gIdx].containers[cIdx].combo = container.number;
                        });
                        return;
                    }
                }
            }
        },

        clearPreviousComboRelationship(groupIndex, containerIndex) {
            const container = this.groups[groupIndex].containers[containerIndex];
            if (!container.combo) return;

            // Find and clear the previous combo target
            for (let gIdx = 0; gIdx < this.groups.length; gIdx++) {
                const group = this.groups[gIdx];
                for (let cIdx = 0; cIdx < group.containers.length; cIdx++) {
                    const targetContainer = group.containers[cIdx];
                    if (targetContainer.number === container.combo && targetContainer.combo === container.number) {
                        targetContainer.combo = '';
                        this.$nextTick(() => {
                            this.groups[gIdx].containers[cIdx].combo = '';
                        });
                        return;
                    }
                }
            }
        },

        // Apply combine - bidirectional update
        applyCombine(groupIndex, containerIndex) {
            const container = this.groups[groupIndex].containers[containerIndex];
            const selectedCombine = container.combine;

            // Clear previous combine relationships
            this.clearPreviousCombineRelationship(groupIndex, containerIndex);

            if (!selectedCombine) {
                return;
            }

            // Parse: "order_code/container_number"
            const parts = selectedCombine.split('/');
            if (parts.length < 2) return;

            const targetOrderCode = parts[0];
            const targetContainerNumber = parts.slice(1).join('/');

            // Find the target in availableCombineOrders
            for (const order of this.availableCombineOrders) {
                if (order.order_code === targetOrderCode) {
                    for (const c of order.containers) {
                        if (c.container_number === targetContainerNumber) {
                            // Mark the target as having a combine source
                            c._combineSource = this.groups[groupIndex].containers[containerIndex].number;
                            // Store the reverse relationship info
                            this.combineSources[targetOrderCode + '/' + targetContainerNumber] =
                                this.groups[groupIndex].containers[containerIndex].number;
                            return;
                        }
                    }
                }
            }
        },

        clearPreviousCombineRelationship(groupIndex, containerIndex) {
            const container = this.groups[groupIndex].containers[containerIndex];
            if (!container.combine) return;

            // Remove from combineSources
            delete this.combineSources[container.combine];
        },

        // Get the combo target container number
        getComboTarget(groupIndex, containerIndex) {
            const container = this.groups[groupIndex]?.containers[containerIndex];
            if (!container?.combo) return null;

            // Verify the reciprocal relationship exists
            for (let gIdx = 0; gIdx < this.groups.length; gIdx++) {
                const group = this.groups[gIdx];
                for (let cIdx = 0; cIdx < group.containers.length; cIdx++) {
                    const c = group.containers[cIdx];
                    if (c.number === container.combo && c.combo === container.number) {
                        return c.number;
                    }
                }
            }

            // If no reciprocal, return null (invalid combo)
            return null;
        },

        onContainerNumberChange(groupIndex, containerIndex) {
            // Clear combo/combine when container number changes
            const container = this.groups[groupIndex].containers[containerIndex];
            if (container.combo) {
                this.clearPreviousComboRelationship(groupIndex, containerIndex);
                container.combo = '';
            }
            if (container.combine) {
                this.clearPreviousCombineRelationship(groupIndex, containerIndex);
                container.combine = '';
            }
        },

        clearStorage() {
            localStorage.removeItem(this.storageKey);
        }
    }
}
</script>
