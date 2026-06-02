<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'midtrans_order_id', 'transaction_id',
        'payment_type', 'payment_channel', 'va_number', 'qr_code_url',
        'gross_amount', 'status', 'fraud_status', 'signature_key',
        'midtrans_response', 'transaction_time', 'settlement_time', 'expires_at',
    ];

    // Use the $casts property so Eloquent will correctly cast attributes.
    protected $casts = [
        'gross_amount'      => 'decimal:2',
        'midtrans_response' => 'array',
        'transaction_time'  => 'datetime',
        'settlement_time'   => 'datetime',
        'expires_at'        => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isSettled(): bool
    {
        return in_array($this->status, ['settlement', 'capture']);
    }

    public function verifySignature(string $serverKey): bool
    {
        $expected = hash('sha512',
            $this->midtrans_order_id .
            $this->status .
            $this->gross_amount .
            $serverKey
        );
        return hash_equals($expected, $this->signature_key ?? '');
    }
}
