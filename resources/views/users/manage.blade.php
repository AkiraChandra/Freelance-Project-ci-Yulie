<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-slate-800 to-teal-800 rounded-2xl shadow-lg p-6 border border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Manajemen User</h1>
                        <p class="text-slate-300 mt-1">Approve, reject, dan assign role ke user</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if ($message = Session::get('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-medium">{{ $message }}</p>
            </div>
        @endif

        <!-- Pending Users Section -->
        <div class="mb-8">
            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">⏳ User Menunggu Approval ({{ $pendingUsers->count() }})</h2>
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $pendingUsers->count() }} Pending</span>
                </div>

                @if ($pendingUsers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Terdaftar</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingUsers as $key => $user)
                                    <tr class="border-b border-gray-100 hover:bg-teal-50/50">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $key + 1 }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <form action="{{ route('users.approve', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 font-semibold text-xs transition">
                                                        ✓ Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('users.reject', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 font-semibold text-xs transition">
                                                        ✗ Reject
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
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-600 font-medium">Tidak ada user yang menunggu approval</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approved Users Section -->
        <div class="mb-8">
            <div class="bg-slate-50 rounded-2xl shadow-xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">✓ User Approved ({{ $approvedUsers->count() }})</h2>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $approvedUsers->count() }} Approved</span>
                </div>

                @if ($approvedUsers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Role</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Assign Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approvedUsers as $key => $user)
                                    <tr class="border-b border-gray-100 hover:bg-teal-50/50">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $key + 1 }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($user->role_id)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800">
                                                    {{ ucfirst(str_replace('-', ' ', $user->roleModel->name)) }}
                                                </span>
                                            @else
                                                <span class="text-gray-500 italic">Belum ada role</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <form action="{{ route('users.assign-role', $user->id) }}" method="POST" class="inline-flex gap-2">
                                                    @csrf
                                                    <select name="role_id" class="px-2 py-1 border border-gray-300 rounded-lg text-xs font-medium focus:border-teal-500 focus:ring-1 focus:ring-teal-200">
                                                        <option value="">Pilih Role</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="px-3 py-1 rounded-lg bg-teal-100 text-teal-700 hover:bg-teal-200 font-semibold text-xs transition">
                                                        Assign
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
                    <div class="text-center py-8">
                        <p class="text-gray-600 font-medium">Tidak ada user yang di-approve</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
