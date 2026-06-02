<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'min_order_amount', 'max_discount_amount', 'usage_limit',
        'usage_count', 'per_user_limit', 'is_influencer_code',
        'influencer_user_id', 'is_active', 'starts_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'value'               => 'decimal:2',
            'min_order_amount'    => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'is_influencer_code'  => 'boolean',
            'is_active'           => 'boolean',
            'starts_at'           => 'datetime',
            'expires_at'          => 'datetime',
        ];
    }

    public function usages()
    {
        return $this->hasMany(PromoUsage::class);
    }

    public function influencer()
    {
        return $this->belongsTo(User::class, 'influencer_user_id');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order_amount) return 0;

        $discount = match ($this->type) {
            'percentage'   => $subtotal * ($this->value / 100),
            'fixed_amount' => (float) $this->value,
            'free_shipping'=> 0,
            default        => 0,
        };

        if ($this->max_discount_amount) {
            $discount = min($discount, (float) $this->max_discount_amount);
        }

        return round($discount, 2);
    }

    public function hasUserExceededLimit(int $userId): bool
    {
        return $this->usages()->where('user_id', $userId)->count() >= $this->per_user_limit;
    }
}
