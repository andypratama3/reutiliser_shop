<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
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
        return view('checkout.index', compact('cart', 'addresses'));
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
        $request->validate([
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
                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->product->name,
                    'variant_info'       => $item->variant
                        ? "{$item->variant->size} / {$item->variant->color}"
                        : null,
                    'product_image'      => $item->product->primaryImage?->path,
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->price,
                    'total_price'        => $item->line_total,
                ]);

                $item->product->decrement('stock', $item->quantity);
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

            $payment = $this->midtrans->createTransaction($order, $request->payment_method, $request->payment_channel);

            $this->whatsApp->sendPaymentInstruction($order, $payment);

            $cart->items()->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Checkout failed: ' . $e->getMessage());
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

    private function calculateShipping(string $city): float
    {
        return 15000;
    }
}
