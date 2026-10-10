<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class OrderService
{
    const DELIVERY_FEE = 10000; // Rp 10.000
    const POINTS_PER_RUPIAH = 0.01; // 1% dari total = poin

    /**
     * Buat pesanan baru beserta items-nya.
     */
    public function createOrder(Customer $customer, array $data): Order
    {
        return DB::transaction(function () use ($customer, $data) {
            // Hitung subtotal dari items
            $subtotal = 0;
            $itemsData = [];

            foreach ($data['items'] as $item) {
                $service = Service::findOrFail($item['service_id']);
                $qty     = floatval($item['quantity']);
                $price   = $service->price_per_unit;
                $sub     = round($qty * $price, 2);
                $subtotal += $sub;

                $itemsData[] = [
                    'service_id'     => $service->id,
                    'quantity'       => $qty,
                    'price_per_unit' => $price,
                    'subtotal'       => $sub,
                ];
            }

            // Hitung diskon
            $discount    = 0;
            $couponId    = null;
            if (!empty($data['coupon_code'])) {
                $coupon = Coupon::where('code', $data['coupon_code'])->first();
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                    $coupon->increment('used_count');
                }
            }

            // Ongkir
            $deliveryFee = ($data['pickup_method'] === 'pickup') ? self::DELIVERY_FEE : 0;

            // Total
            $total = $subtotal - $discount + $deliveryFee;

            // Buat Order
            $order = Order::create([
                'customer_id'   => $customer->id,
                'address_id'    => $data['address_id'] ?? null,
                'coupon_id'     => $couponId,
                'order_code'    => Order::generateOrderCode(),
                'status'        => 'pending',
                'pickup_method' => $data['pickup_method'],
                'pickup_date'   => $data['pickup_date'] ?? null,
                'notes'         => $data['notes'] ?? null,
                'subtotal'      => $subtotal,
                'discount'      => $discount,
                'delivery_fee'  => $deliveryFee,
                'total'         => $total,
            ]);

            // Buat Order Items
            $order->items()->createMany($itemsData);

            // Buat Payment record
            Payment::create([
                'order_id'     => $order->id,
                'payment_code' => Payment::generatePaymentCode(),
                'method'       => $data['payment_method'] ?? 'transfer',
                'status'       => 'unpaid',
                'amount'       => $total,
            ]);

            return $order;
        });
    }

    /**
     * Update status pesanan.
     */
    public function updateStatus(Order $order, string $newStatus, ?int $employeeId = null): Order
    {
        $update = ['status' => $newStatus];

        if ($employeeId) {
            $update['employee_id'] = $employeeId;
        }

        if ($newStatus === 'picked_up') {
            $update['pickup_date'] = now();
        }

        if ($newStatus === 'delivered') {
            $update['delivery_date'] = now();
        }

        if ($newStatus === 'completed') {
            // Berikan loyalty points
            $points = (int) floor($order->total * self::POINTS_PER_RUPIAH);
            $order->customer->addLoyaltyPoints($points);
        }

        $order->update($update);
        return $order->fresh();
    }

    /**
     * Kalkulasi ulang total (untuk preview checkout).
     */
    public static function calculatePreview(array $items, ?string $couponCode, string $pickupMethod): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $service   = Service::find($item['service_id']);
            $subtotal += ($service->price_per_unit * floatval($item['quantity']));
        }

        $discount    = 0;
        $couponValid = false;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount    = $coupon->calculateDiscount($subtotal);
                $couponValid = true;
            }
        }

        $deliveryFee = ($pickupMethod === 'pickup') ? self::DELIVERY_FEE : 0;

        return [
            'subtotal'     => $subtotal,
            'discount'     => $discount,
            'delivery_fee' => $deliveryFee,
            'total'        => $subtotal - $discount + $deliveryFee,
            'coupon_valid' => $couponValid,
        ];
    }
}
