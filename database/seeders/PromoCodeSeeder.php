<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::create([
            'code'              => 'WELCOME10',
            'name'              => 'Diskon 10% untuk Member Baru',
            'description'       => 'Nikmati diskon 10% untuk pembelian pertama Anda',
            'type'              => 'percentage',
            'value'             => 10,
            'min_order_amount'  => 50000,
            'max_discount_amount' => 50000,
            'usage_limit'       => 100,
            'per_user_limit'    => 1,
            'is_active'         => true,
            'starts_at'         => now()->subDay(),
            'expires_at'        => now()->addDays(30),
        ]);

        PromoCode::create([
            'code'              => 'FLAT50',
            'name'              => 'Diskon Rp 50.000',
            'description'       => 'Potongan harga Rp 50.000 untuk minimal belanja Rp 200.000',
            'type'              => 'fixed_amount',
            'value'             => 50000,
            'min_order_amount'  => 200000,
            'max_discount_amount' => 50000,
            'usage_limit'       => 50,
            'per_user_limit'    => 1,
            'is_active'         => true,
            'starts_at'         => now()->subDay(),
            'expires_at'        => now()->addDays(30),
        ]);

        PromoCode::create([
            'code'              => 'GRATISONGKIR',
            'name'              => 'Gratis Ongkos Kirim',
            'description'       => 'Gratis ongkos kirim untuk semua pembelian',
            'type'              => 'free_shipping',
            'value'             => 0,
            'min_order_amount'  => 0,
            'usage_limit'       => 200,
            'per_user_limit'    => 3,
            'is_active'         => true,
            'starts_at'         => now()->subDay(),
            'expires_at'        => now()->addDays(14),
        ]);

        PromoCode::create([
            'code'              => 'PREMIUM25',
            'name'              => 'Diskon 25% Premium',
            'description'       => 'Diskon 25% untuk pelanggan premium (maks Rp 100.000)',
            'type'              => 'percentage',
            'value'             => 25,
            'min_order_amount'  => 150000,
            'max_discount_amount' => 100000,
            'usage_limit'       => 20,
            'per_user_limit'    => 1,
            'is_active'         => false,
            'starts_at'         => now()->subDay(),
            'expires_at'        => now()->addDays(60),
        ]);

        PromoCode::create([
            'code'              => 'INFLUENCER10',
            'name'              => 'Kode Influencer 10%',
            'description'       => 'Kode diskon dari influencer',
            'type'              => 'percentage',
            'value'             => 10,
            'min_order_amount'  => 0,
            'max_discount_amount' => 200000,
            'usage_limit'       => 500,
            'per_user_limit'    => 1,
            'is_influencer_code' => true,
            'influencer_user_id' => \App\Models\User::first()?->id,
            'is_active'         => true,
            'starts_at'         => now()->subDay(),
            'expires_at'        => now()->addDays(90),
        ]);
    }
}
