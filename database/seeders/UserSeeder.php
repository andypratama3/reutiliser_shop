<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@shopx.com'],
            [
                'name'     => 'Super Admin',
                'phone'    => '081234567890',
                'password' => bcrypt('password'),
                'status'   => 'active',
            ]
        );
        if (!$superadmin->hasRole('superadmin')) {
            $superadmin->assignRole('superadmin');
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@shopx.com'],
            [
                'name'     => 'Admin ShopX',
                'phone'    => '081234567891',
                'password' => bcrypt('password'),
                'status'   => 'active',
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $phone = "08123456789" . ($i + 1);
            $user = User::firstOrCreate(
                ['email' => "customer$i@shopx.com"],
                [
                    'name'     => "Customer $i",
                    'phone'    => $phone,
                    'password' => bcrypt('password'),
                    'status'   => 'active',
                ]
            );
            if (!$user->hasRole('user')) {
                $user->assignRole('user');
            }
            $users[] = $user;
        }

        Address::firstOrCreate(
            ['user_id' => $superadmin->id, 'label' => 'Rumah'],
            [
                'recipient_name' => 'Super Admin',
                'phone'      => '081234567890',
                'address'    => 'Jl. Contoh No. 123',
                'city'       => 'Jakarta Selatan',
                'province'   => 'DKI Jakarta',
                'postal_code' => '12345',
                'is_default' => true,
            ]
        );

        foreach ($users as $user) {
            Address::firstOrCreate(
                ['user_id' => $user->id, 'label' => 'Rumah'],
                [
                    'recipient_name' => $user->name,
                    'phone'      => $user->phone,
                    'address'    => 'Jl. Pelanggan No. ' . $user->id,
                    'city'       => 'Jakarta',
                    'province'   => 'DKI Jakarta',
                    'postal_code' => '1234' . $user->id,
                    'is_default' => true,
                ]
            );
        }
    }
}
