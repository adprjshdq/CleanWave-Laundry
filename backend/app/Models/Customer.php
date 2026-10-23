<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'loyalty_points', 'membership_level'];

    // ─── Relationships ───────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────
    public function addLoyaltyPoints(int $points): void
    {
        $this->increment('loyalty_points', $points);
        $this->refresh();
        $this->updateMembershipLevel();
    }

    public function redeemLoyaltyPoints(int $points): void
    {
        if ($points <= 0) {
            return;
        }

        $this->decrement('loyalty_points', min($points, $this->loyalty_points));
        $this->refresh();
        $this->updateMembershipLevel();
    }

    private function updateMembershipLevel(): void
    {
        $level = match(true) {
            $this->loyalty_points >= 10000 => 'platinum',
            $this->loyalty_points >= 5000  => 'gold',
            $this->loyalty_points >= 1000  => 'silver',
            default                         => 'bronze',
        };
        $this->update(['membership_level' => $level]);
    }

    public function getMembershipBadgeColorAttribute(): string
    {
        return match($this->membership_level) {
            'platinum' => '#E5E7EB',
            'gold'     => '#F59E0B',
            'silver'   => '#9CA3AF',
            default    => '#92400E',
        };
    }

    public function getMembershipLabelAttribute(): string
    {
        return match($this->membership_level) {
            'platinum' => 'Platinum',
            'gold'     => 'Gold',
            'silver'   => 'Silver',
            default    => 'Bronze',
        };
    }

    public function getNextTierAttribute(): ?array
    {
        $tiers = [
            'silver'   => 1000,
            'gold'     => 5000,
            'platinum' => 10000,
        ];

        foreach ($tiers as $name => $threshold) {
            if ($this->loyalty_points < $threshold) {
                return ['name' => ucfirst($name), 'points' => $threshold];
            }
        }

        return null;
    }

    public function getMembershipProgressAttribute(): int
    {
        $thresholds = [
            'bronze'   => 0,
            'silver'   => 1000,
            'gold'     => 5000,
            'platinum' => 10000,
        ];

        $current = $thresholds[$this->membership_level] ?? 0;
        $next = $this->next_tier['points'] ?? null;

        if (! $next || $next <= $current) {
            return 100;
        }

        return (int) round((($this->loyalty_points - $current) / ($next - $current)) * 100);
    }
}
