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
        Tag::insert([
            ['name' => 'baru', 'slug' => 'baru'],
            ['name' => 'best seller', 'slug' => 'best-seller'],
            ['name' => 'limited edition', 'slug' => 'limited-edition'],
            ['name' => 'diskon', 'slug' => 'diskon'],
        ]);

        $elektronik = Category::where('name', 'Elektronik')->first()->id;
        $fashionPria = Category::where('name', 'Fashion Pria')->first()->id;
        $fashionWanita = Category::where('name', 'Fashion Wanita')->first()->id;
        $mamin = Category::where('name', 'Makanan & Minuman')->first()->id;
        $kesehatan = Category::where('name', 'Kesehatan & Kecantikan')->first()->id;
        $olahraga = Category::where('name', 'Olahraga & Outdoor')->first()->id;

        $products = [
            [
                'category_id' => $elektronik, 'name' => 'Smartphone Pro Max', 'slug' => 'smartphone-pro-max', 'sku' => 'PHN-001',
                'price' => 12999000, 'compare_price' => 14999000, 'stock' => 50,
                'short_description' => 'Smartphone flagship dengan kamera 108MP',
                'description' => 'Smartphone dengan prosesor terbaru, RAM 12GB, penyimpanan 256GB, dan kamera utama 108MP. Dilengkapi layar AMOLED 6.7 inci.',
                'is_featured' => true, 'weight' => 250, 'status' => 'published',
            ],
            [
                'category_id' => $elektronik, 'name' => 'Wireless Earbuds Pro', 'slug' => 'wireless-earbuds-pro', 'sku' => 'EBD-001',
                'price' => 1899000, 'compare_price' => 2499000, 'stock' => 100,
                'short_description' => 'Earbuds dengan ANC dan baterai 30 jam',
                'description' => 'Wireless earbuds dengan Active Noise Cancellation, water resistant IPX5, dan total pemutaran hingga 30 jam dengan charging case.',
                'is_featured' => true, 'weight' => 50, 'status' => 'published',
            ],
            [
                'category_id' => $fashionPria, 'name' => 'Kemeja Flanel Premium', 'slug' => 'kemeja-flanel-premium', 'sku' => 'FAS-001',
                'price' => 299000, 'compare_price' => 399000, 'stock' => 75,
                'short_description' => 'Kemeja flanel bahan katun premium nyaman dipakai',
                'description' => 'Kemeja flanel pria dengan bahan katun premium yang nyaman dipakai sehari-hari. Tersedia berbagai ukuran S-XXL.',
                'weight' => 300, 'status' => 'published',
            ],
            [
                'category_id' => $fashionWanita, 'name' => 'Gaun Casual Elegan', 'slug' => 'gaun-casual-elegan', 'sku' => 'FAS-002',
                'price' => 349000, 'stock' => 45,
                'short_description' => 'Gaun casual elegan untuk berbagai acara',
                'description' => 'Gaun casual wanita dengan desain elegan yang cocok untuk berbagai acara. Bahan rayon premium yang adem dan nyaman.',
                'weight' => 250, 'status' => 'published',
            ],
            [
                'category_id' => $mamin, 'name' => 'Kopi Arabika Premium 250gr', 'slug' => 'kopi-arabika-premium', 'sku' => 'MAM-001',
                'price' => 85000, 'compare_price' => 95000, 'stock' => 200,
                'short_description' => 'Kopi arabika pilihan dari dataran tinggi',
                'description' => 'Biji kopi arabika pilihan yang ditanam di dataran tinggi dengan ketinggian 1400-1600 mdpl. Rasa yang kaya dengan acidity yang seimbang.',
                'weight' => 250, 'status' => 'published',
            ],
            [
                'category_id' => $kesehatan, 'name' => 'Sunscreen SPF 50', 'slug' => 'sunscreen-spf-50', 'sku' => 'KES-001',
                'price' => 125000, 'stock' => 150,
                'short_description' => 'Tabir surya dengan SPF 50 melindungi dari UVA/UVB',
                'description' => 'Tabir surya dengan SPF 50 PA+++ yang melindungi kulit dari sinar UVA dan UVB. Formula ringan tidak meninggalkan white cast.',
                'is_featured' => true, 'weight' => 100, 'status' => 'published',
            ],
            [
                'category_id' => $olahraga, 'name' => 'Yoga Mat Premium', 'slug' => 'yoga-mat-premium', 'sku' => 'OLR-001',
                'price' => 450000, 'compare_price' => 550000, 'stock' => 60,
                'short_description' => 'Alas yoga tebal anti-slip dengan ketebalan 6mm',
                'description' => 'Yoga mat premium dengan ketebalan 6mm untuk kenyamanan maksimal. Material TPE ramah lingkungan dengan permukaan anti-slip di kedua sisi.',
                'weight' => 1200, 'status' => 'published',
            ],
            [
                'category_id' => $elektronik, 'name' => 'Smartwatch Sport Edition', 'slug' => 'smartwatch-sport-edition', 'sku' => 'PHN-002',
                'price' => 2499000, 'stock' => 0, 'is_limited_edition' => true,
                'short_description' => 'Smartwatch dengan GPS dan monitor kesehatan',
                'description' => 'Smartwatch sport dengan GPS built-in, monitor detak jantung, SpO2, dan berbagai mode olahraga. Tahan air hingga 50 meter.',
                'is_featured' => true, 'weight' => 80, 'status' => 'published',
            ],
        ];

        $tagIds = Tag::pluck('id')->toArray();

        foreach ($products as $i => $data) {
            $product = Product::create($data);

            if ($i < 4) {
                $product->tags()->attach($tagIds[0]);
            }
            if (in_array($i, [0, 1, 5, 7])) {
                $product->tags()->attach($tagIds[1]);
            }
            if ($i === 7) {
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
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku'        => $skuBase . '-BLK',
                    'color'      => 'Hitam',
                    'color_hex'  => '#000000',
                    'price'      => $data['price'],
                    'stock'      => (int)($data['stock'] / 2),
                ]);
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku'        => $skuBase . '-WHT',
                    'color'      => 'Putih',
                    'color_hex'  => '#FFFFFF',
                    'price'      => $data['price'],
                    'stock'      => (int)($data['stock'] / 2),
                ]);
            }
        }
    }
}
