@extends('layouts.cleanwave')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header & Search Box -->
        <div class="text-center mb-8">
            <span class="text-brand-600 font-bold text-xs tracking-wider uppercase">Live Status Tracking</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1 font-heading">
                Lacak Status Cucian Real-Time
            </h1>
            <p class="text-slate-600 text-sm mt-2">
                Masukkan kode pesanan Anda untuk mengetahui progres pencucian hingga pengantaran.
            </p>

            <form action="{{ route('tracking') }}" method="GET" class="mt-6 max-w-lg mx-auto flex gap-2">
                <div class="relative flex-grow">
                    <i class="fa-solid fa-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="code" value="{{ request('code', $order->order_code ?? '') }}" placeholder="Contoh: CW-20261010-0042..." class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" required>
                </div>
                <button type="submit" class="px-6 py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Lacak
                </button>
            </form>
        </div>

        @if(isset($order))
        <!-- Tracking Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden animate-fade-in">

            <!-- Order Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase tracking-wider text-accent-400 font-bold">Kode Pesanan</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/10 text-white">
                            {{ strtoupper($order->pickup_method) }}
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold font-mono tracking-tight text-white mt-1">
                        {{ $order->order_code }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>

                <div class="text-right sm:text-right">
                    <span class="text-xs text-slate-400 block">Status Saat Ini</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-accent-300 border border-brand-500/30 mt-1">
                        <span class="w-2 h-2 rounded-full bg-accent-400 animate-pulse"></span>
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <!-- Visual Step Timeline Progress Bar -->
            <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">Tahapan Proses Pengerjaan</h3>

                @php
                $steps = [
                ['key' => 'pending', 'label' => 'Pesanan Diterima', 'icon' => 'fa-clipboard-list'],
                ['key' => 'confirmed', 'label' => 'Dikonfirmasi', 'icon' => 'fa-check-circle'],
                ['key' => 'picked_up', 'label' => 'Dijemput Kurir', 'icon' => 'fa-truck-pickup'],
                ['key' => 'processing', 'label' => 'Dicuci & Disetrika', 'icon' => 'fa-soap'],
                ['key' => 'ready', 'label' => 'Siap Kirim', 'icon' => 'fa-box'],
                ['key' => 'on_delivery', 'label' => 'Dalam Pengantaran', 'icon' => 'fa-motorcycle'],
                ['key' => 'completed', 'label' => 'Selesai', 'icon' => 'fa-circle-check'],
                ];

                $currentIndex = 0;
                foreach ($steps as $i => $s) {
                if ($s['key'] === $order->status) {
                $currentIndex = $i;
                break;
                }
                }
                if ($order->status === 'delivered') $currentIndex = 6;
                @endphp

                <div class="relative">
                    <!-- Connecting Line -->
                    <div class="hidden md:block absolute top-1/2 left-6 right-6 -translate-y-1/2 h-1 bg-slate-200 -z-0">
                        <div class="h-1 bg-brand-600 transition-all duration-500" style="width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%"></div>
                    </div>

                    <!-- Step Items Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-7 gap-4 relative z-10">
                        @foreach($steps as $i => $step)
                        @php
                        $isPassed = $i <= $currentIndex;
                            $isActive=$i===$currentIndex;
                            @endphp
                            <div class="text-center flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg font-bold transition-all duration-300 {{ $isActive ? 'bg-brand-600 text-white ring-4 ring-brand-200 scale-110 shadow-lg shadow-brand-500/30' : ($isPassed ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400') }}">
                                <i class="fa-solid {{ $step['icon'] }}"></i>
                            </div>
                            <span class="text-xs font-bold mt-2.5 {{ $isActive ? 'text-brand-600' : ($isPassed ? 'text-slate-800' : 'text-slate-400') }}">
                                {{ $step['label'] }}
                            </span>
                            @if($isActive)
                            <span class="text-[10px] font-bold text-accent-500 uppercase tracking-wider mt-0.5 animate-pulse">Sedang Berjalan</span>
                            @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Detail Information -->
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">

            <!-- Left: Items & Notes -->
            <div class="space-y-4">
                <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-brand-600"></i> Rincian Cucian ({{ count($order->items) }} Item)
                </h4>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $item->service->name ?? 'Layanan' }}</p>
                            <p class="text-xs text-slate-500">{{ $item->quantity }} {{ $item->service->unit ?? 'kg' }} × Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                @if($order->notes)
                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">
                    <strong>Catatan Khusus:</strong> {{ $order->notes }}
                </div>
                @endif
            </div>

            <!-- Right: Logistics & Payment -->
            <div class="space-y-4">
                <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-truck-fast text-brand-600"></i> Detail Pengantaran & Pembayaran
                </h4>

                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Metode:</span>
                        <span class="font-semibold text-slate-900">{{ ucfirst($order->pickup_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Alamat:</span>
                        <span class="font-semibold text-slate-900 text-right max-w-[200px] truncate">{{ $order->address->full_address ?? 'Drop-off langsung ke outlet' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Spesialis Pencuci / Kurir:</span>
                        <span class="font-semibold text-slate-900">{{ $order->employee->user->name ?? 'Ditugaskan ke Tim CleanWave' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status Bayar:</span>
                        <span class="font-bold {{ $order->payment && $order->payment->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $order->payment ? $order->payment->status_label : 'Belum Ada' }} ({{ strtoupper($order->payment->method ?? 'transfer') }})
                        </span>
                    </div>
                </div>

                <!-- Total Box -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Subtotal Cucian:</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-xs text-emerald-600 font-medium">
                        <span>Diskon Kupon:</span>
                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Biaya Antar-Jemput:</span>
                        <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                        <span>Total Tagihan:</span>
                        <span class="text-brand-600 text-base">{{ $order->formatted_total }}</span>
                    </div>
                </div>

                <a href="https://wa.me/6281234567890?text=Halo%20CleanWave,%20saya%20mau%20tanya%20progres%20order%20{{ $order->order_code }}" target="_blank" class="w-full py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs transition flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Chat CS mengenai Pesanan Ini
                </a>
            </div>
        </div>

    </div>
    @elseif(request()->filled('code'))
    <div class="text-center py-12 bg-white rounded-3xl border border-slate-200 p-8">
        <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-2xl mx-auto mb-4">
            <i class="fa-solid fa-magnifying-glass-chart"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Pesanan Tidak Ditemukan</h3>
        <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1">
            Pastikan kode pesanan <strong>"{{ request('code') }}"</strong> sudah sesuai dengan yang tertera di nota atau SMS/WhatsApp konfirmasi.
        </p>
    </div>
    @endif

</div>
</div>
@endsection