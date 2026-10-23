@extends('layouts.cleanwave')

@section('content')
<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('customer.orders.index') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.orders.invoice', $order->id) }}" class="px-3.5 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-file-arrow-down"></i> Invoice PDF
                </a>
                <a href="{{ route('tracking.result', $order->order_code) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-location-crosshairs text-brand-600"></i> Buka Halaman Tracking
                </a>
            </div>
        </div>

        <div class="space-y-6">

            <!-- Order Status Banner -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rincian Transaksi</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 mt-0.5">
                        {{ $order->order_code }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB • Metode {{ ucfirst($order->pickup_method) }}
                    </p>
                </div>

                <div class="text-left sm:text-right">
                    <span class="text-xs text-slate-400 block">Status Pengerjaan</span>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-brand-700 mt-1">
                        <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <!-- Items & Financial Breakdown -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-basket-shopping text-brand-600"></i> Rincian Cucian
                </h2>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center py-2 border-b border-slate-50 text-sm">
                        <div>
                            <p class="font-bold text-slate-900">{{ $item->service->name ?? 'Layanan Laundry' }}</p>
                            <p class="text-xs text-slate-500">{{ $item->quantity }} {{ $item->service->unit ?? 'kg' }} × Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Price Summary -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-600 font-medium">
                        <span>Diskon Promo ({{ $order->coupon->code ?? 'Kupon' }}):</span>
                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($order->points_redeemed > 0)
                    <div class="flex justify-between text-emerald-600 font-medium">
                        <span>Tukar Poin ({{ number_format($order->points_redeemed, 0, ',', '.') }} poin):</span>
                        <span>- Rp {{ number_format($order->points_redeemed, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span>Biaya Antar-Jemput:</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-slate-900 pt-3 border-t border-slate-200">
                        <span>Total Tagihan:</span>
                        <span class="text-brand-600 text-lg">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status & Upload Section -->
            @if($order->payment)
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-brand-600"></i> Informasi Pembayaran
                    </h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $order->payment->status_label }}
                    </span>
                </div>

                @if($order->payment->status === 'unpaid')
                <div class="p-5 rounded-2xl bg-blue-50/50 border border-blue-200 space-y-3">
                    <h3 class="font-bold text-sm text-brand-900">Petunjuk Pembayaran ({{ strtoupper($order->payment->method) }})</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Silakan transfer tepat sebesar <strong>{{ $order->formatted_total }}</strong> ke salah satu rekening resmi CleanWave:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div class="bg-white p-3 rounded-xl border border-blue-100 text-xs">
                            <span class="font-bold block text-slate-900">Bank Central Asia (BCA)</span>
                            <span class="font-mono text-brand-600 text-sm font-bold">8801-2345-6789</span>
                            <span class="block text-[11px] text-slate-400">a.n. PT CleanWave Indonesia</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-blue-100 text-xs">
                            <span class="font-bold block text-slate-900">Bank Mandiri</span>
                            <span class="font-mono text-brand-600 text-sm font-bold">123-00-9876543-1</span>
                            <span class="block text-[11px] text-slate-400">a.n. PT CleanWave Indonesia</span>
                        </div>
                    </div>
                </div>

                <!-- Upload Proof Form -->
                <form action="{{ route('customer.payments.proof', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Unggah Foto Bukti Transfer / Resi Pembayaran:</label>
                        <input type="file" name="proof_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-700 border border-slate-300 rounded-xl p-1" required>
                        <span class="text-[11px] text-slate-400 block mt-1">Format: JPG, PNG, WEBP (Maksimal 3MB).</span>
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i> Kirim Bukti Pembayaran
                    </button>
                </form>
                @elseif($order->payment->status === 'pending_verification')
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-hourglass-half text-lg text-amber-500"></i>
                    <div>
                        <p class="font-bold">Bukti Pembayaran Sudah Diunggah!</p>
                        <p class="text-amber-700">Tim admin sedang memverifikasi transfer Anda. Status akan berubah menjadi "Lunas" dalam 5-15 menit.</p>
                    </div>
                </div>
                @else
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-500"></i>
                    <div>
                        <p class="font-bold">Pembayaran Telah Diverifikasi Lunas</p>
                        <p class="text-emerald-700">Diverifikasi pada: {{ $order->payment->paid_at ? $order->payment->paid_at->format('d M Y, H:i') : '-' }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Review Section if Order is Completed -->
            @if($order->status === 'completed')
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-star text-amber-400"></i> Ulasan & Kepuasan Pelanggan
                </h2>

                @if($order->review)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex items-center gap-1 text-amber-400 text-base">
                        @for($i = 0; $i < $order->review->rating; $i++)
                            <i class="fa-solid fa-star"></i>
                            @endfor
                    </div>
                    <p class="text-xs text-slate-700 italic">"{{ $order->review->comment }}"</p>
                    <span class="text-[11px] text-slate-400 block">Ditulis pada {{ $order->review->created_at->format('d M Y') }}</span>
                </div>
                @else
                <!-- Form Create Review -->
                <form action="{{ route('customer.reviews.store', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Beri Penilaian Bintang (1 - 5):</label>
                        <select name="rating" class="rounded-xl border border-slate-300 text-xs px-3 py-2 w-48 font-bold">
                            <option value="5">⭐⭐⭐⭐⭐ Sangat Puas (5)</option>
                            <option value="4">⭐⭐⭐⭐ Bagus (4)</option>
                            <option value="3">⭐⭐⭐ Cukup (3)</option>
                            <option value="2">⭐⭐ Kurang (2)</option>
                            <option value="1">⭐ Buruk (1)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Komentar / Pengalaman Anda:</label>
                        <textarea name="comment" rows="3" placeholder="Ceritakan bagaimana keharuman cucian, kerapian setrika, atau keramahan kurir..." class="w-full rounded-xl border border-slate-300 text-xs p-3 focus:ring-2 focus:ring-brand-500" required></textarea>
                    </div>

                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs transition shadow-sm">
                        Kirim Ulasan
                    </button>
                </form>
                @endif
            </div>
            @endif

        </div>

    </div>
</div>
@endsection