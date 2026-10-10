@extends('layouts.admin')

@section('title', 'Manajemen Layanan & Tarif Laundry')

@section('content')
<div class="space-y-8 max-w-6xl">

    <!-- Add Service Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-brand-600"></i> Tambah Layanan Laundry Baru
        </h2>

        <form action="{{ route('admin.services.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kategori:</label>
                <select name="category_id" class="w-full rounded-xl border border-slate-300 text-xs py-2 px-3 font-semibold" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Layanan:</label>
                <input type="text" name="name" placeholder="Misal: Cuci Karpet Tebal" class="w-full rounded-xl border border-slate-300 text-xs py-2 px-3" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Satuan & Harga (Rp):</label>
                <div class="flex gap-2">
                    <input type="text" name="unit" placeholder="kg/pcs" class="w-20 rounded-xl border border-slate-300 text-xs py-2 px-2 text-center" value="kg" required>
                    <input type="number" name="price_per_unit" placeholder="15000" class="w-full rounded-xl border border-slate-300 text-xs py-2 px-3 font-bold" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi (Hari):</label>
                <input type="number" name="estimated_days" value="2" min="1" class="w-full rounded-xl border border-slate-300 text-xs py-2 px-3 text-center" required>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    + Tambah Layanan
                </button>
            </div>
        </form>
    </div>

    <!-- Services Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-base text-slate-900">Daftar Semua Layanan Aktif</h3>
            <p class="text-xs text-slate-500">Harga ini langsung muncul di katalog publik dan form pemesanan pelanggan.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3 px-4 font-bold">Layanan</th>
                        <th class="py-3 px-4 font-bold">Kategori</th>
                        <th class="py-3 px-4 font-bold">Harga Satuan</th>
                        <th class="py-3 px-4 font-bold">Estimasi Selesai</th>
                        <th class="py-3 px-4 font-bold">Status</th>
                        <th class="py-3 px-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($services as $svc)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $svc->name }}
                                <span class="text-[11px] text-slate-400 block font-normal">{{ $svc->description ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-brand-700">
                                    {{ $svc->category->name ?? 'Kategori' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-extrabold text-brand-600 text-sm">
                                {{ $svc->formatted_price }} <span class="text-xs text-slate-400 font-normal">/ {{ $svc->unit }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                <i class="fa-regular fa-clock text-slate-400 mr-1"></i> {{ $svc->estimated_days }} Hari Kerja
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                    Aktif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="{{ route('admin.services.destroy', $svc->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs p-1" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada layanan yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $services->links() }}
        </div>
    </div>

</div>
@endsection
