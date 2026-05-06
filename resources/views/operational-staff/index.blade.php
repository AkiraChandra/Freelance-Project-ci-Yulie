<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            👷 Manajemen Staff Operasional
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ search: '', showForm: false, editId: null, editName: '', editContact: '', editStatus: '' }">

            {{-- Alert Messages --}}
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

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-8 bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 border border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Daftar Staff Operasional</h1>
                    <p class="text-slate-300">Kelola data pekerja lapangan (orang lapangan)</p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari staff..."
                            class="pl-9 pr-4 py-2.5 border border-white/20 rounded-lg bg-white/10 text-white placeholder-slate-400 text-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all w-full sm:w-52">
                    </div>
                    <button @click="showForm = !showForm; editId = null"
                        class="inline-flex items-center px-6 py-3 bg-white/15 hover:bg-white/25 text-white font-bold rounded-lg border border-white/20 backdrop-blur-sm transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Staff Baru
                    </button>
                </div>
                </div>
            </div>

            {{-- Add / Edit Form --}}
            <div x-show="showForm" x-transition class="mb-8 bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4" x-text="editId ? '✏️ Edit Staff Operasional' : '➕ Tambah Staff Operasional Baru'"></h3>

                {{-- Add Form --}}
                <form x-show="!editId" method="POST" action="{{ route('operational-staff.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Staff <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Kontak <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <input type="text" name="contact" value="{{ old('contact') }}" placeholder="Contoh: 08123456789"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors">
                            Simpan
                        </button>
                        <button type="button" @click="showForm = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                            Batal
                        </button>
                    </div>
                </form>

                {{-- Edit Forms (one per staff, shown by Alpine) --}}
                @foreach ($staffs as $staff)
                    <form x-show="editId === {{ $staff->id }}" method="POST"
                        action="{{ route('operational-staff.update', $staff) }}">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Staff <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="editName" placeholder="Nama lengkap"
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Kontak <span class="text-gray-400 font-normal">(opsional)</span></label>
                                <input type="text" name="contact" x-model="editContact" placeholder="Contoh: 08123456789"
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" x-model="editStatus" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-900 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                                Perbarui
                            </button>
                            <button type="button" @click="showForm = false; editId = null" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>

            {{-- Staff Table --}}
            <div class="bg-slate-50 rounded-2xl shadow-xl overflow-hidden border border-slate-200">
                @if ($staffs->count() > 0)
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-200">
                        <h3 class="text-lg font-bold text-gray-900">{{ $staffs->count() }} Staff Terdaftar</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-slate-200">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Staff</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nomor Kontak</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total Penugasan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($staffs as $staff)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150"
                                        x-show="search === '' || '{{ strtolower($staff->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($staff->contact ?? '') }}'.includes(search.toLowerCase())">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-11 h-11 bg-gradient-to-br from-slate-700 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md">
                                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $staff->name }}</p>
                                                    <p class="text-xs text-gray-500">#{{ str_pad($staff->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700">
                                            @if ($staff->contact)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $staff->contact) }}" target="_blank"
                                                    class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                    {{ $staff->contact }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-sm italic">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block bg-teal-100 text-teal-800 text-sm font-semibold px-3 py-1 rounded-full">
                                                {{ $staff->assignments()->count() }} Order
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($staff->status === 'active')
                                                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full">
                                                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-1.5"></span>Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2">
                                                <button
                                                    @click="showForm = true; editId = {{ $staff->id }}; editName = '{{ addslashes($staff->name) }}'; editContact = '{{ addslashes($staff->contact ?? '') }}'; editStatus = '{{ $staff->status }}'"
                                                    class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-semibold rounded-lg transition-colors">
                                                    ✏️ Edit
                                                </button>

                                                <form method="POST" action="{{ route('operational-staff.destroy', $staff) }}"
                                                    onsubmit="return confirm('Yakin hapus staff {{ addslashes($staff->name) }}? Semua penugasannya juga akan terhapus.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition-colors">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-16 text-center text-gray-400">
                        <div class="text-5xl mb-4">👷</div>
                        <p class="text-lg font-semibold text-gray-500">Belum ada staff operasional</p>
                        <p class="text-sm mt-1">Klik "Tambah Staff Baru" untuk menambahkan data pekerja lapangan</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
