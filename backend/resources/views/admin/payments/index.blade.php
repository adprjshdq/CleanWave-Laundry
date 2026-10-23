@extends('layouts.admin')

@section('title', 'Verifikasi & Data Pembayaran')

@section('content')
<div class="space-y-6 max-w-6xl">

    <!-- Filters Header -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daftar Transaksi Pembayaran</h2>
            <p class="text-xs text-slate-500">Periksa bukti transfer dan konfirmasi pembayaran pelanggan.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.payments.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.payments.index') }}?status=pending_verification" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'pending_verification' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
                Menunggu Verifikasi
            </a>
            <a href="{{ route('admin.payments.index') }}?status=paid" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'paid' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
                Lunas
            </a>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-4 font-bold">Kode Bayar</th>
                        <th class="py-3.5 px-4 font-bold">Kode Pesanan</th>
                        <th class="py-3.5 px-4 font-bold">Pelanggan</th>
                        <th class="py-3.5 px-4 font-bold">Metode</th>
                        <th class="py-3.5 px-4 font-bold">Nominal</th>
                        <th class="py-3.5 px-4 font-bold">Bukti Transfer</th>
                        <th class="py-3.5 px-4 font-bold">Status</th>
                        <th class="py-3.5 px-4 font-bold text-right">Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $pay)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                            {{ $pay->payment_code }}
                            <span class="text-[10px] text-slate-400 block font-normal">{{ $pay->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-brand-600">
                            <a href="{{ route('admin.orders.show', $pay->order->id) }}">{{ $pay->order->order_code }}</a>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-900 block">{{ $pay->order->customer->user->name ?? 'Pelanggan' }}</span>
                            <span class="text-[11px] text-slate-400">{{ $pay->order->customer->user->phone ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            {{ $pay->method_label }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                            {{ $pay->formatted_amount }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($pay->proof_image)
                            <a href="{{ asset('storage/' . $pay->proof_image) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-brand-600 font-bold hover:bg-blue-100">
                                <i class="fa-solid fa-image"></i> Lihat Foto
                            </a>
                            @else
                            <span class="text-slate-400 italic">Belum unggah</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $pay->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($pay->status === 'pending_verification' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $pay->status_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            @if($pay->status !== 'paid')
                            <form action="{{ route('admin.payments.verify', $pay->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="paid">
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition shadow-sm" title="Tandai Lunas">
                                    <i class="fa-solid fa-check"></i> Lunas
                                </button>
                            </form>
                            @else
                            <span class="text-emerald-600 font-bold text-[11px]">
                                <i class="fa-solid fa-circle-check"></i> Terverifikasi
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">Tidak ada data pembayaran yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

</div>
@endsection