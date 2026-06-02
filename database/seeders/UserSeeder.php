<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@shopx.com',
            'phone'    => '081234567890',
            'password' => bcrypt('password'),
            'status'   => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $admin = User::create([
            'name'     => 'Admin ShopX',
            'email'    => 'admin@shopx.com',
            'phone'    => '081234567891',
            'password' => bcrypt('password'),
            'status'   => 'active',
        ]);
        $admin->assignRole('admin');

        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $phone = "08123456789" . ($i + 1);
            $user = User::create([
                'name'     => "Customer $i",
                'email'    => "customer$i@shopx.com",
                'phone'    => $phone,
                'password' => bcrypt('password'),
                'status'   => 'active',
            ]);
            $user->assignRole('user');
            $users[] = $user;
        }

        Address::create([
            'user_id'    => $superadmin->id,
            'label'      => 'Rumah',
            'recipient_name' => 'Super Admin',
            'phone'      => '081234567890',
            'address'    => 'Jl. Contoh No. 123',
            'city'       => 'Jakarta Selatan',
            'province'   => 'DKI Jakarta',
            'postal_code' => '12345',
            'is_default' => true,
        ]);

        foreach ($users as $user) {
            Address::create([
                'user_id'    => $user->id,
                'label'      => 'Rumah',
                'recipient_name' => $user->name,
                'phone'      => $user->phone,
                'address'    => 'Jl. Pelanggan No. ' . $user->id,
                'city'       => 'Jakarta',
                'province'   => 'DKI Jakarta',
                'postal_code' => '1234' . $user->id,
                'is_default' => true,
            ]);
        }
    }
}
