<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CleanWave') }} — Sistem Laundry Online</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-900 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-2">

        <!-- Brand Panel -->
        <aside class="relative hidden overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-sky-300/20 blur-3xl"></div>

            <a href="{{ url('/') }}" class="relative z-10 inline-flex items-center gap-3">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/25 backdrop-blur">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 8c2.2 0 2.2 2 4.4 2S9.6 8 11.8 8s2.2 2 4.4 2 2.2-2 4.4-2" />
                        <path d="M3 14c2.2 0 2.2 2 4.4 2s2.2-2 4.4-2 2.2 2 4.4 2 2.2-2 4.4-2" />
                        <path d="M3 20c2.2 0 2.2 2 4.4 2s2.2-2 4.4-2 2.2 2 4.4 2 2.2-2 4.4-2" />
                    </svg>
                </span>
                <span class="text-2xl font-extrabold tracking-tight">CleanWave</span>
            </a>

            <div class="relative z-10 max-w-md">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                    Laundry online terpercaya
                </span>

                <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight">
                    Cucian bersih,<br>hidup lebih ringan.
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-blue-50/90">
                    Serahkan urusan cucian Anda kepada CleanWave. Antar-jemput praktis, proses higienis, dan pantau setiap tahapnya langsung dari dashboard.
                </p>

                <ul class="mt-8 space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 flex-none items-center justify-center rounded-full bg-white/10">
                            <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold">Antar-jemput praktis</p>
                            <p class="text-xs text-blue-100/80">Kurir menjemput dan mengantar cucian Anda.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 flex-none items-center justify-center rounded-full bg-white/10">
                            <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold">Tracking real-time</p>
                            <p class="text-xs text-blue-100/80">Pantau status pesanan kapan saja.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 flex-none items-center justify-center rounded-full bg-white/10">
                            <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold">Poin loyalitas</p>
                            <p class="text-xs text-blue-100/80">Kumpulkan poin dan tukarkan dengan diskon.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <p class="relative z-10 text-xs text-blue-100/70">
                &copy; {{ date('Y') }} CleanWave. Semua hak dilindungi.
            </p>
        </aside>

        <!-- Form Panel -->
        <main class="flex min-h-screen flex-col justify-center bg-slate-50 px-4 py-10 sm:px-8">
            <div class="mx-auto w-full max-w-md">

                <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-2.5 lg:hidden">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/30">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 8c2.2 0 2.2 2 4.4 2S9.6 8 11.8 8s2.2 2 4.4 2 2.2-2 4.4-2" />
                            <path d="M3 14c2.2 0 2.2 2 4.4 2s2.2-2 4.4-2 2.2 2 4.4 2 2.2-2 4.4-2" />
                            <path d="M3 20c2.2 0 2.2 2 4.4 2s2.2-2 4.4-2 2.2 2 4.4 2 2.2-2 4.4-2" />
                        </svg>
                    </span>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900">Clean<span class="text-blue-600">Wave</span></span>
                </a>

                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xl shadow-slate-200/60 sm:p-9">
                    {{ $slot }}
                </div>

                <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-400">
                    <a href="{{ url('/') }}" class="font-semibold text-slate-500 transition hover:text-blue-600">Beranda</a>
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <a href="{{ url('/layanan') }}" class="font-semibold text-slate-500 transition hover:text-blue-600">Layanan</a>
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <a href="{{ url('/lacak-pesanan') }}" class="font-semibold text-slate-500 transition hover:text-blue-600">Lacak Pesanan</a>
                </div>

            </div>
        </main>

    </div>
</body>

</html>
