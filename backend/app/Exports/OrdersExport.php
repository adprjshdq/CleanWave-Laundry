<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    public function __construct(
        private string $from,
        private string $to,
    ) {
    }

    public function collection(): Collection
    {
        return Order::with(['customer.user', 'items.service', 'payment', 'employee.user'])
            ->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Order',
            'Tanggal',
            'Pelanggan',
            'No. HP',
            'Layanan',
            'Status Pesanan',
            'Metode Bayar',
            'Status Bayar',
            'Poin Ditukar',
            'Subtotal',
            'Diskon Kupon',
            'Ongkir',
            'Total',
        ];
    }

    public function map($order): array
    {
        $services = $order->items
            ->map(fn ($item) => ($item->service->name ?? 'Layanan') . ' (' . $item->quantity . ' ' . ($item->service->unit ?? '') . ')')
            ->implode(', ');

        return [
            $order->order_code,
            $order->created_at->format('d/m/Y H:i'),
            $order->customer->user->name ?? '-',
            $order->customer->user->phone ?? '-',
            $services,
            $order->status_label,
            $order->payment->method_label ?? '-',
            $order->payment->status_label ?? '-',
            $order->points_redeemed,
            (float) $order->subtotal,
            (float) $order->discount,
            (float) $order->delivery_fee,
            (float) $order->total,
        ];
    }

    public function title(): string
    {
        return 'Laporan Pesanan';
    }
}
