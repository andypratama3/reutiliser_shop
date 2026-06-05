<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'promo_code_id',
        'recipient_name', 'recipient_phone', 'shipping_address',
        'shipping_city', 'shipping_province', 'shipping_postal_code',
        'shipping_method',
        'subtotal', 'shipping_cost', 'discount_amount', 'total_amount',
        'status', 'payment_method', 'payment_channel', 'notes', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'        => 'decimal:2',
            'shipping_cost'   => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount'    => 'decimal:2',
            'paid_at'         => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function promoUsage()
    {
        return $this->hasOne(PromoUsage::class);
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(now()->format('ymd')) . '-' . str_pad(
            (static::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT
        );
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'awaiting_payment']);
    }
}
