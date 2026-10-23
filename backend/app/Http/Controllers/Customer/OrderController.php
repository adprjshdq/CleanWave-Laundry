<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Address;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    protected function currentCustomer(): \App\Models\Customer
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $customer = $user->customer;
        if (!$customer) {
            $customer = $user->customer()->create();
        }
        return $customer;
    }

    public function index()
    {
        $customer = $this->currentCustomer();

        $orders = Order::with(['items.service', 'payment'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view('customer.order.index', compact('orders'));
    }

    public function create()
    {
        $customer = $this->currentCustomer();

        $categories = ServiceCategory::with('activeServices')
            ->where('is_active', true)
            ->get();

        $addresses = $customer->addresses;

        return view('customer.order.create', compact('categories', 'addresses', 'customer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.5',
            'pickup_method' => 'required|in:pickup,dropoff',
            'address_id' => 'nullable|exists:addresses,id',
            'notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string',
            'points_redeemed' => 'nullable|integer|min:0',
            'payment_method' => 'required|in:qris,transfer,cash',
        ]);

        $customer = $this->currentCustomer();

        if (($request->points_redeemed ?? 0) > $customer->loyalty_points) {
            return back()->withErrors(['points_redeemed' => 'Poin yang ingin ditukar melebihi saldo poin Anda.'])->withInput();
        }

        // If pickup method is pickup and no address is chosen, validate new address if provided
        $addressId = $request->address_id;
        if ($request->pickup_method === 'pickup' && !$addressId) {
            if ($request->filled('new_address')) {
                $addr = Address::create([
                    'customer_id' => $customer->id,
                    'label' => $request->address_label ?? 'Alamat Antar-Jemput',
                    'full_address' => $request->new_address,
                    'city' => $request->city ?? 'Jakarta',
                    'is_default' => true,
                ]);
                $addressId = $addr->id;
            } else {
                return back()->withErrors(['address_id' => 'Alamat penjemputan wajib dipilih atau diisi.'])->withInput();
            }
        }

        $data = $request->all();
        $data['address_id'] = $addressId;

        $order = $this->orderService->createOrder($customer, $data);

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat dengan kode: ' . $order->order_code);
    }

    public function show($id)
    {
        $customer = $this->currentCustomer();
        $order = Order::with(['items.service', 'payment', 'employee.user', 'address', 'review'])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        return view('customer.order.show', compact('order'));
    }

    public function cancel($id)
    {
        $customer = $this->currentCustomer();
        $order = Order::where('customer_id', $customer->id)->findOrFail($id);

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan sudah diproses dan tidak dapat dibatalkan.');
        }

        $this->orderService->updateStatus($order, 'cancelled');

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function invoice($id)
    {
        $customer = $this->currentCustomer();

        $order = Order::with(['customer.user', 'items.service', 'payment', 'coupon', 'address'])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))->setPaper('a4');

        return $pdf->download('Invoice-' . $order->order_code . '.pdf');
    }
}
