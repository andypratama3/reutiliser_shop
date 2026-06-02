<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Gadget dan perangkat elektronik', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Fashion Pria', 'description' => 'Pakaian dan aksesoris pria', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Fashion Wanita', 'description' => 'Pakaian dan aksesoris wanita', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Makanan & Minuman', 'description' => 'Produk makanan dan minuman', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Kesehatan & Kecantikan', 'description' => 'Produk kesehatan dan perawatan tubuh', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Olahraga & Outdoor', 'description' => 'Perlengkapan olahraga dan aktivitas outdoor', 'sort_order' => 6, 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            $cat['slug'] = str()->slug($cat['name']);
            Category::create($cat);
        }
    }
}
