<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;

class SimulateCheckoutSingle extends Command
{
    protected $signature = 'simulate:checkout-single
        {--user= : user id or email}
        {--product= : product id}
        {--variant= : product variant id (optional)}
        {--quantity=1 : quantity}
        {--mode=locked : locked|optimistic}
    ';

    protected $description = 'Simulate a single internal checkout attempt (no HTTP)';

    public function handle()
    {
        $userOpt = $this->option('user');
        $productId = (int) $this->option('product');
        $variantId = $this->option('variant') ? (int) $this->option('variant') : null;
        $qty = (int) $this->option('quantity');
        $mode = $this->option('mode') === 'optimistic' ? 'optimistic' : 'locked';

        if (!$userOpt || !$productId) {
            $this->error('Please provide --user and --product');
            return 1;
        }

        $user = is_numeric($userOpt) ? User::find($userOpt) : User::where('email', $userOpt)->first();
        if (!$user) {
            $this->error('User not found');
            return 1;
        }

        $product = Product::find($productId);
        if (!$product) {
            $this->error('Product not found');
            return 1;
        }

        try {
            if ($mode === 'locked') {
                DB::beginTransaction();
                if ($variantId) {
                    $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->first();
                    if (!$variant) throw new \Exception('Variant not found');
                    if ($variant->stock < $qty) throw new \Exception('Not enough variant stock');
                } else {
                    $variant = null;
                }

                $p = Product::where('id', $productId)->lockForUpdate()->first();
                if ($p->stock < $qty) throw new \Exception('Not enough product stock');

                // Decrement
                $p->decrement('stock', $qty);
                if ($variant) $variant->decrement('stock', $qty);

                // Create a minimal order record
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user->id,
                    'recipient_name' => $user->name ?? 'Test',
                    'recipient_phone' => '000',
                    'shipping_address' => 'Simulated',
                    'shipping_city' => 'SimCity',
                    'shipping_province' => 'SimProvince',
                    'shipping_postal_code' => '00000',
                    'subtotal' => 0,
                    'shipping_cost' => 0,
                    'discount_amount' => 0,
                    'total_amount' => 0,
                    'status' => 'awaiting_payment',
                    'payment_method' => 'va_bank',
                    'payment_channel' => 'bca',
                ]);

                DB::commit();
                $this->info('OK order ' . $order->order_number . ' (locked)');
                return 0;
            }

            // optimistic
            DB::beginTransaction();
            $affected = 0;
            if ($variantId) {
                $affected = DB::table('product_variants')
                    ->where('id', $variantId)
                    ->where('stock', '>=', $qty)
                    ->decrement('stock', $qty);
                if (!$affected) throw new \Exception('Optimistic variant update failed');
            }

            $affected = DB::table('products')
                ->where('id', $productId)
                ->where('stock', '>=', $qty)
                ->decrement('stock', $qty);
            if (!$affected) throw new \Exception('Optimistic product update failed');

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'recipient_name' => $user->name ?? 'Test',
                'recipient_phone' => '000',
                'shipping_address' => 'Simulated',
                'shipping_city' => 'SimCity',
                'shipping_province' => 'SimProvince',
                'shipping_postal_code' => '00000',
                'subtotal' => 0,
                'shipping_cost' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'status' => 'awaiting_payment',
                'payment_method' => 'va_bank',
                'payment_channel' => 'bca',
            ]);

            DB::commit();
            $this->info('OK order ' . $order->order_number . ' (optimistic)');
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('FAILED: ' . $e->getMessage());
            return 2;
        }
    }
}
