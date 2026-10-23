@extends('layouts.admin')

@section('title', 'Laporan & Export')

@section('content')
<div class="space-y-6">

    <!-- Header & Filter -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 font-heading">Laporan Operasional</h2>
                <p class="text-xs text-slate-500 mt-1">Ringkasan pesanan, pendapatan, dan performa layanan per periode.</p>
            </div>

            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ $from->toDateString() }}" class="rounded-xl border border-slate-300 text-xs px-3 py-2 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ $to->toDateString() }}" class="rounded-xl border border-slate-300 text-xs px-3 py-2 font-semibold">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition">
                    <i class="fa-solid fa-filter mr-1"></i> Terapkan
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Pesanan</span>
                <i class="fa-solid fa-clipboard-list text-brand-600"></i>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 mt-2">{{ number_format($totalOrders, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">{{ number_format($completedOrders, 0, ',', '.') }} pesanan selesai</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pendapatan Lunas</span>
                <i class="fa-solid fa-money-bill-wave text-emerald-600"></i>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 mt-2">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Berdasarkan tanggal pembayaran</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Rata-rata / Pesanan</span>
                <i class="fa-solid fa-calculator text-accent-600"></i>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 mt-2">Rp {{ number_format($avgOrder, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Tidak termasuk pesanan dibatalkan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Layanan Terjual</span>
                <i class="fa-solid fa-tags text-amber-500"></i>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 mt-2">{{ $servicePerformance->count() }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Jenis layanan dengan transaksi</p>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Unduh Laporan</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Format Excel (.xlsx) sesuai rentang tanggal yang dipilih.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.orders.export', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-excel"></i> Export Pesanan
            </a>
            <a href="{{ route('admin.reports.services.export', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}" class="px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-chart-bar"></i> Export Performa Layanan
            </a>
        </div>
    </div>

    <!-- Service Performance -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-ranking-star text-brand-600"></i> Performa Layanan
        </h3>

        @if($servicePerformance->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-2.5 px-2">Layanan</th>
                        <th class="py-2.5 px-2">Kategori</th>
                        <th class="py-2.5 px-2 text-right">Pesanan</th>
                        <th class="py-2.5 px-2 text-right">Kuantitas</th>
                        <th class="py-2.5 px-2 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($servicePerformance as $row)
                    <tr>
                        <td class="py-3 px-2 font-bold text-slate-900">{{ $row->service->name ?? 'Layanan' }}</td>
                        <td class="py-3 px-2 text-slate-600">{{ $row->service->category->name ?? '-' }}</td>
                        <td class="py-3 px-2 text-right font-semibold text-slate-800">{{ number_format($row->total_orders, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-right text-slate-600">{{ number_format($row->total_quantity, 0, ',', '.') }} {{ $row->service->unit ?? '' }}</td>
                        <td class="py-3 px-2 text-right font-extrabold text-brand-600">Rp {{ number_format($row->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-xs text-slate-400 text-center py-8">Belum ada transaksi layanan pada periode ini.</p>
        @endif
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-receipt text-brand-600"></i> Daftar Pesanan ({{ number_format($totalOrders, 0, ',', '.') }})
        </h3>

        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-2.5 px-2">Kode</th>
                        <th class="py-2.5 px-2">Tanggal</th>
                        <th class="py-2.5 px-2">Pelanggan</th>
                        <th class="py-2.5 px-2">Status</th>
                        <th class="py-2.5 px-2">Pembayaran</th>
                        <th class="py-2.5 px-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-slate-50/60">
                        <td class="py-3 px-2 font-mono font-bold text-brand-600">{{ $order->order_code }}</td>
                        <td class="py-3 px-2 text-slate-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-2">
                            <span class="font-semibold text-slate-900">{{ $order->customer->user->name ?? '-' }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $order->customer->user->phone ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-brand-700">{{ $order->status_label }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ ($order->payment->status ?? '') === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $order->payment->status_label ?? 'Belum Bayar' }}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-right font-extrabold text-slate-900">{{ $order->formatted_total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $orders->links() }}
        </div>
        @else
        <p class="text-xs text-slate-400 text-center py-8">Belum ada pesanan pada periode ini.</p>
        @endif
    </div>

</div>
@endsection
