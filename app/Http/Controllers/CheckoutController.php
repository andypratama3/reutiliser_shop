<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\PromoUsage;
use App\Services\MidtransService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly MidtransService  $midtrans,
        private readonly WhatsAppService  $whatsApp,
    ) {}

    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product.primaryImage', 'items.variant')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $addresses = auth()->user()->addresses;
        
        $shippingMethodsSetting = \App\Models\Setting::where('key', 'shipping_methods')->first();
        $shippingMethods = $shippingMethodsSetting ? json_decode($shippingMethodsSetting->value, true) : [];

        return view('checkout.index', compact('cart', 'addresses', 'shippingMethods'));
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $promo = PromoCode::where('code', strtoupper($request->code))->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json(['error' => 'Kode promo tidak valid atau sudah kadaluarsa.'], 422);
        }

        if ($promo->hasUserExceededLimit(auth()->id())) {
            return response()->json(['error' => 'Kamu sudah pernah menggunakan kode ini.'], 422);
        }

        session(['promo_code_id' => $promo->id]);

        return response()->json([
            'success'         => true,
            'message'         => "Diskon {$promo->name} berhasil diterapkan!",
            'promo_code_id'   => $promo->id,
            'discount_type'   => $promo->type,
            'discount_value'  => $promo->value,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name'        => 'required|string|max:100',
            'recipient_phone'       => 'required|string|max:20',
            'shipping_address'      => 'required|string',
            'shipping_city'         => 'required|string|max:100',
            'shipping_province'     => 'required|string|max:100',
            'shipping_postal_code'  => 'required|string|max:10',
            'shipping_method'       => 'required|string',
            'payment_method'        => 'required|in:va_bank,qris,e_wallet',
            'payment_channel'       => 'required|string',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product.primaryImage', 'items.variant')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();
        try {
            $subtotal = $cart->subtotal;
            $promoCode = null;
            $discountAmount = 0;

            if (session('promo_code_id')) {
                $promoCode = PromoCode::find(session('promo_code_id'));
                if ($promoCode?->isValid()) {
                    $discountAmount = $promoCode->calculateDiscount($subtotal);
                }
            }

            // Calculate shipping from dynamic methods but prioritize city-based zones
            $shippingCost = $this->calculateShipping($request->shipping_city);
            
            // Add premium for express methods
            if (str_contains(strtolower($request->shipping_method), 'express')) {
                $shippingCost += 10000;
            }

            $totalAmount  = max(0, $subtotal - $discountAmount + $shippingCost);

            $order = Order::create([
                'order_number'          => Order::generateOrderNumber(),
                'user_id'               => auth()->id(),
                'promo_code_id'         => $promoCode?->id,
                'recipient_name'        => $request->recipient_name,
                'recipient_phone'       => $request->recipient_phone,
                'shipping_address'      => $request->shipping_address,
                'shipping_city'         => $request->shipping_city,
                'shipping_province'     => $request->shipping_province,
                'shipping_postal_code'  => $request->shipping_postal_code,
                'subtotal'              => $subtotal,
                'shipping_cost'         => $shippingCost,
                'discount_amount'       => $discountAmount,
                'total_amount'          => $totalAmount,
                'status'                => 'awaiting_payment',
                'payment_method'        => $request->payment_method,
                'payment_channel'       => $request->payment_channel,
                'shipping_method'       => $request->shipping_method,
            ]);

            // Lock product and variant rows to avoid overselling in concurrent checkouts
            foreach ($cart->items as $item) {
                // If a variant exists, lock it for update
                $variant = null;
                if ($item->product_variant_id) {
                    $variant = ProductVariant::where('id', $item->product_variant_id)->lockForUpdate()->first();
                    if (!$variant) {
                        throw new \Exception('Varian produk tidak ditemukan.');
                    }
                    if ($variant->stock < $item->quantity) {
                        throw new \Exception("Stok varian untuk " . ($item->product?->name ?? 'Unknown') . " tidak cukup.");
                    }
                }

                // Lock product row as well
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stok produk untuk {$product->name} tidak cukup.");
                }

                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $product->name,
                    'variant_info'       => $variant
                        ? "{$variant->size} / {$variant->color}"
                        : null,
                    'product_image'      => $product->primaryImage?->path,
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->price,
                    'total_price'        => $item->line_total,
                ]);

                // decrement locked rows
                $product->decrement('stock', $item->quantity);
                if ($variant) {
                    $variant->decrement('stock', $item->quantity);
                }
            }

            if ($promoCode) {
                $promoCode->increment('usage_count');
                PromoUsage::create([
                    'promo_code_id'   => $promoCode->id,
                    'user_id'         => auth()->id(),
                    'order_id'        => $order->id,
                    'discount_amount' => $discountAmount,
                ]);
                session()->forget('promo_code_id');
            }

            $payment = $this->createPayment($order, $request->payment_method, $request->payment_channel);

            $this->whatsApp->sendPaymentInstruction($order, $payment);

            $cart->items()->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'snap_token' => $payment->midtrans_response['token'] ?? null,
                    'order_number' => $order->order_number,
                    'redirect_url' => route('checkout.success', $order->order_number),
                ]);
            }

            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Checkout failed: ' . $e->getMessage());

            // If the exception indicates stock issues, return a validation-like error to the user
            $msg = $e->getMessage();
            if (str_contains(strtolower($msg), 'stok')) {
                return back()->withErrors(['stock' => $msg])->withInput();
            }

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items', 'payment'])
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    private function createPayment(Order $order, string $paymentMethod, string $paymentChannel): Payment
    {
        // prefer new config/midtrans.php values with fallback to services.midtrans
        $serverKey = config('midtrans.server_key') ?: config('services.midtrans.server_key');
        if (!$serverKey) {
            return Payment::create([
                'order_id'           => $order->id,
                'midtrans_order_id'  => $order->order_number,
                'payment_type'       => $this->mapPaymentType($paymentMethod),
                'payment_channel'    => $paymentChannel,
                'gross_amount'       => $order->total_amount,
                'status'             => 'pending',
                'expires_at'         => now()->addHours(24),
            ]);
        }

        return $this->midtrans->createTransaction($order, $paymentMethod, $paymentChannel);
    }

    private function mapPaymentType(string $method): string
    {
        return match ($method) {
            'va_bank'  => 'bank_transfer',
            'qris'     => 'qris',
            'e_wallet' => 'gopay',
            default    => 'bank_transfer',
        };
    }

    private function calculateShipping(string $city): float
    {
        $cityLower = strtolower(trim($city));
        $zones = config('shipping.zones', []);
        foreach ($zones as $key => $cost) {
            if (str_contains($cityLower, $key)) {
                return (float) $cost;
            }
        }
        return (float) config('shipping.default_cost', 20000);
    }

    

    /**
     * Alternative optimistic update-based checkout.
     * Attempts to decrement stock using a single UPDATE ... WHERE stock >= :qty
     * This demonstrates an alternative to row-level locking.
     * Note: kept separate for review; does not replace store().
     */
    public function storeOptimistic(Request $request)
    {
        $validated = $request->validate([
            'recipient_name'        => 'required|string|max:100',
            'recipient_phone'       => 'required|string|max:20',
            'shipping_address'      => 'required|string',
            'shipping_city'         => 'required|string|max:100',
            'shipping_province'     => 'required|string|max:100',
            'shipping_postal_code'  => 'required|string|max:10',
            'payment_method'        => 'required|in:va_bank,qris,e_wallet',
            'payment_channel'       => 'required|string',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product', 'items.variant')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // We'll attempt to build and run optimistic updates per item
        DB::beginTransaction();
        try {
            $subtotal = $cart->subtotal;
            $promoCode = null;
            $discountAmount = 0;

            if (session('promo_code_id')) {
                $promoCode = PromoCode::find(session('promo_code_id'));
                if ($promoCode?->isValid()) {
                    $discountAmount = $promoCode->calculateDiscount($subtotal);
                }
            }

            $shippingCost = $this->calculateShipping($request->shipping_city);
            $totalAmount  = max(0, $subtotal - $discountAmount + $shippingCost);

            $order = Order::create([
                'order_number'          => Order::generateOrderNumber(),
                'user_id'               => auth()->id(),
                'promo_code_id'         => $promoCode?->id,
                'recipient_name'        => $request->recipient_name,
                'recipient_phone'       => $request->recipient_phone,
                'shipping_address'      => $request->shipping_address,
                'shipping_city'         => $request->shipping_city,
                'shipping_province'     => $request->shipping_province,
                'shipping_postal_code'  => $request->shipping_postal_code,
                'subtotal'              => $subtotal,
                'shipping_cost'         => $shippingCost,
                'discount_amount'       => $discountAmount,
                'total_amount'          => $totalAmount,
                'status'                => 'awaiting_payment',
                'payment_method'        => $request->payment_method,
                'payment_channel'       => $request->payment_channel,
            ]);

            foreach ($cart->items as $item) {
                $qty = (int)$item->quantity;

                // If variant exists, attempt optimistic update on variant first
                if ($item->product_variant_id) {
                    $affected = DB::table('product_variants')
                        ->where('id', $item->product_variant_id)
                        ->where('stock', '>=', $qty)
                        ->update(['stock' => DB::raw("stock - $qty")]);

                    if ($affected === 0) {
                        throw new \Exception("Stok varian untuk " . ($item->product?->name ?? 'Unknown') . " tidak cukup (optimistic).");
                    }
                }

                // Attempt optimistic update on product
                $affected = DB::table('products')
                    ->where('id', $item->product_id)
                    ->where('stock', '>=', $qty)
                    ->update(['stock' => DB::raw("stock - $qty")]);

                if ($affected === 0) {
                    throw new \Exception("Stok produk untuk " . ($item->product?->name ?? 'Unknown') . " tidak cukup (optimistic).");
                }

                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->product?->name,
                    'variant_info'       => $item->variant ? "{$item->variant->size} / {$item->variant->color}" : null,
                    'product_image'      => $item->product?->primaryImage?->path,
                    'quantity'           => $qty,
                    'unit_price'         => $item->price,
                    'total_price'        => $item->line_total,
                ]);
            }

            if ($promoCode) {
                $promoCode->increment('usage_count');
                PromoUsage::create([
                    'promo_code_id'   => $promoCode->id,
                    'user_id'         => auth()->id(),
                    'order_id'        => $order->id,
                    'discount_amount' => $discountAmount,
                ]);
                session()->forget('promo_code_id');
            }

            $payment = $this->createPayment($order, $request->payment_method, $request->payment_channel);
            $this->whatsApp->sendPaymentInstruction($order, $payment);

            $cart->items()->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Checkout optimistic failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains(strtolower($msg), 'stok')) {
                return back()->withErrors(['stock' => $msg])->withInput();
            }
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
