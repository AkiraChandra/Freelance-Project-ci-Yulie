<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Daftar Order</h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 mb-6 border border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Order</h1>
                        <p class="text-slate-300 text-sm mt-1">Kelola semua order impor dan ekspor</p>
                    </div>
                    @hasanyrole('staff|manager')
                    <a href="{{ route('orders.select-type') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-white/15 hover:bg-white/25 text-white font-semibold rounded-xl border border-white/20 backdrop-blur-sm transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Order Baru
                    </a>
                    @endhasanyrole
                </div>
            </div>

            <!-- Tabs + Filter -->
            <div x-data="{
                tab: 'impor',
                showCompleted: false,
                showCancelled: false,
                matchStatus(status) {
                    if (status === 'on going') return true;
                    if (status === 'completed' && this.showCompleted) return true;
                    if (status === 'cancelled' && this.showCancelled) return true;
                    return false;
                }
            }">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
                    <div class="flex gap-2">
                        <button @click="tab = 'impor'"
                            :class="tab === 'impor' ? 'bg-teal-600 text-white shadow' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200'"
                            class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">
                            Impor ({{ $importOrders->count() }})
                        </button>
                        <button @click="tab = 'ekspor'"
                            :class="tab === 'ekspor' ? 'bg-teal-600 text-white shadow' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200'"
                            class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">
                            Ekspor ({{ $exportOrders->count() }})
                        </button>
                    </div>
                    <div class="flex items-center gap-4 ml-0 sm:ml-4 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tampilkan:</span>
                        <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                            <span class="text-gray-600 font-medium">On Going</span>
                            <span class="text-xs text-gray-400">(default)</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer text-sm select-none">
                            <input type="checkbox" x-model="showCompleted" class="w-4 h-4 rounded accent-green-600">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-gray-700 font-medium">Completed</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer text-sm select-none">
                            <input type="checkbox" x-model="showCancelled" class="w-4 h-4 rounded accent-red-500">
                            <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                            <span class="text-gray-700 font-medium">Cancelled</span>
                        </label>
                    </div>
                </div>

                <!-- IMPOR TABLE -->
                <div x-show="tab === 'impor'" class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    @if ($importOrders->isEmpty())
                        <div class="text-center py-16 text-gray-400">
                            <p class="font-medium text-lg">Belum ada order impor</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-100 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-gray-600">No Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Tgl Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Customer</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">B/L No</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Nama Barang</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Party</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Status</th>
                                        @hasanyrole('staff|manager')
                                        <th class="px-4 py-3 font-semibold text-gray-600 text-center">Aksi</th>
                                        @endhasanyrole
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($importOrders as $order)
                                        <tr class="hover:bg-teal-50/50 transition-colors"
                                            x-show="matchStatus('{{ $order->status }}')">
                                            <td class="px-4 py-3">
                                                    {{ $order->import_order_number }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $order->order_date->format('d M Y') }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $order->customer?->customer_name ?? '-' }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ $order->bl_number }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ $order->product_name }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700">{{ $order->party }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $statusColors = ['on going' => 'bg-yellow-100 text-yellow-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                                @endphp
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? '' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @hasanyrole('staff|manager')
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('import-orders.edit', $order) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 font-medium rounded-lg text-xs transition-colors">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('import-orders.destroy', $order) }}" method="POST"
                                                        onsubmit="return confirm('Hapus order {{ $order->import_order_number }}?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg text-xs transition-colors">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                                @endhasanyrole
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- EKSPOR TABLE -->
                <div x-show="tab === 'ekspor'" class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    @if ($exportOrders->isEmpty())
                        <div class="text-center py-16 text-gray-400">
                            <p class="font-medium text-lg">Belum ada order ekspor</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-100 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-gray-600">No Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Tgl Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Customer</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">No DO</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Nama Barang</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Party</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Status</th>
                                        @hasanyrole('staff|manager')
                                        <th class="px-4 py-3 font-semibold text-gray-600 text-center">Aksi</th>
                                        @endhasanyrole
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($exportOrders as $order)
                                        <tr class="hover:bg-teal-50/50 transition-colors"
                                            x-show="matchStatus('{{ $order->status }}')">
                                            <td class="px-4 py-3">
                                                    {{ $order->export_order_number }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $order->order_date->format('d M Y') }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $order->customer?->customer_name ?? '-' }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ $order->do_number }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ $order->product_name }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 text-orange-700">{{ $order->party }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $statusColors = ['on going' => 'bg-yellow-100 text-yellow-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                                @endphp
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? '' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @hasanyrole('staff|manager')
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('export-orders.edit', $order) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 font-medium rounded-lg text-xs transition-colors">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('export-orders.destroy', $order) }}" method="POST"
                                                        onsubmit="return confirm('Hapus order {{ $order->export_order_number }}?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg text-xs transition-colors">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                                @endhasanyrole
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if form was just submitted and succeeded
        if (sessionStorage.getItem('form_submitting') === 'true') {
            // Clear all container localStorage for create forms
            localStorage.removeItem('container_data_create');
            // Remove the flag
            sessionStorage.removeItem('form_submitting');
        }
    });
    </script>
</x-app-layout>
