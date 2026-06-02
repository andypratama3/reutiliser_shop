<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage products', 'manage categories', 'manage orders',
            'manage users', 'manage roles', 'manage promo codes',
            'view reports', 'manage settings', 'manage waitlist', 'export data',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'superadmin' => $permissions,
            'admin'      => [
                'manage products', 'manage categories', 'manage orders',
                'manage promo codes', 'view reports', 'manage waitlist', 'export data',
            ],
            'user'       => [],
            'guest'      => [],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePerms);
        }
    }
}
