<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Midtrans Webhook (no auth, verified via signature)
Route::post('/webhook/midtrans', [PaymentController::class, 'webhook'])
    ->name('webhook.midtrans');

// Payment Status Polling
Route::get('/orders/{order}/payment-status', [PaymentController::class, 'status'])
    ->middleware('auth:sanctum')
    ->name('api.payment.status');
