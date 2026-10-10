<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'service_id', 'quantity', 'price_per_unit', 'subtotal'];

    protected $casts = [
        'quantity'      => 'decimal:2',
        'price_per_unit'=> 'decimal:2',
        'subtotal'      => 'decimal:2',
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function service() { return $this->belongsTo(Service::class); }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
