<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'store_name', 'value' => 'ShopX', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_tagline', 'value' => 'Belanja Mudah, Harga Terbaik', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_email', 'value' => 'hello@shopx.com', 'group' => 'general', 'type' => 'email'],
            ['key' => 'store_phone', 'value' => '021-12345678', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_address', 'value' => 'Jl. ShopX No. 1, Jakarta', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'currency', 'value' => 'IDR', 'group' => 'general', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '11', 'group' => 'general', 'type' => 'number'],
            ['key' => 'shipping_free_threshold', 'value' => '100000', 'group' => 'shipping', 'type' => 'number'],
            ['key' => 'shipping_flat_rate', 'value' => '15000', 'group' => 'shipping', 'type' => 'number'],
            ['key' => 'whatsapp_notifications', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'order_confirmation_template', 'value' => 'Hai {name}, pesanan #{order_id} telah diterima! Total: Rp {total}', 'group' => 'notifications', 'type' => 'textarea'],
            ['key' => 'payment_confirmation_template', 'value' => 'Hai {name}, pembayaran untuk pesanan #{order_id} telah dikonfirmasi!', 'group' => 'notifications', 'type' => 'textarea'],
            ['key' => 'shipping_update_template', 'value' => 'Hai {name}, pesanan #{order_id} telah dikirim! No. resi: {tracking_number}', 'group' => 'notifications', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
