<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Tags - use firstOrCreate to avoid duplicates
        $tagData = [
            ['name' => 'baru', 'slug' => 'baru'],
            ['name' => 'best seller', 'slug' => 'best-seller'],
            ['name' => 'limited edition', 'slug' => 'limited-edition'],
            ['name' => 'diskon', 'slug' => 'diskon'],
        ];
        foreach ($tagData as $t) {
            Tag::firstOrCreate(['slug' => $t['slug']], $t);
        }

        $jackets = Category::where('name', 'Jackets')->first()->id;
        $trousers = Category::where('name', 'Trousers')->first()->id;
        $shirts = Category::where('name', 'Shirts')->first()->id;
        $accessories = Category::where('name', 'Accessories')->first()->id;
        $denim = Category::where('name', 'Denim')->first()->id;
        $knitwear = Category::where('name', 'Knitwear')->first()->id;

        $products = [
            [
                'category_id' => $jackets, 'name' => 'Patchwork Archive Jacket', 'material' => 'Upcycled Cotton & Denim',
                'slug' => 'patchwork-archive-jacket', 'sku' => 'JKT-001',
                'price' => 385000, 'compare_price' => 450000, 'stock' => 15,
                'short_description' => 'A singular masterpiece of circular design from 15 unique swatches.',
                'description' => 'Constructed from over 15 unique swatches of archival denim and deadstock cotton. Each panel tells its own history through original fade patterns, repair marks, and textile patina.',
                'is_featured' => true, 'weight' => 800, 'status' => 'published', 'tag' => 'BEST SELLER',
            ],
            [
                'category_id' => $trousers, 'name' => 'Panelled Denim Trouser', 'material' => "Vintage Repurposed Levi's",
                'slug' => 'panelled-denim-trouser', 'sku' => 'TRS-001',
                'price' => 210000, 'compare_price' => null, 'stock' => 20,
                'short_description' => 'Reconstructed from vintage Levi\'s 501s with architectural paneling.',
                'description' => 'Deconstructed and rebuilt from vintage Levi\'s 501s. Architectural paneling creates a sculptural silhouette while preserving original selvedge edges and hardware.',
                'is_featured' => true, 'weight' => 600, 'status' => 'published', 'tag' => 'LIMITED',
            ],
            [
                'category_id' => $shirts, 'name' => 'Bone Linen Overshirt', 'material' => '100% Deadstock Linen',
                'slug' => 'bone-linen-overshirt', 'sku' => 'SHT-001',
                'price' => 175000, 'compare_price' => null, 'stock' => 25,
                'short_description' => 'Cut from premium deadstock Belgian linen.',
                'description' => 'Cut from premium deadstock Belgian linen for a soft, architectural silhouette. Garment-dyed for a natural bone tone that deepens with wear.',
                'is_featured' => false, 'weight' => 400, 'status' => 'published', 'tag' => 'NEW',
            ],
            [
                'category_id' => $jackets, 'name' => 'Archival Forest Blazer', 'material' => 'Vintage Wool Blend',
                'slug' => 'archival-forest-blazer', 'sku' => 'JKT-002',
                'price' => 420000, 'compare_price' => null, 'stock' => 8,
                'short_description' => 'Meticulously tailored from archival wool.',
                'description' => 'Meticulously tailored from archival wool found in a closed Milanese textile mill. Features original weave patterns from the 1970s.',
                'is_featured' => false, 'weight' => 700, 'status' => 'published', 'tag' => 'EXCLUSIVE',
            ],
            [
                'category_id' => $accessories, 'name' => 'Studio Utility Tote', 'material' => 'Reinforced Canvas Scraps',
                'slug' => 'studio-utility-tote', 'sku' => 'ACC-001',
                'price' => 130000, 'compare_price' => null, 'stock' => 40,
                'short_description' => 'A durable tote crafted from workshop remnants.',
                'description' => 'A durable tote crafted from reinforced canvas workshop remnants. Each piece features unique stitching patterns and hardware salvaged from decommissioned industrial equipment.',
                'is_featured' => false, 'weight' => 350, 'status' => 'published', 'tag' => null,
            ],
            [
                'category_id' => $shirts, 'name' => 'Recycled Cotton Knit', 'material' => 'Hand-knit / Circular Yarn',
                'slug' => 'recycled-cotton-knit', 'sku' => 'SHT-002',
                'price' => 155000, 'compare_price' => 185000, 'stock' => 18,
                'short_description' => 'Soft, breathable knit from circular fibers.',
                'description' => 'Soft, breathable knit produced entirely from mechanically recycled cotton fiber. Hand-finished by artisans in our Paris atelier.',
                'is_featured' => false, 'weight' => 350, 'status' => 'published', 'tag' => 'LIMITED',
            ],
            [
                'category_id' => $denim, 'name' => 'Indigo Raw Hem Jeans', 'material' => 'Archival Selvedge Denim',
                'slug' => 'indigo-raw-hem-jeans', 'sku' => 'DEN-001',
                'price' => 280000, 'compare_price' => null, 'stock' => 12,
                'short_description' => 'Raw hem jeans cut from archival selvedge denim rolls.',
                'description' => 'Cut from rare archival selvedge denim rolls sourced from a defunct Japanese mill. Features original indigo dip counts and raw hem finish.',
                'is_featured' => true, 'weight' => 650, 'status' => 'published', 'tag' => null,
            ],
            [
                'category_id' => $knitwear, 'name' => 'Mélange Recycled Sweater', 'material' => 'Reclaimed Wool & Cashmere',
                'slug' => 'melange-recycled-sweater', 'sku' => 'KNT-001',
                'price' => 245000, 'compare_price' => 295000, 'stock' => 10,
                'short_description' => 'A rich mélange sweater from reclaimed fibers.',
                'description' => 'A rich mélange sweater blended from reclaimed wool and cashmere discards. Each garment is individually color-matched and hand-finished.',
                'is_featured' => false, 'weight' => 500, 'status' => 'published', 'tag' => 'EXCLUSIVE',
            ],
        ];

        $tagIds = Tag::pluck('id')->toArray();

        foreach ($products as $i => $data) {
            $tag = $data['tag'] ?? null;
            unset($data['tag']);

            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                $data
            );

            // Only attach tags and create images/variants if product was just created
            if ($product->wasRecentlyCreated) {
                if ($i < 4) {
                    $product->tags()->attach($tagIds[0]);
                }
                if (in_array($i, [0, 1, 5])) {
                    $product->tags()->attach($tagIds[1]);
                }
                if (in_array($i, [7])) {
                    $product->tags()->attach($tagIds[2]);
                }
                if ($data['compare_price'] ?? false) {
                    $product->tags()->attach($tagIds[3]);
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => "https://placehold.co/600x600/e2e8f0/64748b?text=" . urlencode($data['name']),
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);

                if (in_array($i, [0, 1, 2])) {
                    $skuBase = $data['sku'];
                    ProductVariant::firstOrCreate(
                        ['sku' => $skuBase . '-BLK'],
                        [
                            'product_id' => $product->id,
                            'color'      => 'Hitam',
                            'color_hex'  => '#000000',
                            'price'      => $data['price'],
                            'stock'      => (int)($data['stock'] / 2),
                        ]
                    );
                    ProductVariant::firstOrCreate(
                        ['sku' => $skuBase . '-WHT'],
                        [
                            'product_id' => $product->id,
                            'color'      => 'Putih',
                            'color_hex'  => '#FFFFFF',
                            'price'      => $data['price'],
                            'stock'      => (int)($data['stock'] / 2),
                        ]
                    );
                }
            }
        }
    }
}
