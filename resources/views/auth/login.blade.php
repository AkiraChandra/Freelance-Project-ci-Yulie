<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-slate-800 to-teal-700 rounded-2xl mb-4 shadow-lg">
            <span class="text-white font-extrabold text-xl">SS</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-1">Selamat Datang!</h2>
        <p class="text-slate-500 text-sm">Masuk ke sistem PT. Suryasumatera Indahsejahtera</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-red-700 font-medium text-sm">{{ $errors->first() }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-red-700 font-medium text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition duration-200 text-sm"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition duration-200 text-sm"
                placeholder="Masukkan password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-md border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                <span class="ml-2 text-sm text-slate-500">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-teal-600 hover:text-teal-800 font-semibold" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-slate-800 to-teal-700 text-white font-bold py-3.5 px-4 rounded-xl hover:from-slate-900 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition duration-200 shadow-lg shadow-teal-900/20 text-sm tracking-wide">
            MASUK
        </button>

        <!-- Sign Up Link -->
        <div class="text-center pt-2">
            <p class="text-slate-500 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-800 font-bold">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
