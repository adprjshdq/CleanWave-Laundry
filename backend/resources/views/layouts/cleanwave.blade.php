<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CleanWave — Sistem Laundry Online Cepat & Terpercaya' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN Fallback + Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
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

        h1,
        h2,
        h3,
        h4,
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .hero-pattern {
            background-color: #0f172a;
            background-image: radial-gradient(rgba(37, 99, 235, 0.25) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-brand-700 via-brand-600 to-accent-600 text-white text-xs sm:text-sm py-2 px-4 text-center font-medium shadow-inner">
        <span>✨ <strong>Promo Terbatas:</strong> Gunakan kode kupon <span class="bg-white/20 px-2 py-0.5 rounded font-mono font-bold tracking-wider">CLEANWAVE10</span> untuk diskon 10% cucian pertama Anda!</span>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 glass-header border-b border-slate-200/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-accent-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/25 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-water text-2xl"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-extrabold font-heading text-slate-900 tracking-tight flex items-center gap-1">
                            Clean<span class="text-brand-600">Wave</span>
                        </span>
                        <p class="text-[10px] uppercase tracking-widest text-slate-700 font-bold -mt-1">Laundry Service</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors {{ request()->routeIs('home') ? 'text-brand-600 font-bold' : '' }}">Beranda</a>
                    <a href="{{ route('services') }}" class="hover:text-brand-600 transition-colors {{ request()->routeIs('services') ? 'text-brand-600 font-bold' : '' }}">Daftar Layanan</a>
                    <a href="{{ route('tracking') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1.5 {{ request()->routeIs('tracking*') ? 'text-brand-600 font-bold' : '' }}">
                        <i class="fa-solid fa-magnifying-glass-location text-accent-500"></i> Lacak Pesanan
                    </a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-brand-50 text-brand-700 hover:bg-brand-100 font-semibold text-sm transition flex items-center gap-2 border border-brand-200">
                        <i class="fa-solid fa-gauge-high"></i> Panel Admin
                    </a>
                    @else
                    <a href="{{ route('customer.orders.index') }}" class="px-4 py-2.5 rounded-xl bg-blue-50 text-brand-700 hover:bg-blue-100 font-semibold text-sm transition flex items-center gap-2 border border-blue-200">
                        <i class="fa-solid fa-receipt"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('customer.orders.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-semibold text-sm shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/40 hover:-translate-y-0.5 transition duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i> Buat Pesanan
                    </a>
                    @endif

                    @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                    <!-- Notifications -->
                    <div class="relative group">
                        <a href="{{ route('notifications.index') }}" class="relative flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-brand-600 hover:border-brand-200 transition" title="Notifikasi">
                            <i class="fa-regular fa-bell text-base"></i>
                            @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                            @endif
                        </a>
                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden group-hover:block transition duration-200">
                            <div class="px-4 py-2 border-b border-slate-100 flex justify-between items-center">
                                <p class="text-xs font-bold text-slate-900">Notifikasi Terbaru</p>
                                <a href="{{ route('notifications.index') }}" class="text-[10px] font-bold text-brand-600 hover:underline">Lihat semua</a>
                            </div>
                            @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notif)
                            <div class="px-4 py-2.5 border-b border-slate-50 last:border-0 {{ $notif->is_read ? '' : 'bg-blue-50/40' }}">
                                <div class="flex items-start gap-2">
                                    @if(!$notif->is_read)
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-600 mt-1.5 flex-shrink-0"></span>
                                    @endif
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-900 leading-snug">{{ $notif->title }}</p>
                                        <p class="text-[10px] text-slate-500 mt-0.5 leading-snug">{{ \Illuminate\Support\Str::limit($notif->message, 70) }}</p>
                                        <span class="text-[9px] text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="px-4 py-6 text-center text-xs text-slate-400">Belum ada notifikasi.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full border border-slate-200 hover:border-slate-300 bg-white">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                            <span class="text-xs font-semibold text-slate-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden group-hover:block transition duration-200">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->isCustomer() && auth()->user()->customer)
                                <p class="text-[10px] text-amber-600 font-bold mt-1">
                                    <i class="fa-solid fa-crown mr-1"></i>{{ auth()->user()->customer->membership_label }}
                                    &middot; {{ number_format(auth()->user()->customer->loyalty_points, 0, ',', '.') }} poin
                                </p>
                                @endif
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                <i class="fa-regular fa-user mr-2 text-slate-400"></i> Edit Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-xl text-slate-700 hover:text-brand-600 font-semibold text-sm transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-semibold text-sm shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/40 hover:-translate-y-0.5 transition duration-200">
                        Daftar Sekarang
                    </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button id="mobileMenuBtn" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-5 space-y-3 shadow-lg">
            <a href="{{ route('home') }}" class="block text-slate-700 font-semibold text-sm py-2">Beranda</a>
            <a href="{{ route('services') }}" class="block text-slate-700 font-semibold text-sm py-2">Daftar Layanan</a>
            <a href="{{ route('tracking') }}" class="block text-slate-700 font-semibold text-sm py-2">Lacak Pesanan</a>
            <hr class="border-slate-100">
            @auth
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="block text-brand-600 font-bold text-sm py-2">Panel Admin</a>
            @else
            <a href="{{ route('customer.orders.index') }}" class="block text-brand-600 font-semibold text-sm py-2">Pesanan Saya</a>
            <a href="{{ route('customer.orders.create') }}" class="block bg-brand-600 text-white text-center py-2.5 rounded-xl font-bold text-sm">Buat Pesanan Baru</a>
            @endif
            <a href="{{ route('notifications.index') }}" class="flex items-center justify-between text-slate-700 font-semibold text-sm py-2">
                <span><i class="fa-regular fa-bell mr-2 text-brand-600"></i> Notifikasi</span>
                @if($unreadCount > 0)
                <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-left text-rose-600 font-semibold text-sm py-2">Keluar</button>
            </form>
            @else
            <div class="grid grid-cols-2 gap-2 pt-2">
                <a href="{{ route('login') }}" class="text-center py-2.5 border border-slate-300 rounded-xl text-slate-700 font-semibold text-sm">Masuk</a>
                <a href="{{ route('register') }}" class="text-center py-2.5 bg-brand-600 text-white rounded-xl font-semibold text-sm">Daftar</a>
            </div>
            @endauth
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-check text-sm"></i>
                </div>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>
    @endif

    @if(session('error') || $errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div class="text-sm">
                    @if(session('error'))
                    <p class="font-medium">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                    <ul class="list-disc list-inside text-xs mt-1">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-water text-xl"></i>
                        </div>
                        <span class="text-2xl font-extrabold font-heading text-white tracking-tight">Clean<span class="text-accent-400">Wave</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Layanan laundry online modern terpadu. Dari penjemputan, pencucian higienis dengan detergen ramah serat, hingga pengantaran rapi langsung ke pintu Anda.
                    </p>
                    <div class="flex items-center gap-3 pt-2 text-slate-400">
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-brand-600 hover:text-white transition"><i class="fa-brands fa-whatsapp text-lg"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-brand-600 hover:text-white transition"><i class="fa-brands fa-instagram text-lg"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-brand-600 hover:text-white transition"><i class="fa-brands fa-facebook text-lg"></i></a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Navigasi</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('services') }}" class="hover:text-white transition">Daftar Layanan</a></li>
                        <li><a href="{{ route('tracking') }}" class="hover:text-white transition">Lacak Pesanan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar Pengguna Baru</a></li>
                    </ul>
                </div>

                <!-- Service Hours -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Jam Operasional</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex justify-between"><span>Senin - Jumat:</span> <span class="text-white font-semibold">07:00 - 21:00</span></li>
                        <li class="flex justify-between"><span>Sabtu - Minggu:</span> <span class="text-white font-semibold">08:00 - 20:00</span></li>
                        <li class="flex justify-between"><span>Layanan Antar:</span> <span class="text-white font-semibold">Setiap Hari</span></li>
                        <li class="pt-2 text-emerald-400 flex items-center gap-2"><i class="fa-solid fa-circle text-[8px] animate-pulse"></i> Outlet Sedang Buka</li>
                    </ul>
                </div>

                <!-- Contact & Location -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Kontak Kami</h3>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-brand-400 mt-1"></i>
                            <span>Jl. Boulevard Raya Blok A4 No. 12, Kelapa Gading, Jakarta Utara</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-brand-400"></i>
                            <span>(021) 4587-9921 / 0812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-brand-400"></i>
                            <span>halo@cleanwavelaundry.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400">
                <p>© {{ date('Y') }} CleanWave Laundry Inc. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="mt-2 sm:mt-0 flex items-center gap-1">Dirancang untuk Portfolio Full-Stack Engineering 🚀</p>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
    @stack('scripts')
</body>

</html>