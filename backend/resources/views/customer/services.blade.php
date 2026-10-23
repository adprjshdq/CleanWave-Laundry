@extends('layouts.cleanwave')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-brand-600 font-bold text-xs sm:text-sm tracking-wider uppercase">Daftar Lengkap Harga & Layanan</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mt-2 font-heading">
                Semua Kebutuhan Laundry Anda Ada di Sini
            </h1>
            <p class="text-slate-600 mt-3 text-sm sm:text-base">
                Pilih paket sesuai kebutuhan Anda. Transparan tanpa biaya tersembunyi, dengan standar higienis terbaik.
            </p>
        </div>

        <!-- Categories Loop -->
        @foreach($categories as $category)
        <div class="mb-14">
            <div class="flex items-center gap-3 mb-6 pb-3 border-b border-slate-200">
                <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center text-lg">
                    <i class="fa-solid {{ $category->icon ?? 'fa-basket-shopping' }}"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $category->name }}</h2>
                    <p class="text-xs text-slate-500">{{ $category->description }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($category->activeServices as $service)
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-brand-300 transition flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-lg text-slate-900">{{ $service->name }}</h3>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i> {{ $service->estimated_days }} Hari
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">
                            {{ $service->description ?? 'Pencucian profesional dengan detergen hypoallergenic ramah pakaian.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-brand-600">{{ $service->formatted_price }}</span>
                            <span class="text-xs text-slate-500">/ {{ $service->unit }}</span>
                        </div>
                        <a href="{{ route('customer.orders.create') }}" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-plus text-[10px]"></i> Pesan
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Promo Banner -->
        <div class="rounded-3xl bg-gradient-to-r from-brand-600 to-accent-600 p-8 sm:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
            <div>
                <span class="px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider">Kupon Pengguna Baru</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold font-heading mt-2">Dapatkan Diskon 10% dengan Kode: CLEANWAVE10</h3>
                <p class="text-slate-100 text-sm mt-1">Berlaku untuk semua jenis layanan kiloan maupun satuan tanpa batas berat.</p>
            </div>
            <a href="{{ route('customer.orders.create') }}" class="px-6 py-3.5 rounded-2xl bg-white text-brand-700 font-extrabold text-sm shadow-md hover:bg-slate-100 transition flex-shrink-0">
                Gunakan Kupon Sekarang
            </a>
        </div>

    </div>
</div>
@endsection