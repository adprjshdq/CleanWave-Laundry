<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'position', 'shift', 'is_available'];

    protected $casts = ['is_available' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getShiftLabelAttribute(): string
    {
        return match($this->shift) {
            'morning'   => 'Pagi (06:00–14:00)',
            'afternoon' => 'Siang (14:00–22:00)',
            'night'     => 'Malam (22:00–06:00)',
            default     => '-',
        };
    }
}
