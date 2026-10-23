<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function uploadProof(Request $request, $orderId)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $customer = $user->customer;
        $order = Order::where('customer_id', $customer->id)->findOrFail($orderId);

        $payment = $order->payment;
        if (!$payment) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $path = $request->file('proof_image')->store('payment-proofs', 'public');

        $payment->update([
            'proof_image' => $path,
            'status' => 'pending_verification',
        ]);

        $payment->load('order.customer.user');
        $this->notifications->paymentProofUploaded($payment);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }
}
