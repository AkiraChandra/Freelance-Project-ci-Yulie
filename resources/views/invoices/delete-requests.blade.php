<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Request Hapus Invoice</h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Request Hapus Invoice Pending</h3>

                @if($requests->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada request</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada request hapus invoice yang pending.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($requests as $request)
                            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                Pending
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $request->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-1">
                                            Invoice {{ $request->invoice->nota_number ?? '-' }}
                                        </h4>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Request oleh: <strong>{{ $request->requester->name ?? '-' }}</strong>
                                        </p>
                                        @if($request->review_notes)
                                            <p class="text-sm text-gray-500 italic mb-3">
                                                Alasan: {{ $request->review_notes }}
                                            </p>
                                        @endif

                                        {{-- Invoice Details --}}
                                        @if($request->invoice)
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm bg-gray-50 rounded-lg p-3 mt-3">
                                                <div>
                                                    <p class="text-gray-500">Tipe</p>
                                                    <p class="font-semibold">{{ $request->invoice->order_type === 'import' ? 'Import' : 'Export' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Customer</p>
                                                    <p class="font-semibold">{{ $request->invoice->order->customer->customer_name ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Total</p>
                                                    <p class="font-semibold">Rp {{ number_format($request->invoice->grand_total ?? 0, 0, ',', '.') }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Dibuat</p>
                                                    <p class="font-semibold">{{ $request->invoice->created_at->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col gap-2 ml-4">
                                        <form action="{{ route('invoices.delete-requests.approve', $request) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menyetujui penghapusan invoice ini?')">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                                ✓ Setuju
                                            </button>
                                        </form>

                                        <button type="button"
                                            onclick="showRejectModal({{ $request->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                            ✕ Tolak
                                        </button>
                                    </div>
                                </div>

                                {{-- Reject Form (Hidden) --}}
                                <div id="reject-form-{{ $request->id }}" class="hidden mt-4 pt-4 border-t">
                                    <form action="{{ route('invoices.delete-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                                            <textarea name="reason" rows="2" required
                                                class="w-full border-2 border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-red-500 focus:ring-2 focus:ring-red-200"
                                                placeholder="Masukkan alasan penolakan..."></textarea>
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                                Kirim Penolakan
                                            </button>
                                            <button type="button" onclick="hideRejectModal({{ $request->id }})"
                                                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal(id) {
            document.getElementById('reject-form-' + id).classList.remove('hidden');
        }

        function hideRejectModal(id) {
            document.getElementById('reject-form-' + id).classList.add('hidden');
        }
    </script>
</x-app-layout>
