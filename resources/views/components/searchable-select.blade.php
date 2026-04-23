@props([
    'name',
    'options' => [],
    'selected' => '',
    'placeholder' => '-- Pilih --',
    'required' => false,
])

<div
    x-data="{
        open: false,
        search: '',
        value: @js((string) $selected),
        options: @js($options),
        dropdownStyle: '',
        get label() {
            const found = this.options.find(o => String(o.value) === String(this.value));
            return found ? found.label : '';
        },
        get filtered() {
            if (!this.search) return this.options;
            const q = this.search.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().includes(q));
        },
        openDropdown() {
            const rect = this.$refs.trigger.getBoundingClientRect();
            this.dropdownStyle = 'position:fixed;z-index:9999;top:' + (rect.bottom + 4) + 'px;left:' + rect.left + 'px;width:' + rect.width + 'px';
            this.open = true;
            this.$nextTick(() => this.$refs.searchInput?.focus());
        },
        select(opt) {
            this.value = opt.value;
            this.open = false;
            this.search = '';
        }
    }"
    @click.outside="open = false"
    @scroll.window="open = false"
    @keydown.escape.window="open = false"
    class="relative"
>
    <input type="hidden" name="{{ $name }}" :value="value" {{ $required ? 'required' : '' }}>

    <button
        type="button"
        x-ref="trigger"
        @click="open ? (open = false, search = '') : openDropdown()"
        class="w-full px-4 py-2.5 border-2 rounded-xl bg-white text-left text-sm transition-all flex items-center justify-between gap-2"
        :class="open ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-300 hover:border-gray-400'"
    >
        <span
            x-text="label || {{ Js::from($placeholder) }}"
            :class="label ? 'text-gray-900' : 'text-gray-400'"
            class="truncate"
        ></span>
        <svg
            class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-150"
            :class="{ 'rotate-180': open }"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <template x-teleport="body">
        <div
            x-show="open"
            :style="dropdownStyle"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden"
            style="display:none"
            @click.outside="open = false"
        >
            <div class="p-2 border-b border-gray-100">
                <input
                    type="text"
                    x-model="search"
                    x-ref="searchInput"
                    placeholder="Cari..."
                    @keydown.escape="open = false"
                    @click.stop
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-200"
                >
            </div>
            <ul class="max-h-52 overflow-y-auto py-1">
                @unless($required)
                <li>
                    <button type="button" @click="select({ value: '', label: '' })"
                        class="w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:bg-gray-50 transition-colors">
                        {{ $placeholder }}
                    </button>
                </li>
                @endunless
                <template x-for="opt in filtered" :key="opt.value">
                    <li>
                        <button type="button" @click="select(opt)"
                            class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                            :class="String(opt.value) === String(value) ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-900 hover:bg-gray-50'">
                            <span x-text="opt.label"></span>
                        </button>
                    </li>
                </template>
                <li x-show="filtered.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center italic">
                    Tidak ditemukan
                </li>
            </ul>
        </div>
    </template>
</div>
