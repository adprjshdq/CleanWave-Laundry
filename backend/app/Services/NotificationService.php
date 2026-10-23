<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

class NotificationService
{
    public function send(User $user, string $title, string $message, string $type = 'system'): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function notifyAdmins(string $title, string $message, string $type = 'system'): void
    {
        User::where('role', 'admin')
            ->get()
            ->each(fn (User $admin) => $this->send($admin, $title, $message, $type));
    }

    public function newOrder(Order $order): void
    {
        $customerUser = $order->customer?->user;

        if ($customerUser) {
            $this->send(
                $customerUser,
                'Pesanan Berhasil Dibuat',
                "Pesanan {$order->order_code} berhasil dibuat dengan total {$order->formatted_total}. Kami akan segera memprosesnya.",
                'order'
            );
        }

        $this->notifyAdmins(
            'Pesanan Baru Masuk',
            "Pesanan baru {$order->order_code} dari " . ($customerUser->name ?? 'pelanggan') . " senilai {$order->formatted_total}.",
            'order'
        );
    }

    public function orderStatusChanged(Order $order, string $oldStatus, string $newStatus): void
    {
        if ($oldStatus === $newStatus) {
            return;
        }

        $customerUser = $order->customer?->user;

        if (! $customerUser) {
            return;
        }

        $message = "Status pesanan {$order->order_code} diperbarui menjadi: {$order->status_label}.";

        if ($newStatus === 'completed') {
            $earned = (int) floor($order->total * \App\Services\OrderService::POINTS_PER_RUPIAH);
            $message .= " Terima kasih! Anda mendapatkan {$earned} poin loyalitas.";
        }

        if ($newStatus === 'cancelled') {
            $message = "Pesanan {$order->order_code} telah dibatalkan.";
        }

        $this->send($customerUser, 'Status Pesanan Diperbarui', $message, 'order');
    }

    public function paymentVerified(Payment $payment, bool $isPaid): void
    {
        $customerUser = $payment->order?->customer?->user;

        if (! $customerUser) {
            return;
        }

        if ($isPaid) {
            $this->send(
                $customerUser,
                'Pembayaran Diverifikasi',
                "Pembayaran {$payment->payment_code} sebesar {$payment->formatted_amount} untuk pesanan {$payment->order->order_code} telah lunas.",
                'payment'
            );

            return;
        }

        $this->send(
            $customerUser,
            'Pembayaran Ditolak',
            "Pembayaran {$payment->payment_code} untuk pesanan {$payment->order->order_code} ditolak. Silakan unggah ulang bukti pembayaran yang valid.",
            'payment'
        );
    }

    public function paymentProofUploaded(Payment $payment): void
    {
        $customerName = $payment->order?->customer?->user?->name ?? 'Pelanggan';

        $this->notifyAdmins(
            'Bukti Pembayaran Baru',
            "{$customerName} mengunggah bukti pembayaran untuk pesanan {$payment->order->order_code}. Menunggu verifikasi.",
            'payment'
        );
    }
}
