<?php

namespace App\Exports;

use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ServicePerformanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    public function __construct(
        private string $from,
        private string $to,
    ) {
    }

    public function collection(): Collection
    {
        return OrderItem::query()
            ->select(
                'order_items.service_id',
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('order_items.service_id')
            ->orderByDesc('total_revenue')
            ->with('service.category')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Layanan',
            'Kategori',
            'Satuan',
            'Jumlah Pesanan',
            'Total Kuantitas',
            'Total Pendapatan',
        ];
    }

    public function map($row): array
    {
        return [
            $row->service->name ?? 'Layanan',
            $row->service->category->name ?? '-',
            $row->service->unit ?? '-',
            (int) $row->total_orders,
            (float) $row->total_quantity,
            (float) $row->total_revenue,
        ];
    }

    public function title(): string
    {
        return 'Performa Layanan';
    }
}
