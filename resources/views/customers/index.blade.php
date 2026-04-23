<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            Daftar Customer
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ search: '' }">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Customer</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola daftar customer ekspor dan impor</p>
                    </div>
                    <a href="{{ route('customers.create') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Daftarkan Customer
                    </a>
                </div>
                <!-- Search Bar -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="relative max-w-sm">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari nama atau kode customer..."
                            class="w-full pl-9 pr-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-900 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all">
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                @if ($customers->isEmpty())
                    <div class="text-center py-20 text-gray-400">
                        <svg class="w-14 h-14 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M15 11a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="font-medium text-lg">Belum ada customer</p>
                        <p class="text-sm mt-1">Klik tombol <strong>Daftarkan Customer</strong> untuk menambahkan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Kode</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Nama Customer</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Tipe</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Didaftarkan Oleh</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($customers as $customer)
                                    <tr class="hover:bg-gray-50 transition-colors"
                                        x-show="search === '' || '{{ strtolower($customer->customer_name) }}'.includes(search.toLowerCase()) || '{{ strtolower($customer->customer_code) }}'.includes(search.toLowerCase()) || '{{ strtolower($customer->type) }}'.includes(search.toLowerCase())">
                                        <td class="px-6 py-4">
                                            <span class="font-mono font-bold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg text-xs">
                                                {{ $customer->customer_code }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $customer->customer_name }}</td>
                                        <td class="px-6 py-4">
                                            @if ($customer->type === 'ekspor')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                    Ekspor
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                                    Impor
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $customer->creator?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $customer->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('customers.edit', $customer) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded-lg text-xs transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                                    onsubmit="return confirm('Hapus customer {{ addslashes($customer->customer_name) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg text-xs transition-colors">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3M4 7h16" />
                                                        </svg>
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
</x-app-layout>
