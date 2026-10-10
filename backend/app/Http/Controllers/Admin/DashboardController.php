<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::whereIn('status', ['confirmed', 'picked_up', 'processing'])->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $pendingPayments = Payment::where('status', 'pending_verification')->count();
        $totalCustomers = Customer::count();
        $avgRating = round(Review::avg('rating') ?? 5, 1);

        $recentOrders = Order::with(['customer.user', 'payment', 'employee.user'])
            ->latest()
            ->take(8)
            ->get();

        // Status breakdown
        $statusCounts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'totalRevenue',
            'monthlyRevenue',
            'pendingPayments',
            'totalCustomers',
            'avgRating',
            'recentOrders',
            'statusCounts'
        ));
    }
}
