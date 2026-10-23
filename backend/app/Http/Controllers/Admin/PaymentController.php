<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function index(Request $request)
    {
        $query = Payment::with(['order.customer.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:paid,failed',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? now() : null,
        ]);

        if ($request->status === 'paid' && $payment->order->status === 'pending') {
            $payment->order->update(['status' => 'confirmed']);
        }

        $payment->load('order.customer.user');
        $this->notifications->paymentVerified($payment, $request->status === 'paid');

        return back()->with('success', "Status pembayaran {$payment->payment_code} telah diperbarui.");
    }
}
