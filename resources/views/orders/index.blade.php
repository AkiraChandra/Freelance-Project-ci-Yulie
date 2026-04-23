<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Daftar Order</h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
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
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Order</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola semua order impor dan ekspor</p>
                    </div>
                    <a href="{{ route('orders.select-type') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Order Baru
                    </a>
                </div>
            </div>

            <!-- Tabs -->
            <div x-data="{ tab: 'impor' }">
                <div class="flex gap-2 mb-4">
                    <button @click="tab = 'impor'"
                        :class="tab === 'impor' ? 'bg-purple-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                        class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">
                        Impor ({{ $importOrders->count() }})
                    </button>
                    <button @click="tab = 'ekspor'"
                        :class="tab === 'ekspor' ? 'bg-purple-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                        class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">
                        Ekspor ({{ $exportOrders->count() }})
                    </button>
                </div>

                <!-- IMPOR TABLE -->
                <div x-show="tab === 'impor'" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @if ($importOrders->isEmpty())
                        <div class="text-center py-16 text-gray-400">
                            <p class="font-medium text-lg">Belum ada order impor</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-gray-600">No Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Tgl Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Customer</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">B/L No</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Nama Barang</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Party</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Status</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($importOrders as $order)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <span class="font-mono font-bold text-purple-700 bg-purple-50 px-2 py-1 rounded text-xs">
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
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('import-orders.edit', $order) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded-lg text-xs transition-colors">
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
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- EKSPOR TABLE -->
                <div x-show="tab === 'ekspor'" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @if ($exportOrders->isEmpty())
                        <div class="text-center py-16 text-gray-400">
                            <p class="font-medium text-lg">Belum ada order ekspor</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-gray-600">No Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Tgl Order</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Customer</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">No DO</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Nama Barang</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Party</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600">Status</th>
                                        <th class="px-4 py-3 font-semibold text-gray-600 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($exportOrders as $order)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <span class="font-mono font-bold text-orange-700 bg-orange-50 px-2 py-1 rounded text-xs">
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
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('export-orders.edit', $order) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded-lg text-xs transition-colors">
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
</x-app-layout>
