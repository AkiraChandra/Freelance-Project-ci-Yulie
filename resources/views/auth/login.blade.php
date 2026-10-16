<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — {{ config('app.name', 'SSIS Logistics') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .login-dots { background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.22) 1px, transparent 0); background-size: 24px 24px; }
        .login-ambient {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .login-ambient-light {
            position: absolute;
            width: 85%;
            aspect-ratio: 1;
            border-radius: 50%;
            will-change: transform, opacity;
            animation: ambient-drift 20s ease-in-out infinite;
        }
        .login-ambient-blue {
            top: -25%;
            left: -30%;
            background: radial-gradient(ellipse, rgba(56,189,248,.24), rgba(56,189,248,.08) 40%, transparent 70%);
        }
        .login-ambient-teal {
            bottom: -30%;
            right: -25%;
            background: radial-gradient(ellipse, rgba(45,212,191,.32), rgba(20,184,166,.12) 40%, transparent 70%);
            animation-name: ambient-tide;
            animation-duration: 24s;
            animation-delay: -8s;
        }
        @keyframes ambient-drift {
            0%, 100% { transform: translate3d(0,0,0) scale(1); opacity: .7; }
            35% { transform: translate3d(35%,25%,0) scale(1.25); opacity: 1; }
            70% { transform: translate3d(10%,60%,0) scale(.95); opacity: .6; }
        }
        @keyframes ambient-tide {
            0%, 100% { transform: translate3d(0,0,0) scale(1.1); opacity: .8; }
            40% { transform: translate3d(-35%,-30%,0) scale(1.35); opacity: 1; }
            75% { transform: translate3d(-5%,-60%,0) scale(.95); opacity: .65; }
        }
        .login-gleam {
            position: absolute;
            top: -60%;
            left: -60%;
            width: 45%;
            height: 220%;
            pointer-events: none;
            transform: rotate(18deg) translateX(0);
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.06) 40%, rgba(255,255,255,.28) 50%, rgba(255,255,255,.06) 60%, transparent 100%);
            mix-blend-mode: screen;
            will-change: transform, opacity;
            animation: login-gleam-sweep 7s ease-in-out infinite;
        }
        @keyframes login-gleam-sweep {
            0% { transform: rotate(18deg) translateX(0); opacity: 0; }
            8% { opacity: 1; }
            55% { transform: rotate(18deg) translateX(420%); opacity: 1; }
            70% { transform: rotate(18deg) translateX(420%); opacity: 0; }
            100% { transform: rotate(18deg) translateX(420%); opacity: 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            .login-ambient-light,
            .login-gleam { animation: none; will-change: auto; }
            .login-gleam { display: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">
    <main class="grid min-h-screen grid-cols-1 lg:grid-cols-12">
        <section class="relative flex min-h-[620px] flex-col justify-between overflow-hidden bg-gradient-to-br from-[#0c1d3f] via-[#102c54] to-[#006a61] p-6 text-white sm:p-10 lg:col-span-7 lg:min-h-screen lg:p-12">
            <div class="login-dots pointer-events-none absolute inset-0 opacity-30"></div>
            <div class="login-ambient" aria-hidden="true">
                <div class="login-ambient-light login-ambient-blue"></div>
                <div class="login-ambient-light login-ambient-teal"></div>
            </div>
            <div class="login-gleam" aria-hidden="true"></div>

            <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-teal-200">
                        <span class="material-symbols-outlined text-3xl" aria-hidden="true">anchor</span>
                    </div>
                    <div>
                        <p class="text-lg font-extrabold leading-tight tracking-tight sm:text-xl">SSIS LOGISTICS</p>
                        <p class="text-xs font-bold uppercase tracking-[.14em] text-teal-200">Enterprise Freight OS</p>
                    </div>
                </div>
                <div class="hidden items-center gap-2 rounded-full bg-white/10 px-3 py-2 text-xs font-bold sm:flex">
                    <span class="relative flex h-2.5 w-2.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-teal-200 opacity-70"></span><span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-teal-200"></span></span>
                    SYSTEM OPERATIONAL 99.98%
                </div>
            </div>

            <div class="relative z-10 my-12 max-w-3xl lg:my-8">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-teal-200/15 px-4 py-2 text-sm font-bold text-teal-200">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">anchor</span>
                    Port &amp; Vessel Integrated Platform
                </div>
                <h1 class="mb-4 text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-5xl xl:text-6xl">Suryasumatera<br>Indahsejahtera</h1>
                <p class="max-w-2xl text-base leading-relaxed text-blue-100 sm:text-lg">Sistem manajemen logistik terpadu ekspor, impor, armada kargo antarpulau, dan otomasi kepabeanan dengan kontrol finansial akurat dan transparan.</p>
                <div class="mt-8 grid max-w-3xl grid-cols-2 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/5 bg-white/10 p-4"><p class="text-3xl font-extrabold text-teal-200">99.8%</p><p class="mt-1 text-xs font-bold uppercase tracking-wide text-blue-100">On-time dispatches</p></div>
                    <div class="rounded-2xl border border-white/5 bg-white/10 p-4"><p class="text-3xl font-extrabold">450+</p><p class="mt-1 text-xs font-bold uppercase tracking-wide text-blue-100">Active TEU containers</p></div>
                    <div class="col-span-2 rounded-2xl border border-white/5 bg-white/10 p-4 sm:col-span-1"><p class="text-3xl font-extrabold text-teal-200">14 Hubs</p><p class="mt-1 text-xs font-bold uppercase tracking-wide text-blue-100">Sumatera &amp; Jawa port</p></div>
                </div>
            </div>

            <div class="relative z-10 flex flex-col justify-between gap-3 rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur sm:flex-row sm:items-center">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-300/15 text-teal-200"><span class="material-symbols-outlined" aria-hidden="true">directions_boat</span></div>
                    <div><p class="font-bold">Kapal Motor SSIS Express IV</p><p class="text-sm text-blue-100">Pelabuhan Belawan → Tanjung Priok (En Route)</p></div>
                </div>
                <span class="self-start rounded-full bg-teal-200/20 px-3 py-1 text-xs font-bold text-teal-100 sm:self-auto">ETA: 04:30 WIB</span>
            </div>
        </section>

        <section class="flex min-h-screen flex-col justify-between bg-white px-6 py-7 sm:px-10 lg:col-span-5 lg:px-12 xl:px-14">
            <header class="flex items-center justify-between gap-3 text-sm">
                <div class="flex items-center gap-2 font-semibold text-slate-600"><span class="material-symbols-outlined text-lg" aria-hidden="true">verified_user</span>SSIS Gate v3.14.0 Enterprise</div>
                <a href="mailto:admin@ssis-logistics.co.id" class="flex items-center gap-1 font-semibold text-teal-700 hover:text-teal-900"><span class="material-symbols-outlined text-lg" aria-hidden="true">contact_support</span>Bantuan</a>
            </header>

            <div class="mx-auto my-12 w-full max-w-md lg:my-auto lg:py-12"
                 x-data="{
                     showPassword: false,
                     state: 'idle',
                     errorMessage: '',
                     async submitLogin(e) {
                         if (this.state !== 'idle') return;
                         this.state = 'loading';
                         this.errorMessage = '';
                         const form = e.target;
                         try {
                             const response = await fetch(form.action, {
                                 method: 'POST',
                                 headers: {
                                     'Accept': 'application/json',
                                     'X-Requested-With': 'XMLHttpRequest',
                                     'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                                 },
                                 body: new FormData(form)
                             });
                             const data = await response.json().catch(() => ({}));
                             if (response.ok && data.success === true && typeof data.redirect === 'string') {
                                 this.state = 'success';
                                 window.location.href = data.redirect;
                             } else {
                                 this.state = 'idle';
                                 if (response.status === 419) {
                                     this.errorMessage = 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.';
                                 } else if (response.status === 422 || response.status === 429) {
                                     this.errorMessage = Object.values(data.errors || {}).flat()[0]
                                         || data.message || 'Email, kata sandi, atau peran tidak sesuai.';
                                 } else {
                                     this.errorMessage = 'Server belum dapat memproses login. Coba lagi nanti.';
                                 }
                             }
                         } catch (error) {
                             this.state = 'idle';
                             this.errorMessage = 'Koneksi ke server terputus. Periksa koneksi lalu coba lagi.';
                         }
                     }
                 }"
                 @pageshow.window="if ($event.persisted) state = 'idle'">
                <div class="mb-7">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Selamat Datang Kembali</h2>
                    <p class="mt-2 text-sm text-slate-600">Masuk ke portal operasional logistik Anda</p>
                </div>

                <x-auth-session-status class="mb-4 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-700" :status="session('status')" />
                <div x-show="errorMessage" role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700" x-text="errorMessage"></div>
                @if (session('error'))
                    <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ session('error') }}</div>
                @endif
                @if ($errors->has('email') || $errors->has('password') || $errors->has('role'))
                    <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first('email') ?: ($errors->first('password') ?: $errors->first('role')) }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5" @submit.prevent="submitLogin($event)">
                    @csrf
                    <fieldset>
                        <legend class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-600">Pilih hak akses peran</legend>
                        <div class="grid grid-cols-3 gap-1 rounded-xl bg-[#e7efff] p-1">
                            @foreach ([['owner', 'Owner'], ['staff-accounting', 'Accounting'], ['staff', 'Operasional']] as [$value, $label])
                                <label class="cursor-pointer">
                                    <input class="peer sr-only" type="radio" name="role" value="{{ $value }}" required {{ old('role', 'owner') === $value ? 'checked' : '' }}>
                                    <span class="flex min-h-10 items-center justify-center rounded-lg px-2 text-center text-xs font-bold text-slate-600 transition peer-checked:bg-white peer-checked:text-slate-900 peer-checked:shadow-sm peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-teal-600 sm:text-sm">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </fieldset>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-800">Email Perusahaan</label>
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">mail</span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@ssis-logistics.co.id" class="w-full rounded-xl border border-transparent bg-[#eff4ff] py-3 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-500 focus:border-teal-600 focus:bg-white focus:ring-2 focus:ring-teal-600/20">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="password" class="text-sm font-semibold text-slate-800">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-teal-700 hover:underline">Lupa Password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">lock</span>
                            <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required autocomplete="current-password" placeholder="Masukkan kata sandi akun" class="w-full rounded-xl border border-transparent bg-[#eff4ff] py-3 pl-11 pr-12 text-sm text-slate-900 placeholder:text-slate-500 focus:border-teal-600 focus:bg-white focus:ring-2 focus:ring-teal-600/20">
                            <button type="button" @click="showPassword = !showPassword" x-bind:aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'" class="absolute right-3 top-1/2 -translate-y-1/2 rounded p-1 text-slate-500 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-600">
                                <span class="material-symbols-outlined" x-text="showPassword ? 'visibility' : 'visibility_off'" aria-hidden="true">visibility_off</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <label for="remember_me" class="flex cursor-pointer items-center gap-2.5 pt-1 text-sm text-slate-600">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-teal-700 focus:ring-teal-600" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya di perangkat ini
                    </label>

                    <button type="submit"
                            :disabled="state !== 'idle'"
                            :class="state === 'success' ? '!bg-emerald-600 hover:!bg-emerald-600' : 'bg-gradient-to-r from-[#0c1d3f] via-[#0c1d3f] to-[#006a61]'"
                            class="group mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#0c1d3f] via-[#0c1d3f] to-[#006a61] px-6 py-3.5 text-base font-bold tracking-wide text-white shadow-md transition-all hover:shadow-xl hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 active:scale-[0.99] disabled:cursor-wait">
                        <span x-show="state === 'idle'" class="inline-flex items-center justify-center gap-2">
                            MASUK KE SISTEM <span class="material-symbols-outlined text-xl transition-transform group-hover:translate-x-1" aria-hidden="true">arrow_forward</span>
                        </span>
                        <span x-cloak x-show="state === 'loading'" class="inline-flex items-center justify-center gap-2">
                            <span class="animate-spin text-lg" aria-hidden="true">⟳</span> MEMVERIFIKASI...
                        </span>
                        <span x-cloak x-show="state === 'success'" class="inline-flex items-center justify-center gap-2 text-white">
                            <span class="material-symbols-outlined text-xl" aria-hidden="true">check_circle</span> AKSES DISETUJUI
                        </span>
                    </button>
                </form>
            </div>

            <footer class="border-t border-slate-100 pt-5 text-center text-sm text-slate-600">
                <p>Butuh akses akun baru? Hubungi Administrator atau
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="font-semibold text-slate-900 hover:text-teal-700">Daftar Akun Staff</a>
                    @else
                        <span class="font-semibold text-slate-900">Daftar Akun Staff</span>
                    @endif
                </p>
                <p class="mt-2 text-xs font-semibold text-slate-500">© {{ date('Y') }} PT. Suryasumatera Indahsejahtera Logistics. Hak Cipta Dilindungi.</p>
            </footer>
        </section>
    </main>
</body>
</html>
