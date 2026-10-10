<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'payment_code', 'method', 'status',
        'amount', 'proof_image', 'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2',
    ];

    public function order() { return $this->belongsTo(Order::class); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'unpaid'               => 'Belum Bayar',
            'pending_verification' => 'Menunggu Verifikasi',
            'paid'                 => 'Lunas',
            'failed'               => 'Gagal',
            'refunded'             => 'Dikembalikan',
            default                => ucfirst($this->status),
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'cash'     => 'Tunai',
            'transfer' => 'Transfer Bank',
            'ewallet'  => 'E-Wallet',
            'qris'     => 'QRIS',
            default    => ucfirst($this->method),
        };
    }

    public function getProofImageUrlAttribute(): ?string
    {
        return $this->proof_image ? asset('storage/' . $this->proof_image) : null;
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public static function generatePaymentCode(): string
    {
        return 'PAY-' . strtoupper(uniqid());
    }
}
