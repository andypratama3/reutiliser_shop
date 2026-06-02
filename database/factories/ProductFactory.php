<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => fake()->numberBetween(50000, 500000),
            'stock' => fake()->numberBetween(1, 50),
            'low_stock_threshold' => 5,
            'status' => 'published',
            'is_active' => true,
        ];
    }
}
