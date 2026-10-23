<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_code }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header td {
            vertical-align: top;
        }

        .brand {
            font-size: 26px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }

        .brand span {
            color: #2563eb;
        }

        .tagline {
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 2px;
        }

        .invoice-title {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 1px;
            margin: 0;
        }

        .invoice-meta {
            text-align: right;
            font-size: 10px;
            color: #64748b;
            margin-top: 4px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            vertical-align: top;
            width: 50%;
        }

        .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .value {
            font-size: 11px;
            color: #0f172a;
            line-height: 1.6;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-paid {
            background: #d1fae5;
            color: #047857;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        table.items th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 9px 8px;
            text-align: left;
            border-bottom: 2px solid #bfdbfe;
        }

        table.items td {
            padding: 9px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        table.items .right {
            text-align: right;
        }

        .summary {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .summary td {
            padding: 5px 8px;
            font-size: 11px;
            color: #475569;
        }

        .summary .right {
            text-align: right;
            font-weight: bold;
            color: #0f172a;
        }

        .summary .discount {
            color: #059669;
        }

        .summary .total-row td {
            border-top: 2px solid #cbd5e1;
            padding-top: 10px;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }

        .summary .total-row .right {
            color: #2563eb;
        }

        .note-box {
            margin-top: 26px;
            padding: 12px 14px;
            background: #f8fafc;
            border-left: 3px solid #2563eb;
            font-size: 10px;
            color: #475569;
            line-height: 1.6;
        }

        .footer {
            margin-top: 34px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <td>
                <p class="brand">Clean<span>Wave</span></p>
                <p class="tagline">Laundry Service</p>
                <p class="value" style="font-size:10px; color:#64748b; margin-top:8px;">
                    Jl. Boulevard Raya Blok A4 No. 12, Kelapa Gading<br>
                    Jakarta Utara 14240<br>
                    Telp: 0812-3456-7890 &middot; admin@cleanwave.com
                </p>
            </td>
            <td>
                <p class="invoice-title">INVOICE</p>
                <p class="invoice-meta">
                    No. Invoice: <strong>{{ $order->order_code }}</strong><br>
                    Tanggal: {{ $order->created_at->format('d F Y, H:i') }} WIB
                </p>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <p class="label">Ditagihkan Kepada</p>
                <p class="value">
                    <strong>{{ $order->customer->user->name ?? '-' }}</strong><br>
                    {{ $order->customer->user->email ?? '-' }}<br>
                    {{ $order->customer->user->phone ?? '-' }}<br>
                    {{ $order->address->full_address ?? 'Drop-off langsung ke outlet CleanWave' }}
                    @if($order->address && $order->address->city)
                    , {{ $order->address->city }}
                    @endif
                </p>
            </td>
            <td style="text-align:right;">
                <p class="label">Status Pembayaran</p>
                <p class="value">
                    @if($order->payment)
                    <span class="status-badge {{ $order->payment->status === 'paid' ? 'status-paid' : '' }}">
                        {{ $order->payment->status_label }}
                    </span><br>
                    <span style="font-size:10px; color:#64748b;">
                        Metode: {{ $order->payment->method_label }}<br>
                        @if($order->payment->paid_at)
                        Dibayar: {{ $order->payment->paid_at->format('d F Y, H:i') }} WIB
                        @endif
                    </span>
                    @else
                    <span class="status-badge">Belum Ada Pembayaran</span>
                    @endif
                </p>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width:40%;">Layanan</th>
                <th class="right">Harga Satuan</th>
                <th class="right">Kuantitas</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->service->name ?? 'Layanan Laundry' }}</strong>
                    @if($item->service && $item->service->description)
                    <br><span style="font-size:9px; color:#94a3b8;">{{ $item->service->description }}</span>
                    @endif
                </td>
                <td class="right">Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
                <td class="right">{{ $item->quantity }} {{ $item->service->unit ?? '' }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td>Subtotal</td>
            <td class="right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($order->discount > 0)
        <tr>
            <td class="discount">Diskon Kupon {{ $order->coupon->code ?? '' }}</td>
            <td class="right discount">- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($order->points_redeemed > 0)
        <tr>
            <td class="discount">Tukar Poin Loyalitas ({{ $order->points_redeemed }} poin)</td>
            <td class="right discount">- Rp {{ number_format($order->points_redeemed, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td>Biaya Antar-Jemput</td>
            <td class="right">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Tagihan</td>
            <td class="right">{{ $order->formatted_total }}</td>
        </tr>
    </table>

    @if($order->notes)
    <div class="note-box">
        <strong>Catatan Pesanan:</strong><br>
        {{ $order->notes }}
    </div>
    @endif

    <div class="note-box">
        <strong>Terima kasih telah mempercayakan cucian Anda kepada CleanWave.</strong><br>
        Invoice ini dibuat secara otomatis oleh sistem dan sah tanpa tanda tangan. Untuk pertanyaan, hubungi admin CleanWave.
    </div>

    <div class="footer">
        CleanWave Laundry Service &middot; Cuci Bersih, Harum, Rapi<br>
        &copy; {{ now()->year }} CleanWave. Semua hak dilindungi.
    </div>
</body>

</html>
