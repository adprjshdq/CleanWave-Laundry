<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'employee_id', 'address_id', 'coupon_id',
        'order_code', 'status', 'pickup_method', 'pickup_date',
        'delivery_date', 'notes', 'subtotal', 'discount', 'delivery_fee', 'total',
    ];

    protected $casts = [
        'pickup_date'   => 'datetime',
        'delivery_date' => 'datetime',
        'subtotal'      => 'decimal:2',
        'discount'      => 'decimal:2',
        'delivery_fee'  => 'decimal:2',
        'total'         => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING     = 'pending';
    const STATUS_CONFIRMED   = 'confirmed';
    const STATUS_PICKED_UP   = 'picked_up';
    const STATUS_PROCESSING  = 'processing';
    const STATUS_READY       = 'ready';
    const STATUS_ON_DELIVERY = 'on_delivery';
    const STATUS_DELIVERED   = 'delivered';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_CANCELLED   = 'cancelled';

    // ─── Relationships ───────────────────────────────────────────
    public function customer()    { return $this->belongsTo(Customer::class); }
    public function employee()    { return $this->belongsTo(Employee::class); }
    public function address()     { return $this->belongsTo(Address::class); }
    public function coupon()      { return $this->belongsTo(Coupon::class); }
    public function items()       { return $this->hasMany(OrderItem::class); }
    public function payment()     { return $this->hasOne(Payment::class); }
    public function review()      { return $this->hasOne(Review::class); }

    // ─── Status Helpers ──────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'Menunggu Konfirmasi',
            'confirmed'   => 'Dikonfirmasi',
            'picked_up'   => 'Sudah Diambil',
            'processing'  => 'Sedang Diproses',
            'ready'       => 'Siap Dikirim',
            'on_delivery' => 'Dalam Pengiriman',
            'delivered'   => 'Telah Dikirim',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'warning',
            'confirmed'   => 'info',
            'picked_up'   => 'primary',
            'processing'  => 'primary',
            'ready'       => 'success',
            'on_delivery' => 'info',
            'delivered'   => 'success',
            'completed'   => 'success',
            'cancelled'   => 'danger',
            default       => 'secondary',
        };
    }

    public function getStatusStepsAttribute(): array
    {
        $all = [
            'pending', 'confirmed', 'picked_up',
            'processing', 'ready', 'on_delivery', 'completed',
        ];
        $current = array_search($this->status, $all);
        return array_map(fn($s, $i) => [
            'key'    => $s,
            'label'  => (new self(['status' => $s]))->status_label,
            'done'   => $i <= $current,
            'active' => $i === $current,
        ], $all, array_keys($all));
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    // ─── Code Generator ─────────────────────────────────────────
    public static function generateOrderCode(): string
    {
        $date    = now()->format('Ymd');
        $lastSeq = self::whereDate('created_at', today())->count() + 1;
        return sprintf('CW-%s-%04d', $date, $lastSeq);
    }

    public function isPaid(): bool
    {
        return $this->payment && $this->payment->status === 'paid';
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' && !$this->review;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }
}
