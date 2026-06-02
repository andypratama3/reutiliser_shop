<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('midtrans_order_id')->unique();
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('payment_type', ['bank_transfer', 'qris', 'gopay', 'ovo', 'dana', 'shopeepay'])->nullable();
            $table->string('payment_channel')->nullable()->comment('BCA, BNI, Mandiri, dll');
            $table->string('va_number')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->enum('status', [
                'pending',
                'capture',
                'settlement',
                'deny',
                'cancel',
                'expire',
                'failure',
                'refund',
            ])->default('pending');
            $table->string('fraud_status')->nullable();
            $table->string('signature_key')->nullable()->comment('SHA-512 untuk verifikasi');
            $table->json('midtrans_response')->nullable()->comment('Raw response Midtrans');
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
