<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Jackets', 'description' => 'Archival and reconstructed outerwear', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Trousers', 'description' => 'Upcycled and paneled bottoms', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Shirts', 'description' => 'Deadstock linen and cotton tops', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Accessories', 'description' => 'Bags, totes, and small goods', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Denim', 'description' => 'Repurposed and reconstructed denim', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Knitwear', 'description' => 'Circular knit and hand-knit pieces', 'sort_order' => 6, 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            $cat['slug'] = str()->slug($cat['name']);
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
