@extends('layouts.cleanwave')

@section('content')
<!-- Hero Section -->
<section class="relative hero-pattern text-white overflow-hidden py-20 lg:py-28">
    <div class="absolute inset-0 bg-gradient-to-b from-brand-950/80 via-slate-900/90 to-slate-900 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-500/20 border border-brand-400/30 text-accent-300 text-xs sm:text-sm font-semibold backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Layanan Laundry #1 Terintegrasi & Siap Antar-Jemput</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    Pakaian Bersih, Harum & Rapi <span class="bg-gradient-to-r from-accent-400 via-sky-300 to-brand-300 bg-clip-text text-transparent">Tanpa Keluar Rumah</span>
                </h1>
                
                <p class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Serahkan cucian harian, bed cover tebal, hingga sepatu kesayangan Anda pada CleanWave. Pantau status cucian secara real-time dari jemput hingga antar!
                </p>

                <!-- Actions & Tracking Form -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('customer.orders.create') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-accent-600 text-white font-bold text-base shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-[1.02] transition duration-200 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-basket-shopping text-xl"></i>
                        <span>Pesan Laundry Sekarang</span>
                    </a>
                    <a href="{{ route('services') }}" class="w-full sm:w-auto px-6 py-4 rounded-2xl bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-semibold text-base border border-slate-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-tags"></i>
                        <span>Lihat Daftar Harga</span>
                    </a>
                </div>

                <!-- Live Track Box Directly in Hero -->
                <div class="pt-6 max-w-xl mx-auto lg:mx-0">
                    <form action="{{ route('tracking') }}" method="GET" class="p-2 rounded-2xl bg-slate-800/90 border border-slate-700/80 backdrop-blur-md shadow-2xl flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-grow">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="code" placeholder="Masukkan Kode Order (e.g. CW-20261010-0042)..." class="w-full pl-11 pr-4 py-3 bg-transparent text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-0 border-none" required>
                        </div>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-accent-500 hover:bg-accent-600 text-slate-900 font-bold text-sm transition flex items-center justify-center gap-2 flex-shrink-0">
                            <i class="fa-solid fa-location-crosshairs"></i> Lacak
                        </button>
                    </form>
                    <p class="text-xs text-slate-400 mt-2 text-center lg:text-left">
                        💡 Coba lacak demo order: <a href="{{ route('tracking.result', 'CW-20261010-0042') }}" class="text-accent-400 underline font-mono">CW-20261010-0042</a>
                    </p>
                </div>
            </div>

            <!-- Hero Feature Mockup Graphic -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    <!-- Glow effect -->
                    <div class="absolute -inset-4 bg-gradient-to-r from-brand-500 to-accent-500 rounded-3xl opacity-30 blur-2xl"></div>
                    
                    <div class="relative rounded-3xl bg-slate-800/90 border border-slate-700/80 p-6 sm:p-8 backdrop-blur-xl shadow-2xl text-slate-100">
                        <div class="flex items-center justify-between pb-6 border-b border-slate-700/60">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center">
                                    <i class="fa-solid fa-shield-halved text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-white">CleanWave Guarantee</h4>
                                    <p class="text-xs text-slate-400">Garansi Cuci Ulang 100%</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold">Terverifikasi</span>
                        </div>

                        <!-- Live Order Status Card Widget -->
                        <div class="my-6 p-4 rounded-2xl bg-slate-900/80 border border-slate-700/60 space-y-3">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-mono text-slate-400">Order #CW-20261010-0042</span>
                                <span class="px-2.5 py-0.5 rounded-full bg-brand-500/20 text-accent-300 font-bold">Sedang Diproses</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-brand-600/30 text-accent-400 flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-soap animate-spin"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white">Cuci & Spa Sepatu + Bed Cover</p>
                                    <p class="text-xs text-slate-400">Estimasi Pengantaran: Besok Sore</p>
                                </div>
                            </div>
                            <!-- Mini Progress Bar -->
                            <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-brand-500 to-accent-400 h-2 rounded-full w-3/5 transition-all duration-500"></div>
                            </div>
                        </div>

                        <!-- Highlights List -->
                        <div class="space-y-3 text-xs text-slate-300">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                <span>1 Mesin 1 Pelanggan (Higienis & Tidak Dicampur)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                <span>Pewangi Grade A dengan Aroma Tahan 7 Hari</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                <span>Kurir Antar-Jemput Ramah & Tepat Waktu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-3">
                <p class="text-3xl lg:text-4xl font-extrabold font-heading text-brand-600">15.000+</p>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Kg Pakaian Dicuci Bersih</p>
            </div>
            <div class="p-3">
                <p class="text-3xl lg:text-4xl font-extrabold font-heading text-accent-500">99.8%</p>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Tepat Waktu Selesai</p>
            </div>
            <div class="p-3">
                <p class="text-3xl lg:text-4xl font-extrabold font-heading text-amber-500">4.9 / 5.0</p>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Rating Kepuasan Pelanggan</p>
            </div>
            <div class="p-3">
                <p class="text-3xl lg:text-4xl font-extrabold font-heading text-emerald-500">100%</p>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Garansi Cuci Ulang Higienis</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-brand-600 font-bold text-xs sm:text-sm tracking-wider uppercase">Proses Mudah & Transparan</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 font-heading">
                4 Langkah Praktis Laundry Bersih
            </h2>
            <p class="text-slate-600 mt-3 text-sm sm:text-base">
                Anda hanya perlu pesan lewat smartphone, sisanya biarkan tim ahli CleanWave yang menuntaskan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Step 1 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md transition text-center group">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-brand-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mb-3">Langkah 1</span>
                <h3 class="text-lg font-bold text-slate-900">Pesan Online</h3>
                <p class="text-sm text-slate-500 mt-2">Pilih jenis layanan, jadwalkan waktu penjemputan, dan masukkan kupon diskon.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md transition text-center group">
                <div class="w-16 h-16 rounded-2xl bg-cyan-50 text-accent-500 flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-accent-500 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-truck-pickup"></i>
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mb-3">Langkah 2</span>
                <h3 class="text-lg font-bold text-slate-900">Penjemputan</h3>
                <p class="text-sm text-slate-500 mt-2">Kurir CleanWave mengambil cucian Anda langsung di lokasi dengan tas higienis.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md transition text-center group">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mb-3">Langkah 3</span>
                <h3 class="text-lg font-bold text-slate-900">Cuci & Setrika Uap</h3>
                <p class="text-sm text-slate-500 mt-2">Dicuci perorangan, pewangi premium, disetrika uap rapi, dan disegel plastik kedap.</p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md transition text-center group">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mb-3">Langkah 4</span>
                <h3 class="text-lg font-bold text-slate-900">Diantar Rapi</h3>
                <p class="text-sm text-slate-500 mt-2">Pakaian tiba dalam kondisi wangi semerbak, rapi, dan siap langsung dimasukkan lemari.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Services Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
            <div>
                <span class="text-brand-600 font-bold text-xs sm:text-sm tracking-wider uppercase">Katalog Layanan Pilihan</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 font-heading">
                    Layanan Terbaik untuk Segala Jenis Pakaian
                </h2>
            </div>
            <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-brand-600 font-bold text-sm hover:text-brand-700">
                Lihat Semua Layanan <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredServices as $service)
                <div class="rounded-3xl border border-slate-200 p-7 bg-slate-50/50 hover:bg-white hover:border-brand-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-brand-700">
                                {{ $service->category->name ?? 'Layanan' }}
                            </span>
                            <span class="text-xs text-slate-500 flex items-center gap-1 font-medium">
                                <i class="fa-regular fa-clock"></i> {{ $service->estimated_days }} Hari
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-brand-600 transition">{{ $service->name }}</h3>
                        <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $service->description }}</p>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-200/80 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-700 font-semibold block">Harga</span>
                            <span class="text-2xl font-extrabold text-brand-600">{{ $service->formatted_price }}</span>
                            <span class="text-xs text-slate-500">/ {{ $service->unit }}</span>
                        </div>
                        <a href="{{ route('customer.orders.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition flex items-center gap-2">
                            <span>Pilih</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section class="py-20 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-accent-400 font-bold text-xs sm:text-sm tracking-wider uppercase">Ulasan Asli Pelanggan</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2 font-heading">
                Apa Kata Mereka Tentang CleanWave?
            </h2>
            <div class="flex items-center justify-center gap-2 mt-4 text-amber-400">
                <div class="flex gap-1 text-lg">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <span class="text-slate-300 text-sm font-semibold ml-2">Nilai Rata-rata 4.9 dari 5.0 (900+ Pelanggan Puas)</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($reviews as $rev)
                <div class="rounded-3xl bg-slate-800/80 border border-slate-700 p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                            @for($i = 0; $i < $rev->rating; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-slate-300 text-sm italic leading-relaxed">
                            "{{ $rev->comment ?? 'Pelayanan sangat memuaskan, pakaian wangi dan bersih sekali!' }}"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-700/80">
                        <div class="w-10 h-10 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-sm">
                            {{ substr($rev->customer->user->name ?? 'User', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">{{ $rev->customer->user->name ?? 'Pelanggan Setia' }}</p>
                            <p class="text-xs text-slate-400">Order Terverifikasi</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-slate-400">Belum ada ulasan yang ditampilkan.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-16 bg-gradient-to-r from-brand-700 via-brand-600 to-accent-600 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <h2 class="text-3xl sm:text-4xl font-extrabold font-heading">
            Waktunya Ucapkan Selamat Tinggal Pada Tumpukan Cucian!
        </h2>
        <p class="text-slate-100 max-w-2xl mx-auto text-base sm:text-lg">
            Daftarkan diri Anda sekarang, nikmati kemudahan antar-jemput, tracking transparan, dan pakaian wangi higienis setiap hari.
        </p>
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('customer.orders.create') }}" class="px-8 py-4 rounded-2xl bg-white text-brand-700 font-extrabold text-base shadow-xl hover:bg-slate-100 hover:scale-105 transition">
                Mulai Pesan Sekarang
            </a>
            <a href="https://wa.me/6281234567890" target="_blank" class="px-6 py-4 rounded-2xl bg-brand-800/60 hover:bg-brand-800 border border-white/20 text-white font-bold text-base transition flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-xl text-emerald-400"></i> Hubungi via WhatsApp
            </a>
        </div>
    </div>
</section>
@endsection
