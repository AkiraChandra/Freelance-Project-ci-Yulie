<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            ➕ Tambah Penugasan Staff Operasional
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Errors --}}
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

            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8"
                x-data="{
                    orderType: '{{ old('order_type', 'export') }}',
                    selectedStaff: '{{ old('operational_staff_id', '') }}',
                    exportOrders: {{ $exportOrders->map(fn($o) => ['id' => $o->id, 'label' => $o->export_order_number . ($o->customer ? ' — ' . $o->customer->customer_name : '')])->toJson() }},
                    importOrders: {{ $importOrders->map(fn($o) => ['id' => $o->id, 'label' => $o->import_order_number . ($o->customer ? ' — ' . $o->customer->customer_name : '')])->toJson() }},
                    assignedMap: {{ $assignedMap->toJson() }},
                    get assignedIds() {
                        if (!this.selectedStaff) return [];
                        const staffData = this.assignedMap[this.selectedStaff];
                        if (!staffData) return [];
                        return staffData[this.orderType] || [];
                    },
                    get currentOrders() {
                        const all = this.orderType === 'export' ? this.exportOrders : this.importOrders;
                        return all.filter(o => !this.assignedIds.includes(o.id));
                    }
                }">

                <h3 class="text-xl font-bold text-gray-900 mb-6">Detail Penugasan</h3>

                <form method="POST" action="{{ route('staff-assignments.store') }}">
                    @csrf

                    {{-- Staff Operasional --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Staff Operasional <span class="text-red-500">*</span>
                        </label>
                        <select name="operational_staff_id" x-model="selectedStaff" required
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option value="">-- Pilih Staff --</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}" {{ old('operational_staff_id') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }}{{ $staff->contact ? ' (' . $staff->contact . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @if ($staffs->isEmpty())
                            <p class="text-xs text-amber-600 mt-1">⚠️ Belum ada staff aktif. Minta owner untuk menambahkan staff terlebih dahulu.</p>
                        @endif
                    </div>

                    {{-- Tipe Order --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Tipe Order <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="order_type" value="export" x-model="orderType"
                                    class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                <span class="text-sm font-medium text-gray-700">📤 Ekspor</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="order_type" value="import" x-model="orderType"
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">📥 Impor</span>
                            </label>
                        </div>
                    </div>

                    {{-- Order --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Pilih Order <span class="text-red-500">*</span>
                        </label>
                        <select name="order_id" required
                            class="w-full px-4 py-2.5 border-2 {{ $errors->has('order_id') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-gray-900 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option value="">-- Pilih Order --</option>
                            <template x-for="order in currentOrders" :key="order.id">
                                <option :value="order.id" x-text="order.label"
                                    :selected="order.id == {{ old('order_id', 0) }}"></option>
                            </template>
                        </select>
                        {{-- Info jika semua order sudah di-assign untuk staff ini --}}
                        <p x-show="selectedStaff && currentOrders.length === 0"
                            class="mt-1.5 text-sm text-amber-600 flex items-center gap-1">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Semua order sudah pernah di-assign ke staff ini.
                        </p>
                        @error('order_id')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Fee --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Biaya / Gaji <span class="text-red-500">*</span>
                        </label>
                        <div class="flex rounded-lg border-2 border-gray-300 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200 transition-all overflow-hidden">
                            <span class="flex items-center px-3 bg-gray-100 text-gray-600 font-semibold text-sm border-r-2 border-gray-300 select-none whitespace-nowrap">
                                Rp
                            </span>
                            <input type="number" name="fee" value="{{ old('fee', 0) }}" min="0" step="1000" required
                                class="flex-1 px-4 py-2.5 text-gray-900 text-sm bg-white outline-none"
                                placeholder="0">
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="notes" rows="3" maxlength="500" placeholder="Keterangan tambahan..."
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg shadow-lg transition-all duration-200">
                            💾 Simpan Penugasan
                        </button>
                        <a href="{{ route('staff-assignments.index') }}"
                            class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
