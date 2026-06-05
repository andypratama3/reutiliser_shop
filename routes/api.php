<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Midtrans Webhook (no auth, verified via signature)
Route::post('/webhook/midtrans', [PaymentController::class, 'webhook'])
    ->name('webhook.midtrans');

Route::get('/webhook/midtrans', function () {
    \Illuminate\Support\Facades\Log::info('Webhook GET request received from: ' . request()->ip());
    return response()->json([
        'message' => 'The GET method is not supported for route api/webhook/midtrans. Supported methods: POST.'
    ], 200);
});

// Payment Status Polling
Route::get('/orders/{order}/payment-status', [PaymentController::class, 'status'])
    ->middleware('auth:sanctum')
    ->name('api.payment.status');
