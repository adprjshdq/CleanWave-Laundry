<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $customer = $user->customer;
        $order = Order::where('customer_id', $customer->id)->findOrFail($orderId);

        if (!$order->canBeReviewed()) {
            return back()->with('error', 'Pesanan ini tidak dapat diulas atau sudah pernah diulas.');
        }

        Review::create([
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan penilaian Anda!');
    }
}
