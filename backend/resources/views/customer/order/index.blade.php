@extends('layouts.cleanwave')

@section('content')
<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Header & New Order Action -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">Riwayat Pesanan Saya</h1>
                <p class="text-slate-500 text-sm mt-1">Daftar semua transaksi cucian dan progres pengerjaan.</p>
            </div>
            <a href="{{ route('customer.orders.create') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm shadow-md hover:shadow-lg transition flex items-center gap-2">
                <i class="fa-solid fa-plus-circle"></i> Buat Pesanan Baru
            </a>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $ord)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md transition duration-200">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl flex-shrink-0">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-slate-900 font-mono text-base">{{ $ord->order_code }}</span>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-700">
                                            {{ strtoupper($ord->pickup_method) }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-400 mt-0.5 block">
                                        {{ $ord->created_at->format('d M Y, H:i') }} WIB
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-{{ $ord->status_color }}-100 text-{{ $ord->status_color }}-700 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-{{ $ord->status_color }}-500 animate-pulse"></span>
                                    {{ $ord->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Item Snippet & Total -->
                        <div class="py-4 flex flex-col md:flex-row justify-between md:items-center gap-4 text-xs text-slate-600">
                            <div>
                                <span class="font-semibold text-slate-800">Layanan:</span>
                                <span>
                                    @foreach($ord->items as $it)
                                        {{ $it->service->name ?? 'Item' }} ({{ $it->quantity }} {{ $it->service->unit ?? '' }}){{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </span>
                            </div>

                            <div class="text-left md:text-right">
                                <span class="text-slate-400 block text-[11px]">Total Tagihan</span>
                                <span class="text-lg font-extrabold text-brand-600">{{ $ord->formatted_total }}</span>
                                <span class="text-[11px] block text-slate-500">
                                    ({{ $ord->payment ? $ord->payment->status_label : 'Belum Bayar' }})
                                </span>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('tracking.result', $ord->order_code) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-crosshairs text-brand-600"></i> Lacak Progres
                                </a>
                                <a href="{{ route('customer.orders.show', $ord->id) }}" class="px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-brand-700 font-bold text-xs transition">
                                    Rincian Lengkap
                                </a>
                            </div>

                            @if($ord->canBeCancelled())
                                <form action="{{ route('customer.orders.cancel', $ord->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-blue-50 text-brand-600 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Belum Ada Riwayat Pesanan</h3>
                <p class="text-slate-500 text-sm max-w-sm mx-auto mt-1 mb-6">
                    Cucian menumpuk? Yuk buat pesanan pertama Anda sekarang dan dapatkan diskon 10%!
                </p>
                <a href="{{ route('customer.orders.create') }}" class="px-6 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Pesan Laundry Sekarang
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
