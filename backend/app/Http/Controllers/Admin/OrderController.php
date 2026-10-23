<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Employee;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with(['customer.user', 'employee.user', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhereHas('customer.user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->paginate(15);
        $employees = Employee::with('user')->where('is_available', true)->get();

        return view('admin.orders.index', compact('orders', 'employees'));
    }

    public function show($id)
    {
        $order = Order::with(['customer.user', 'employee.user', 'address', 'coupon', 'items.service', 'payment', 'review'])
            ->findOrFail($id);

        $employees = Employee::with('user')->get();

        return view('admin.orders.show', compact('order', 'employees'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $order = Order::findOrFail($id);
        $this->orderService->updateStatus($order, $request->status, $request->employee_id);

        return back()->with('success', "Status pesanan {$order->order_code} berhasil diperbarui.");
    }

    public function invoice($id)
    {
        $order = Order::with(['customer.user', 'items.service', 'payment', 'coupon', 'address'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))->setPaper('a4');

        return $pdf->download('Invoice-' . $order->order_code . '.pdf');
    }
}
