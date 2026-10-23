<x-guest-layout>
    <div class="mb-7">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-blue-700 ring-1 ring-blue-100">
            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
            Selamat datang kembali
        </span>
        <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Masuk ke CleanWave</h1>
        <p class="mt-1.5 text-sm leading-relaxed text-slate-500">
            Kelola pesanan, pantau cucian, dan nikmati poin loyalitas Anda dalam satu tempat.
        </p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8" />
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                    </svg>
                </span>
                <x-text-input id="email" class="block pl-11" type="email" name="email" :value="old('email')" placeholder="nama@email.com" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between gap-3">
                <x-input-label for="password" value="Kata Sandi" />
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 transition hover:text-blue-700">
                    Lupa kata sandi?
                </a>
                @endif
            </div>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="11" width="16" height="10" rx="2" />
                        <path d="M8 11V7a4 4 0 118 0v4" />
                    </svg>
                </span>
                <x-text-input id="password" class="block pl-11 pr-11" type="password" name="password" placeholder="Masukkan kata sandi" required autocomplete="current-password" />
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 transition hover:text-slate-600" aria-label="Tampilkan kata sandi">
                    <svg id="eyeOpen" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="eyeOff" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3l18 18" />
                        <path d="M10.584 10.587a2 2 0 002.83 2.83" />
                        <path d="M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.77 9.77 0 01-4.132 5.411M6.228 6.228A9.77 9.77 0 002.458 12c1.274 4.057 5.064 7 9.542 7 1.02 0 1.997-.153 2.91-.436" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex cursor-pointer items-center gap-2.5">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
            <span class="text-sm text-slate-600">Ingat saya di perangkat ini</span>
        </label>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Masuk
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14" />
                <path d="M13 6l6 6-6 6" />
            </svg>
        </button>
    </form>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-xs text-slate-400">atau</span>
        </div>
    </div>

    <p class="text-center text-sm text-slate-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-bold text-blue-600 transition hover:text-blue-700">Daftar sekarang</a>
    </p>

    <script>
        (function() {
            const input = document.getElementById('password');
            const button = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeOff = document.getElementById('eyeOff');

            if (!input || !button) return;

            button.addEventListener('click', function() {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                eyeOpen.classList.toggle('hidden', show);
                eyeOff.classList.toggle('hidden', !show);
                button.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            });
        })();
    </script>
</x-guest-layout>
