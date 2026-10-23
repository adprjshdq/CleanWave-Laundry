<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Exports\ServicePerformanceExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->range($request);

        $orders = Order::with(['customer.user', 'items.service', 'payment'])
            ->whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalOrders = Order::whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->count();
        $completedOrders = Order::whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->where('status', 'completed')
            ->count();
        $revenue = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->sum('amount');
        $avgOrder = Order::whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->where('status', '!=', 'cancelled')
            ->avg('total') ?? 0;

        $servicePerformance = $this->servicePerformance($from, $to);

        return view('admin.reports.index', compact(
            'from',
            'to',
            'orders',
            'totalOrders',
            'completedOrders',
            'revenue',
            'avgOrder',
            'servicePerformance'
        ));
    }

    public function exportOrders(Request $request)
    {
        [$from, $to] = $this->range($request);

        return Excel::download(
            new OrdersExport($from->toDateString(), $to->toDateString()),
            'laporan-pesanan-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.xlsx'
        );
    }

    public function exportServices(Request $request)
    {
        [$from, $to] = $this->range($request);

        return Excel::download(
            new ServicePerformanceExport($from->toDateString(), $to->toDateString()),
            'performa-layanan-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.xlsx'
        );
    }

    private function range(Request $request): array
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);

        $from = $request->filled('from') ? Carbon::parse($request->from) : now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->to) : now();

        return [$from, $to];
    }

    private function servicePerformance(Carbon $from, Carbon $to)
    {
        return OrderItem::query()
            ->select(
                'order_items.service_id',
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('order_items.service_id')
            ->orderByDesc('total_revenue')
            ->with('service.category')
            ->get();
    }
}
