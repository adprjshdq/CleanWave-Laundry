<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel — CleanWave Laundry' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        admin: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                        accent: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-heading {
            font-family: 'Outfit', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white text-slate-600 flex-shrink-0 min-h-screen hidden md:flex flex-col justify-between border-r border-slate-200">
        <div>
            <!-- Logo Header -->
            <div class="h-20 flex items-center px-6 border-b border-slate-200 gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white">
                    <i class="fa-solid fa-water text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold font-heading text-slate-900 tracking-tight">Clean<span class="text-brand-600">Wave</span></span>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-slate-500 block -mt-1">ADMINISTRATOR</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'hover:bg-slate-100 text-slate-600' }}">
                    <i class="fa-solid fa-gauge text-base w-5 text-center"></i>
                    <span>Dashboard Utama</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.orders*') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'hover:bg-slate-100 text-slate-600' }}">
                    <i class="fa-solid fa-clipboard-list text-base w-5 text-center"></i>
                    <span>Kelola Pesanan</span>
                </a>

                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.services*') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'hover:bg-slate-100 text-slate-600' }}">
                    <i class="fa-solid fa-tags text-base w-5 text-center"></i>
                    <span>Layanan & Tarif</span>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.payments*') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'hover:bg-slate-100 text-slate-600' }}">
                    <i class="fa-solid fa-receipt text-base w-5 text-center"></i>
                    <span>Verifikasi Pembayaran</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.reports*') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'hover:bg-slate-100 text-slate-600' }}">
                    <i class="fa-solid fa-chart-line text-base w-5 text-center"></i>
                    <span>Laporan & Export</span>
                </a>

                <div class="pt-4 pb-2 px-4 text-[10px] uppercase font-bold text-slate-500 tracking-wider">Navigasi Publik</div>

                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl hover:bg-slate-100 text-slate-500 hover:text-slate-900 transition">
                    <i class="fa-solid fa-globe text-base w-5 text-center"></i>
                    <span>Buka Website Publik</span>
                </a>
            </nav>
        </div>

        <!-- Admin Profile Bottom -->
        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                    <div>
                        <p class="text-xs font-bold text-slate-900 truncate max-w-[110px]">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500">Super Admin</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-rose-500 hover:text-rose-600 rounded-lg" title="Keluar">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-h-screen overflow-x-hidden">

        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-extrabold text-slate-900 font-heading">
                    @yield('title', 'Admin Dashboard')
                </h1>
            </div>

            <div class="flex items-center gap-4">
                @php $adminUnreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                <a href="{{ route('notifications.index') }}" class="relative flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-brand-600 hover:border-brand-200 transition" title="Notifikasi">
                    <i class="fa-regular fa-bell text-base"></i>
                    @if($adminUnreadCount > 0)
                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center">
                        {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
                    </span>
                    @endif
                </a>
                <span class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif
                </span>
                <a href="{{ route('home') }}" class="text-xs font-semibold text-brand-600 hover:underline">
                    Lihat Web <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="px-6 sm:px-8 pt-4">
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error') || $errors->any())
        <div class="px-6 sm:px-8 pt-4">
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
                <div>
                    @if(session('error')) <p>{{ session('error') }}</p> @endif
                    @if($errors->any())
                    <ul class="list-disc list-inside mt-0.5">
                        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Body Container -->
        <main class="p-6 sm:p-8 flex-grow">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>