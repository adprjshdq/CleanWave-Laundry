@extends('layouts.admin')

@section('title', 'Dashboard Ringkasan Operasional')

@section('content')
<div class="space-y-8">

    <!-- Top KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Orders -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Total Pesanan</span>
                <p class="text-3xl font-extrabold text-slate-900 font-heading mt-1">{{ $totalOrders }}</p>
                <span class="text-[11px] text-slate-600 mt-1 block">Semua riwayat transaksi</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
        </div>

        <!-- Processing Orders -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-500 uppercase tracking-wider">Sedang Diproses</span>
                <p class="text-3xl font-extrabold text-amber-600 font-heading mt-1">{{ $processingOrders }}</p>
                <span class="text-[11px] text-slate-600 mt-1 block">Cucian aktif di outlet</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-soap animate-spin"></i>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Total Pendapatan</span>
                <p class="text-2xl font-extrabold text-emerald-600 font-heading mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <span class="text-[11px] text-slate-600 mt-1 block">Bulan ini: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-rose-500 uppercase tracking-wider">Verifikasi Bayar</span>
                <p class="text-3xl font-extrabold text-rose-600 font-heading mt-1">{{ $pendingPayments }}</p>
                <span class="text-[11px] text-slate-600 mt-1 block">Menunggu dicek admin</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>

    </div>

    <!-- Main Content: Recent Orders Table & Quick Info -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Pesanan Masuk Terbaru</h2>
                    <p class="text-xs text-slate-500">Daftar transaksi laundry real-time yang perlu diproses.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                    Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-600 uppercase tracking-wider border-b border-slate-100 pb-2">
                            <th class="py-3 px-2 font-bold">Kode</th>
                            <th class="py-3 px-2 font-bold">Pelanggan</th>
                            <th class="py-3 px-2 font-bold">Layanan</th>
                            <th class="py-3 px-2 font-bold">Total</th>
                            <th class="py-3 px-2 font-bold">Status</th>
                            <th class="py-3 px-2 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-2 font-mono font-bold text-brand-600">
                                <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_code }}</a>
                            </td>
                            <td class="py-3 px-2">
                                <span class="font-bold text-slate-900 block">{{ $order->customer->user->name ?? 'Pelanggan' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $order->customer->user->phone ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-2 text-slate-600">
                                {{ count($order->items) }} Layanan
                            </td>
                            <td class="py-3 px-2 font-bold text-slate-900">
                                {{ $order->formatted_total }}
                            </td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-brand-700">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400">Belum ada pesanan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Summary & Actions (4 cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Aksi Cepat</h3>
                <div class="space-y-2.5">
                    <a href="{{ route('admin.orders.index') }}?status=pending" class="w-full flex items-center justify-between p-3 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-semibold text-xs transition border border-amber-200">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-clock"></i> Pesanan Menunggu Konfirmasi</span>
                        <span class="w-6 h-6 rounded-full bg-amber-500 text-white font-bold flex items-center justify-center text-[10px]">{{ $pendingOrders }}</span>
                    </a>

                    <a href="{{ route('admin.payments.index') }}" class="w-full flex items-center justify-between p-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-900 font-semibold text-xs transition border border-rose-200">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-receipt"></i> Cek Bukti Transfer Masuk</span>
                        <span class="w-6 h-6 rounded-full bg-rose-500 text-white font-bold flex items-center justify-center text-[10px]">{{ $pendingPayments }}</span>
                    </a>

                    <a href="{{ route('admin.services.index') }}" class="w-full flex items-center justify-between p-3 rounded-2xl bg-blue-50 hover:bg-blue-100 text-brand-900 font-semibold text-xs transition border border-blue-200">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-plus-circle"></i> Tambah / Ubah Harga Layanan</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Operational Status Breakdown -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Status Operasional</h3>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Menunggu Konfirmasi:</span>
                        <span class="font-bold text-amber-600">{{ $statusCounts['pending'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Sedang Dicuci / Setrika:</span>
                        <span class="font-bold text-brand-600">{{ ($statusCounts['processing'] ?? 0) + ($statusCounts['confirmed'] ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Siap Dikirim / Diantar:</span>
                        <span class="font-bold text-accent-600">{{ ($statusCounts['ready'] ?? 0) + ($statusCounts['on_delivery'] ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Selesai:</span>
                        <span class="font-bold text-emerald-600">{{ $statusCounts['completed'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection