<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-slate-800 to-teal-700 rounded-2xl mb-4 shadow-lg">
            <span class="text-white font-extrabold text-xl">SS</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-1">Lupa Password?</h2>
        <p class="text-slate-500 text-sm">Masukkan email Anda dan kami akan mengirim link reset password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition duration-200 text-sm"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-slate-800 to-teal-700 text-white font-bold py-3.5 px-4 rounded-xl hover:from-slate-900 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition duration-200 shadow-lg shadow-teal-900/20 text-sm tracking-wide">
            KIRIM LINK RESET
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-800 font-bold text-sm">← Kembali ke Login</a>
        </div>
    </form>
</x-guest-layout>
