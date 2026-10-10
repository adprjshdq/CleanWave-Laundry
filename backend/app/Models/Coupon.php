<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'description', 'discount_type', 'discount_value',
        'min_order', 'max_usage', 'used_count', 'expired_at', 'is_active',
    ];

    protected $casts = [
        'expired_at'     => 'datetime',
        'discount_value' => 'decimal:2',
        'min_order'      => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(float $orderTotal): bool
    {
        return $this->is_active
            && $this->used_count < $this->max_usage
            && $this->expired_at->isFuture()
            && $orderTotal >= $this->min_order;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percentage') {
            return round($subtotal * ($this->discount_value / 100), 2);
        }
        return min($this->discount_value, $subtotal);
    }

    public function getDiscountLabelAttribute(): string
    {
        return $this->discount_type === 'percentage'
            ? $this->discount_value . '%'
            : 'Rp ' . number_format($this->discount_value, 0, ',', '.');
    }
}
