<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-slate-800 to-teal-700 rounded-2xl mb-4 shadow-lg">
            <span class="text-white font-extrabold text-xl">SS</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-1">Verifikasi Email</h2>
        <p class="text-slate-500 text-sm">Terima kasih telah mendaftar! Silakan verifikasi email Anda dengan mengklik link yang kami kirimkan. Jika tidak menerima email, kami akan dengan senang hati mengirim ulang.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            Link verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bg-gradient-to-r from-slate-800 to-teal-700 text-white font-bold py-3 px-6 rounded-xl hover:from-slate-900 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition duration-200 shadow-lg shadow-teal-900/20 text-sm tracking-wide">
                KIRIM ULANG EMAIL VERIFIKASI
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-600 hover:text-teal-700 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
