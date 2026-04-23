<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Buat Order Baru</h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('orders.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow hover:shadow-md transition text-gray-700 font-semibold mb-8">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Impor -->
                <a href="{{ route('import-orders.create') }}"
                    class="group bg-white rounded-2xl shadow-sm border-2 border-gray-200 hover:border-blue-500 hover:shadow-md transition-all p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-blue-100 group-hover:bg-blue-200 rounded-2xl flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Order Impor</h3>
                    <p class="text-gray-500 text-sm">Buat order untuk pengiriman dari luar negeri ke dalam negeri</p>
                </a>

                <!-- Ekspor -->
                <a href="{{ route('export-orders.create') }}"
                    class="group bg-white rounded-2xl shadow-sm border-2 border-gray-200 hover:border-orange-500 hover:shadow-md transition-all p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-orange-100 group-hover:bg-orange-200 rounded-2xl flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Order Ekspor</h3>
                    <p class="text-gray-500 text-sm">Buat order untuk pengiriman dari dalam negeri ke luar negeri</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
