@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_code)

@section('content')
<div class="space-y-6 max-w-5xl">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    <!-- Update Status Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-arrows-rotate text-brand-600"></i> Perbarui Status & Petugas
        </h2>

        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status Pengerjaan:</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 text-xs py-2.5 px-3 font-semibold">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Dikonfirmasi)</option>
                    <option value="picked_up" {{ $order->status === 'picked_up' ? 'selected' : '' }}>Picked Up (Dijemput Kurir)</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Sedang Dicuci & Setrika)</option>
                    <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready (Selesai Dicuci, Siap Antar)</option>
                    <option value="on_delivery" {{ $order->status === 'on_delivery' ? 'selected' : '' }}>On Delivery (Sedang Diantar)</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tugaskan Petugas / Kurir:</label>
                <select name="employee_id" class="w-full rounded-xl border border-slate-300 text-xs py-2.5 px-3 font-semibold">
                    <option value="">-- Belum Ditugaskan --</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ $order->employee_id == $emp->id ? 'selected' : '' }}>
                        {{ $emp->user->name }} ({{ $emp->position }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Order Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Left: Customer & Address -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-user text-brand-600"></i> Informasi Pelanggan & Alamat
            </h3>

            <div class="space-y-2 text-xs text-slate-600">
                <div class="flex justify-between">
                    <span>Nama:</span>
                    <span class="font-bold text-slate-900">{{ $order->customer->user->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Email:</span>
                    <span class="font-bold text-slate-900">{{ $order->customer->user->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>No. WhatsApp:</span>
                    <span class="font-bold text-slate-900">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer->user->phone ?? '') }}" target="_blank" class="text-emerald-600 hover:underline">
                            {{ $order->customer->user->phone ?? '-' }} <i class="fa-brands fa-whatsapp ml-1"></i>
                        </a>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span>Metode:</span>
                    <span class="font-bold text-slate-900">{{ ucfirst($order->pickup_method) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-100">
                    <span class="font-semibold text-slate-700 block mb-1">Alamat Penjemputan / Pengantaran:</span>
                    <p class="text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        {{ $order->address->full_address ?? 'Drop-off langsung ke outlet CleanWave.' }}
                    </p>
                </div>
                @if($order->notes)
                <div class="pt-1">
                    <span class="font-semibold text-amber-800 block mb-1">Catatan Pelanggan:</span>
                    <p class="text-amber-900 bg-amber-50 p-2.5 rounded-xl border border-amber-200">
                        {{ $order->notes }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Payment & Verification -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-brand-600"></i> Status & Bukti Pembayaran
            </h3>

            @if($order->payment)
            <div class="space-y-3 text-xs text-slate-600">
                <div class="flex justify-between items-center">
                    <span>Kode Pembayaran:</span>
                    <span class="font-mono font-bold text-slate-900">{{ $order->payment->payment_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Metode:</span>
                    <span class="font-bold text-slate-900">{{ $order->payment->method_label }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Status Bayar:</span>
                    <span class="px-2.5 py-1 rounded-full font-bold {{ $order->payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $order->payment->status_label }}
                    </span>
                </div>

                @if($order->payment->proof_image)
                <div class="pt-3 border-t border-slate-100">
                    <span class="font-semibold text-slate-700 block mb-2">Foto Bukti Transfer Pelanggan:</span>
                    <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" class="w-full max-h-48 object-cover rounded-xl border border-slate-200 hover:opacity-90 transition">
                    </a>
                </div>
                @endif

                @if($order->payment->status !== 'paid')
                <form action="{{ route('admin.payments.verify', $order->payment->id) }}" method="POST" class="pt-2">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="paid">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition">
                        <i class="fa-solid fa-check mr-1.5"></i> Verifikasi Sebagai Lunas
                    </button>
                </form>
                @endif
            </div>
            @else
            <p class="text-xs text-slate-400">Belum ada record pembayaran.</p>
            @endif
        </div>

    </div>

    <!-- Items List Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">
            Rincian Item Cucian
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                        <th class="py-2">Nama Layanan</th>
                        <th class="py-2">Harga Satuan</th>
                        <th class="py-2">Kuantitas</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-3 font-bold text-slate-900">{{ $item->service->name ?? 'Layanan' }}</td>
                        <td class="py-3 text-slate-600">Rp {{ number_format($item->price_per_unit, 0, ',', '.') }} / {{ $item->service->unit ?? 'kg' }}</td>
                        <td class="py-3 font-semibold text-slate-800">{{ $item->quantity }} {{ $item->service->unit ?? 'kg' }}</td>
                        <td class="py-3 text-right font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 max-w-sm ml-auto space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal Cucian:</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount > 0)
            <div class="flex justify-between text-emerald-600 font-bold">
                <span>Diskon Kupon:</span>
                <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-slate-600">
                <span>Biaya Antar-Jemput:</span>
                <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-extrabold text-sm text-slate-900 pt-2 border-t border-slate-200">
                <span>Total:</span>
                <span class="text-brand-600">{{ $order->formatted_total }}</span>
            </div>
        </div>
    </div>

</div>
@endsection