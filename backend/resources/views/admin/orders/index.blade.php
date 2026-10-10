@extends('layouts.admin')

@section('title', 'Manajemen Pesanan Laundry')

@section('content')
<div class="space-y-6">

    <!-- Search & Filters Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Order, Nama Pelanggan, atau No. HP..." class="w-full pl-11 pr-4 py-2.5 rounded-2xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <select name="status" class="py-2.5 px-4 rounded-2xl border border-slate-300 text-xs font-semibold" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>Dijemput</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Sedang Dicuci</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Siap Dikirim</option>
                <option value="on_delivery" {{ request('status') === 'on_delivery' ? 'selected' : '' }}>Dalam Pengantaran</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit" class="px-5 py-2.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-4 px-4 font-bold">Kode Order</th>
                        <th class="py-4 px-4 font-bold">Pelanggan</th>
                        <th class="py-4 px-4 font-bold">Metode</th>
                        <th class="py-4 px-4 font-bold">Total Biaya</th>
                        <th class="py-4 px-4 font-bold">Pembayaran</th>
                        <th class="py-4 px-4 font-bold">Petugas / Kurir</th>
                        <th class="py-4 px-4 font-bold">Status Pesanan</th>
                        <th class="py-4 px-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $ord)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-brand-600">
                            <a href="{{ route('admin.orders.show', $ord->id) }}">{{ $ord->order_code }}</a>
                            <span class="text-[10px] text-slate-400 block">{{ $ord->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-900 block">{{ $ord->customer->user->name ?? 'Pelanggan' }}</span>
                            <span class="text-[11px] text-slate-400">{{ $ord->customer->user->phone ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[11px]">
                                {{ ucfirst($ord->pickup_method) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $ord->formatted_total }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $ord->payment && $ord->payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $ord->payment ? $ord->payment->status_label : 'Belum Bayar' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-700">
                            {{ $ord->employee->user->name ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-brand-700">
                                {{ $ord->status_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs transition">
                                Kelola & Update
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">Tidak ada pesanan yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection