# PRD & Technical Specification — Complete E-Commerce Platform
## Laravel 12 | Roles: superadmin, admin, user, guest

---

## Table of Contents

1. [Product Overview](#1-product-overview)
2. [Roles & Permissions](#2-roles--permissions)
3. [User Flow (dari Flowchart)](#3-user-flow)
4. [Database Design](#4-database-design)
5. [Migrations](#5-migrations)
6. [Models](#6-models)
7. [Controllers](#7-controllers)
8. [Views (Blade)](#8-views-blade)
9. [Routes](#9-routes)
10. [Middleware](#10-middleware)
11. [Services & Jobs](#11-services--jobs)
12. [API Endpoints](#12-api-endpoints)
13. [Sub-Agents / Parallel Task Breakdown](#13-sub-agents--parallel-task-breakdown)

---

## 1. Product Overview

### Nama Produk
**ShopX** — Platform e-commerce berbasis Laravel 12 dengan dukungan multi-channel (website, Instagram, marketplace), sistem pembayaran via Midtrans (VA Bank, QRIS, dompet digital), notifikasi WhatsApp otomatis, dan manajemen order real-time.

### Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.3 |
| Frontend | Blade + Tailwind CSS + Alpine.js |
| Database | MySQL 8.0 |
| Queue | Redis + Laravel Horizon |
| Payment | Midtrans (Signature Key SHA-512) |
| Notification Email
| Auth | Laravel Sanctum + Spatie Permission |
| Storage | Laravel Storage (S3 / local) |
| Cache | Redis |
| Search | Laravel Scout + MeiliSearch |

### Fitur Utama

- Browse produk (website/Instagram/marketplace)
- Stok management + waitlist limited edition
- Keranjang belanja (ukuran, warna, jumlah)
- Kode promo & influencer discount code
- Checkout + data pengiriman
- Pembayaran VA Bank / QRIS / dompet digital via Midtrans
- Verifikasi pembayaran otomatis (Signature Key SHA-512)
- Notifikasi WhatsApp otomatis (VA/QRIS dikirim, konfirmasi order)
- Status order real-time (PAID → update DB → konfirmasi WA)

---

## 2. Roles & Permissions

### Role Hierarchy

```
superadmin
    └── admin
            └── user (customer terdaftar)
                    └── guest (belum login)
```

### Permission Matrix

| Permission | superadmin | admin | user | guest |
|---|:---:|:---:|:---:|:---:|
| Manage products (CRUD) | ✅ | ✅ | ❌ | ❌ |
| Manage categories | ✅ | ✅ | ❌ | ❌ |
| Manage orders (all) | ✅ | ✅ | ❌ | ❌ |
| View own orders | ✅ | ✅ | ✅ | ❌ |
| Manage users | ✅ | ❌ | ❌ | ❌ |
| Manage roles | ✅ | ❌ | ❌ | ❌ |
| Manage promo codes | ✅ | ✅ | ❌ | ❌ |
| View reports | ✅ | ✅ | ❌ | ❌ |
| Checkout | ✅ | ✅ | ✅ | ❌ |
| Browse products | ✅ | ✅ | ✅ | ✅ |
| Add to cart | ✅ | ✅ | ✅ | ❌ |
| Manage settings | ✅ | ❌ | ❌ | ❌ |
| Manage waitlist | ✅ | ✅ | ❌ | ❌ |
| Export data | ✅ | ✅ | ❌ | ❌ |

---

## 3. User Flow

Berdasarkan flowchart yang diunggah:

```
[Entry: Media sosial / website]
        ↓
[Browse produk: Website / Instagram / Marketplace]
        ↓
[Produk tersedia? Cek stok limited edition]
    ↙ Habis         ↘ Tersedia
[Waitlist/notif]   [Pilih produk & tambah ke keranjang]
                           ↓ (pilih ukuran, warna, jumlah)
                   [Isi data pengiriman: nama, alamat, no. telp]
                           ↓
                   [Punya kode promo / influencer code?]
                       ↙ Tidak     ↘ Ya
                        ↘        [Terapkan diskon]
                         ↘       ↙
                   [Review & konfirmasi order: total + ongkir]
                           ↓
                   [Pilih metode pembayaran: VA / QRIS / dompet digital]
                           ↓
                   [Terima notifikasi VA/QRIS via WhatsApp]
                           ↓
                   [Lakukan pembayaran: mobile banking / dompet digital]
                           ↓
                   [Verifikasi pembayaran: Signature Key SHA-512 via Midtrans]
                       ↙ Gagal          ↘ Berhasil
               [Notifikasi gagal bayar]  [Status order PAID]
               [Coba ulang ↑]            [Update real-time + simpan ke DB]
                                                  ↓
                                   [Konfirmasi via WhatsApp]
                                   [Order diterima – pengiriman diproses]
```

---

## 4. Database Design

### Entity Relationship Diagram (Deskripsi)

```
users ──< orders ──< order_items >── products
  |                      |
  └── addresses     order_items >── product_variants
  └── carts ──< cart_items
  └── waitlists

products >── categories
products ──< product_variants (ukuran, warna)
products ──< product_images
products ──< product_tags >── tags

orders >── payments
orders >── shipments
orders >── promo_codes (via order.promo_code_id)

promo_codes ──< promo_usages

roles >──< permissions (via Spatie)
users >── roles
```

### Tabel Lengkap

| # | Table | Keterangan |
|---|---|---|
| 1 | users | Customer & admin |
| 2 | roles | superadmin, admin, user, guest |
| 3 | model_has_roles | Pivot Spatie |
| 4 | permissions | ACL permissions |
| 5 | model_has_permissions | Pivot Spatie |
| 6 | role_has_permissions | Pivot Spatie |
| 7 | categories | Kategori produk (nested) |
| 8 | products | Master produk |
| 9 | product_variants | Varian (ukuran+warna) |
| 10 | product_images | Galeri gambar produk |
| 11 | tags | Tag produk |
| 12 | product_tags | Pivot produk-tag |
| 13 | addresses | Alamat pengiriman user |
| 14 | carts | Keranjang belanja |
| 15 | cart_items | Item di keranjang |
| 16 | orders | Header order |
| 17 | order_items | Detail item order |
| 18 | payments | Record pembayaran Midtrans |
| 19 | shipments | Data pengiriman |
| 20 | promo_codes | Kode promo & influencer |
| 21 | promo_usages | Riwayat pemakaian promo |
| 22 | waitlists | Antrian produk habis stok |
| 23 | settings | Konfigurasi global toko |
| 24 | notifications | WA/system notifikasi log |
| 25 | activity_logs | Audit trail |

---

## 5. Migrations

### 5.1 users

```php
<?php
// database/migrations/0001_01_01_000000_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### 5.2 categories

```php
<?php
// database/migrations/2024_01_01_000001_create_categories_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

### 5.3 products

```php
<?php
// database/migrations/2024_01_01_000002_create_products_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->decimal('weight', 8, 2)->nullable()->comment('gram');
            $table->boolean('is_limited_edition')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('sold_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->json('meta')->nullable()->comment('SEO meta');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_active']);
            $table->index('category_id');
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### 5.4 product_variants

```php
<?php
// database/migrations/2024_01_01_000003_create_product_variants_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('color_hex', 7)->nullable();
            $table->decimal('price', 12, 2)->nullable()->comment('Override harga jika beda');
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'size', 'color']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
```

### 5.5 product_images

```php
<?php
// database/migrations/2024_01_01_000004_create_product_images_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
```

### 5.6 tags & product_tags

```php
<?php
// database/migrations/2024_01_01_000005_create_tags_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('product_tags', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('tags');
    }
};
```

### 5.7 addresses

```php
<?php
// database/migrations/2024_01_01_000006_create_addresses_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Rumah')->comment('Rumah, Kantor, dll');
            $table->string('recipient_name');
            $table->string('phone', 20);
            $table->text('address');
            $table->string('district')->nullable();
            $table->string('city');
            $table->string('province');
            $table->string('postal_code', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
```

### 5.8 carts & cart_items

```php
<?php
// database/migrations/2024_01_01_000007_create_carts_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2)->comment('Harga saat ditambah ke cart');
            $table->timestamps();

            $table->unique(['cart_id', 'product_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
```

### 5.9 promo_codes

```php
<?php
// database/migrations/2024_01_01_000008_create_promo_codes_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping'])->default('percentage');
            $table->decimal('value', 10, 2)->comment('Nilai diskon (% atau nominal)');
            $table->decimal('min_order_amount', 12, 2)->default(0);
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->integer('usage_limit')->nullable()->comment('NULL = unlimited');
            $table->integer('usage_count')->default(0);
            $table->integer('per_user_limit')->default(1);
            $table->boolean('is_influencer_code')->default(false);
            $table->foreignId('influencer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promo_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('discount_amount', 12, 2);
            $table->timestamps();

            $table->index(['promo_code_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_usages');
        Schema::dropIfExists('promo_codes');
    }
};
```

### 5.10 orders & order_items

```php
<?php
// database/migrations/2024_01_01_000009_create_orders_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('promo_code_id')->nullable()->constrained()->nullOnDelete();

            // Snapshot data pengiriman
            $table->string('recipient_name');
            $table->string('recipient_phone', 20);
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_province');
            $table->string('shipping_postal_code', 10);

            // Harga
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            // Status
            $table->enum('status', [
                'pending',
                'awaiting_payment',
                'paid',
                'processing',
                'shipped',
                'delivered',
                'completed',
                'cancelled',
                'refunded',
            ])->default('pending');

            $table->enum('payment_method', ['va_bank', 'qris', 'e_wallet'])->nullable();
            $table->string('payment_channel')->nullable()->comment('BCA, BNI, Gopay, dll');
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('order_number');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name')->comment('Snapshot nama produk');
            $table->string('variant_info')->nullable()->comment('Snapshot ukuran/warna');
            $table->string('product_image')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
```

### 5.11 payments

```php
<?php
// database/migrations/2024_01_01_000010_create_payments_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('midtrans_order_id')->unique();
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('payment_type', ['bank_transfer', 'qris', 'gopay', 'ovo', 'dana', 'shopeepay'])->nullable();
            $table->string('payment_channel')->nullable()->comment('BCA, BNI, Mandiri, dll');
            $table->string('va_number')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->enum('status', [
                'pending',
                'capture',
                'settlement',
                'deny',
                'cancel',
                'expire',
                'failure',
                'refund',
            ])->default('pending');
            $table->string('fraud_status')->nullable();
            $table->string('signature_key')->nullable()->comment('SHA-512 untuk verifikasi');
            $table->json('midtrans_response')->nullable()->comment('Raw response Midtrans');
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
```

### 5.12 shipments

```php
<?php
// database/migrations/2024_01_01_000011_create_shipments_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('courier')->nullable()->comment('JNE, J&T, SiCepat, dll');
            $table->string('service')->nullable()->comment('REG, OKE, YES, dll');
            $table->string('tracking_number')->nullable();
            $table->enum('status', [
                'preparing',
                'picked_up',
                'in_transit',
                'out_for_delivery',
                'delivered',
                'failed',
                'returned',
            ])->default('preparing');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
```

### 5.13 waitlists

```php
<?php
// database/migrations/2024_01_01_000012_create_waitlists_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'notified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlists');
    }
};
```

### 5.14 notifications

```php
<?php
// database/migrations/2024_01_01_000013_create_notifications_log_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('channel', ['whatsapp', 'email', 'system'])->default('whatsapp');
            $table->string('recipient')->comment('Phone/email tujuan');
            $table->string('type')->comment('payment_reminder, order_confirmed, dll');
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
```

### 5.15 settings

```php
<?php
// database/migrations/2024_01_01_000014_create_settings_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('string')->comment('string, boolean, integer, json');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
```

### 5.16 activity_logs

```php
<?php
// database/migrations/2024_01_01_000015_create_activity_logs_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
```

---

## 6. Models

### 6.1 User

```php
<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'avatar', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    public function promoUsages()
    {
        return $this->hasMany(PromoUsage::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ─── Helpers ──────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['superadmin', 'admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }
}
```

### 6.2 Product

```php
<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'price', 'compare_price', 'cost_price', 'stock', 'low_stock_threshold',
        'weight', 'is_limited_edition', 'is_active', 'is_featured', 'status',
        'sold_count', 'view_count', 'meta',
    ];

    protected $casts = [
        'price'              => 'decimal:2',
        'compare_price'      => 'decimal:2',
        'cost_price'         => 'decimal:2',
        'is_limited_edition' => 'boolean',
        'is_active'          => 'boolean',
        'is_featured'        => 'boolean',
        'meta'               => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLimitedEdition($query)
    {
        return $query->where('is_limited_edition', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) $this->price;
    }

    // Boot
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = strtoupper(Str::random(8));
            }
        });
    }
}
```

### 6.3 Order

```php
<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'promo_code_id',
        'recipient_name', 'recipient_phone', 'shipping_address',
        'shipping_city', 'shipping_province', 'shipping_postal_code',
        'subtotal', 'shipping_cost', 'discount_amount', 'total_amount',
        'status', 'payment_method', 'payment_channel', 'notes', 'paid_at',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'shipping_cost'   => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount'    => 'decimal:2',
        'paid_at'         => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function promoUsage()
    {
        return $this->hasOne(PromoUsage::class);
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ─── Helpers ──────────────────────────────────────────────────

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(now()->format('ymd')) . '-' . str_pad(
            (static::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT
        );
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'awaiting_payment']);
    }
}
```

### 6.4 Payment

```php
<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'midtrans_order_id', 'transaction_id',
        'payment_type', 'payment_channel', 'va_number', 'qr_code_url',
        'gross_amount', 'status', 'fraud_status', 'signature_key',
        'midtrans_response', 'transaction_time', 'settlement_time', 'expires_at',
    ];

    protected $casts = [
        'gross_amount'       => 'decimal:2',
        'midtrans_response'  => 'array',
        'transaction_time'   => 'datetime',
        'settlement_time'    => 'datetime',
        'expires_at'         => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isSettled(): bool
    {
        return in_array($this->status, ['settlement', 'capture']);
    }

    public function verifySignature(string $serverKey): bool
    {
        $expected = hash('sha512',
            $this->midtrans_order_id .
            $this->status_code .
            $this->gross_amount .
            $serverKey
        );
        return hash_equals($expected, $this->signature_key ?? '');
    }
}
```

### 6.5 PromoCode

```php
<?php
// app/Models/PromoCode.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'min_order_amount', 'max_discount_amount', 'usage_limit',
        'usage_count', 'per_user_limit', 'is_influencer_code',
        'influencer_user_id', 'is_active', 'starts_at', 'expires_at',
    ];

    protected $casts = [
        'value'              => 'decimal:2',
        'min_order_amount'   => 'decimal:2',
        'max_discount_amount'=> 'decimal:2',
        'is_influencer_code' => 'boolean',
        'is_active'          => 'boolean',
        'starts_at'          => 'datetime',
        'expires_at'         => 'datetime',
    ];

    public function usages()
    {
        return $this->hasMany(PromoUsage::class);
    }

    public function influencer()
    {
        return $this->belongsTo(User::class, 'influencer_user_id');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order_amount) return 0;

        $discount = match ($this->type) {
            'percentage'   => $subtotal * ($this->value / 100),
            'fixed_amount' => (float) $this->value,
            'free_shipping'=> 0,
            default        => 0,
        };

        if ($this->max_discount_amount) {
            $discount = min($discount, (float) $this->max_discount_amount);
        }

        return round($discount, 2);
    }

    public function hasUserExceededLimit(int $userId): bool
    {
        return $this->usages()->where('user_id', $userId)->count() >= $this->per_user_limit;
    }
}
```

### 6.6 Cart & CartItem

```php
<?php
// app/Models/Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }
}
```

```php
<?php
// app/Models/CartItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'product_variant_id', 'quantity', 'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getLineTotalAttribute(): float
    {
        return (float) $this->price * $this->quantity;
    }
}
```

### 6.7 Waitlist

```php
<?php
// app/Models/Waitlist.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $fillable = [
        'product_id', 'product_variant_id', 'user_id',
        'email', 'phone', 'notified', 'notified_at',
    ];

    protected $casts = [
        'notified'     => 'boolean',
        'notified_at'  => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 7. Controllers

### 7.1 ProductController (Customer-facing)

```php
<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()
            ->with(['primaryImage', 'category'])
            ->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc'    => $query->orderBy('price'),
                'price_desc'   => $query->orderByDesc('price'),
                'newest'       => $query->orderByDesc('created_at'),
                'best_selling' => $query->orderByDesc('sold_count'),
                default        => null,
            };
        }

        $products   = $query->paginate(16)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['images', 'variants', 'tags', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('view_count');

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    public function joinWaitlist(Request $request, Product $product)
    {
        $request->validate([
            'email' => 'required_without:phone|email|nullable',
            'phone' => 'required_without:email|string|nullable',
        ]);

        Waitlist::firstOrCreate([
            'product_id'         => $product->id,
            'product_variant_id' => $request->variant_id,
            'user_id'            => auth()->id(),
        ], [
            'email' => $request->email ?? auth()->user()?->email,
            'phone' => $request->phone ?? auth()->user()?->phone,
        ]);

        return back()->with('success', 'Berhasil masuk waitlist! Kami akan notifikasi kamu.');
    }
}
```

### 7.2 CartController

```php
<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.primaryImage', 'items.variant');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id'         => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity'           => 'required|integer|min:1|max:10',
        ]);

        $product = Product::active()->findOrFail($request->product_id);

        if ($product->isOutOfStock()) {
            return back()->with('error', 'Produk sedang habis stok.');
        }

        $cart  = $this->getOrCreateCart();
        $price = $request->product_variant_id
            ? (ProductVariant::find($request->product_variant_id)?->price ?? $product->price)
            : $product->price;

        $cartItem = $cart->items()->where([
            'product_id'         => $product->id,
            'product_variant_id' => $request->product_variant_id,
        ])->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id'         => $product->id,
                'product_variant_id' => $request->product_variant_id,
                'quantity'           => $request->quantity,
                'price'              => $price,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        $item->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(CartItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    private function getOrCreateCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }
}
```

### 7.3 CheckoutController

```php
<?php
// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PromoCode;
use App\Services\MidtransService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly MidtransService  $midtrans,
        private readonly WhatsAppService  $whatsApp,
    ) {}

    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product', 'items.variant')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $addresses = auth()->user()->addresses;
        return view('checkout.index', compact('cart', 'addresses'));
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $promo = PromoCode::where('code', strtoupper($request->code))->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json(['error' => 'Kode promo tidak valid atau sudah kadaluarsa.'], 422);
        }

        if ($promo->hasUserExceededLimit(auth()->id())) {
            return response()->json(['error' => 'Kamu sudah pernah menggunakan kode ini.'], 422);
        }

        session(['promo_code_id' => $promo->id]);

        return response()->json([
            'success'         => true,
            'message'         => "Diskon {$promo->name} berhasil diterapkan!",
            'promo_code_id'   => $promo->id,
            'discount_type'   => $promo->type,
            'discount_value'  => $promo->value,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name'        => 'required|string|max:100',
            'recipient_phone'       => 'required|string|max:20',
            'shipping_address'      => 'required|string',
            'shipping_city'         => 'required|string|max:100',
            'shipping_province'     => 'required|string|max:100',
            'shipping_postal_code'  => 'required|string|max:10',
            'payment_method'        => 'required|in:va_bank,qris,e_wallet',
            'payment_channel'       => 'required|string',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product', 'items.variant')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $subtotal = $cart->subtotal;
            $promoCode = null;
            $discountAmount = 0;

            if (session('promo_code_id')) {
                $promoCode = PromoCode::find(session('promo_code_id'));
                if ($promoCode?->isValid()) {
                    $discountAmount = $promoCode->calculateDiscount($subtotal);
                }
            }

            $shippingCost = $this->calculateShipping($request->shipping_city);
            $totalAmount  = max(0, $subtotal - $discountAmount + $shippingCost);

            $order = Order::create([
                'order_number'          => Order::generateOrderNumber(),
                'user_id'               => auth()->id(),
                'promo_code_id'         => $promoCode?->id,
                'recipient_name'        => $request->recipient_name,
                'recipient_phone'       => $request->recipient_phone,
                'shipping_address'      => $request->shipping_address,
                'shipping_city'         => $request->shipping_city,
                'shipping_province'     => $request->shipping_province,
                'shipping_postal_code'  => $request->shipping_postal_code,
                'subtotal'              => $subtotal,
                'shipping_cost'         => $shippingCost,
                'discount_amount'       => $discountAmount,
                'total_amount'          => $totalAmount,
                'status'                => 'awaiting_payment',
                'payment_method'        => $request->payment_method,
                'payment_channel'       => $request->payment_channel,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->product->name,
                    'variant_info'       => $item->variant
                        ? "{$item->variant->size} / {$item->variant->color}"
                        : null,
                    'product_image'      => $item->product->primaryImage?->path,
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->price,
                    'total_price'        => $item->line_total,
                ]);

                // Kurangi stok
                $item->product->decrement('stock', $item->quantity);
            }

            if ($promoCode) {
                $promoCode->increment('usage_count');
                \App\Models\PromoUsage::create([
                    'promo_code_id'   => $promoCode->id,
                    'user_id'         => auth()->id(),
                    'order_id'        => $order->id,
                    'discount_amount' => $discountAmount,
                ]);
                session()->forget('promo_code_id');
            }

            // Buat transaksi Midtrans
            $payment = $this->midtrans->createTransaction($order, $request->payment_method, $request->payment_channel);

            // Kirim notifikasi WA
            $this->whatsApp->sendPaymentInstruction($order, $payment);

            $cart->items()->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Checkout failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items', 'payment'])
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    private function calculateShipping(string $city): float
    {
        // Implementasi kalkulasi ongkir (RajaOngkir / flat rate)
        return 15000;
    }
}
```

### 7.4 PaymentController (Midtrans Webhook)

```php
<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsApp) {}

    /**
     * Midtrans Webhook — verifikasi Signature SHA-512
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans webhook received', $payload);

        // Verifikasi signature key
        $serverKey     = config('services.midtrans.server_key');
        $signatureKey  = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        if ($signatureKey !== $payload['signature_key']) {
            Log::warning('Midtrans invalid signature', ['order_id' => $payload['order_id']]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $payload['order_id'])->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = Payment::where('order_id', $order->id)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Update payment status
        $payment->update([
            'transaction_id'   => $payload['transaction_id'] ?? null,
            'status'           => $payload['transaction_status'],
            'fraud_status'     => $payload['fraud_status'] ?? null,
            'settlement_time'  => $payload['settlement_time'] ?? null,
            'midtrans_response'=> $payload,
        ]);

        // Jika berhasil
        $isSettled = in_array($payload['transaction_status'], ['settlement', 'capture'])
            && ($payload['fraud_status'] ?? 'accept') === 'accept';

        if ($isSettled && !$order->isPaid()) {
            $order->markAsPaid();
            $order->update(['status' => 'processing']);
            $order->load('items');

            // Update sold_count produk
            foreach ($order->items as $item) {
                $item->product?->increment('sold_count', $item->quantity);
            }

            // Notifikasi WA order confirmed
            $this->whatsApp->sendOrderConfirmation($order);

            Log::info('Order paid: ' . $order->order_number);
        }

        if (in_array($payload['transaction_status'], ['deny', 'cancel', 'expire', 'failure'])) {
            $order->update(['status' => 'cancelled']);
            $this->whatsApp->sendPaymentFailed($order);
        }

        return response()->json(['message' => 'OK'], 200);
    }

    public function status(Order $order)
    {
        $this->authorize('view', $order);
        return response()->json([
            'order_number' => $order->order_number,
            'status'       => $order->status,
            'paid_at'      => $order->paid_at,
            'payment'      => $order->payment?->only(['status', 'va_number', 'qr_code_url', 'expires_at']),
        ]);
    }
}
```

### 7.5 Admin\ProductController

```php
<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage products');
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(20)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $tags       = Tag::all();
        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'category_id'        => 'required|exists:categories,id',
            'sku'                => 'required|string|unique:products,sku',
            'price'              => 'required|numeric|min:0',
            'compare_price'      => 'nullable|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'description'        => 'nullable|string',
            'short_description'  => 'nullable|string|max:500',
            'status'             => 'required|in:draft,published,archived',
            'is_limited_edition' => 'boolean',
            'is_featured'        => 'boolean',
            'images.*'           => 'image|max:2048',
            'tags'               => 'array',
            'tags.*'             => 'exists:tags,id',
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'path'       => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        if ($request->filled('tags')) {
            $product->tags()->sync($request->tags);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $tags       = Tag::all();
        $product->load(['images', 'variants', 'tags']);
        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'category_id'        => 'required|exists:categories,id',
            'price'              => 'required|numeric|min:0',
            'compare_price'      => 'nullable|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'status'             => 'required|in:draft,published,archived',
            'is_limited_edition' => 'boolean',
            'is_featured'        => 'boolean',
        ]);

        $product->update($validated);

        if ($request->filled('tags')) {
            $product->tags()->sync($request->tags);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk dihapus.');
    }
}
```

### 7.6 Admin\OrderController

```php
<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsApp)
    {
        $this->middleware('permission:manage orders');
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'items'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(25)->withQueryString();

        $stats = [
            'total_today'    => Order::whereDate('created_at', today())->count(),
            'total_pending'  => Order::where('status', 'awaiting_payment')->count(),
            'total_paid'     => Order::where('status', 'paid')->count(),
            'revenue_today'  => Order::paid()->whereDate('paid_at', today())->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'shipment', 'promoCode']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Notifikasi WA jika shipped
        if ($request->status === 'shipped') {
            $request->validate([
                'tracking_number' => 'required|string',
                'courier'         => 'required|string',
            ]);
            $order->shipment()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier'         => $request->courier,
                    'tracking_number' => $request->tracking_number,
                    'status'          => 'in_transit',
                    'shipped_at'      => now(),
                ]
            );
            $this->whatsApp->sendShippingUpdate($order);
        }

        return back()->with('success', 'Status order diperbarui.');
    }
}
```

### 7.7 Admin\UserController

```php
<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage users');
    }

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->filled('role'), fn($q) => $q->role($request->role))
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load(['orders', 'roles']);
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);

        // Superadmin tidak bisa diubah kecuali oleh superadmin
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Tidak ada akses.');
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role user diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);
        return back()->with('success', 'Status user diperbarui.');
    }
}
```

### 7.8 Admin\PromoCodeController

```php
<?php
// app/Http/Controllers/Admin/PromoCodeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage promo codes');
    }

    public function index()
    {
        $promos = PromoCode::withCount('usages')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.promos.index', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'                => 'required|string|unique:promo_codes,code|max:50',
            'name'                => 'required|string|max:100',
            'type'                => 'required|in:percentage,fixed_amount,free_shipping',
            'value'               => 'required|numeric|min:0',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit'         => 'nullable|integer|min:1',
            'per_user_limit'      => 'required|integer|min:1',
            'is_influencer_code'  => 'boolean',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date|after:starts_at',
        ]);

        PromoCode::create(array_merge(
            $request->except('_token'),
            ['code' => strtoupper($request->code)]
        ));

        return redirect()->route('admin.promos.index')
            ->with('success', 'Kode promo berhasil dibuat.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return back()->with('success', 'Kode promo dihapus.');
    }
}
```

---

## 8. Views (Blade)

### 8.1 Layout Utama

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 font-sans antialiased" x-data>
    @include('partials.navbar')

    <main class="min-h-screen">
        @if(session('success'))
            <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
```

### 8.2 Navbar

```blade
{{-- resources/views/partials/navbar.blade.php --}}
<nav class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
        <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">
            {{ config('app.name') }}
        </a>

        <div class="flex items-center gap-6">
            <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">Produk</a>

            @auth
                <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-gray-900">
                    🛒
                    @if(($cartCount = auth()->user()->cart?->total_items ?? 0) > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-gray-700">
                        {{ auth()->user()->name }}
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white shadow rounded-lg py-1">
                        <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Pesanan Saya</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Dashboard Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm">Daftar</a>
            @endauth
        </div>
    </div>
</nav>
```

### 8.3 Product List

```blade
{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Semua Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex gap-8">
        {{-- Sidebar Filter --}}
        <aside class="w-64 flex-shrink-0">
            <h3 class="font-semibold mb-4">Kategori</h3>
            <ul class="space-y-2">
                <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-black">Semua</a></li>
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                           class="text-gray-600 hover:text-black {{ request('category') === $cat->slug ? 'font-semibold text-black' : '' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        {{-- Produk Grid --}}
        <div class="flex-1">
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-500">{{ $products->total() }} produk</p>
                <select onchange="window.location=this.value" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'newest'])) }}">Terbaru</option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}">Harga Terendah</option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}">Harga Tertinggi</option>
                    <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'best_selling'])) }}">Terlaris</option>
                </select>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                            @if($product->primaryImage)
                                <img src="{{ Storage::url($product->primaryImage->path) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">📦</div>
                            @endif
                            @if($product->is_limited_edition)
                                <span class="absolute top-2 left-2 bg-yellow-400 text-xs font-bold px-2 py-0.5 rounded">LIMITED</span>
                            @endif
                            @if($product->discount_percent > 0)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="font-medium text-sm line-clamp-2 text-gray-800">{{ $product->name }}</p>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @if($product->compare_price)
                                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            @if($product->isOutOfStock())
                                <span class="mt-1 text-xs text-red-500 font-medium">Habis</span>
                            @elseif($product->isLowStock())
                                <span class="mt-1 text-xs text-orange-500 font-medium">Stok terbatas</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-16 text-gray-500">
                        Tidak ada produk ditemukan.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        </div>
    </div>
</div>
@endsection
```

### 8.4 Checkout

```blade
{{-- resources/views/checkout/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8" x-data="checkoutApp()">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        {{-- Kiri: Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Data Pengiriman --}}
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="font-semibold text-lg mb-4">Data Pengiriman</h2>

                @if(auth()->user()->addresses->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Pilih Alamat Tersimpan</label>
                        <div class="grid gap-2">
                            @foreach(auth()->user()->addresses as $addr)
                                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="saved_address" value="{{ $addr->id }}"
                                           class="mt-1"
                                           @change="fillAddress({{ $addr->toJson() }})">
                                    <div>
                                        <span class="font-medium">{{ $addr->label }}</span> — {{ $addr->recipient_name }}<br>
                                        <span class="text-sm text-gray-500">{{ $addr->address }}, {{ $addr->city }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="divider text-gray-400 text-sm text-center my-3">atau isi manual</div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Penerima</label>
                        <input type="text" name="recipient_name" x-model="form.recipient_name"
                               class="w-full border rounded-lg px-3 py-2" required>
                        @error('recipient_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">No. Telepon</label>
                        <input type="text" name="recipient_phone" x-model="form.recipient_phone"
                               class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Alamat Lengkap</label>
                        <textarea name="shipping_address" x-model="form.shipping_address"
                                  class="w-full border rounded-lg px-3 py-2" rows="3" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kota</label>
                        <input type="text" name="shipping_city" x-model="form.shipping_city"
                               class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Provinsi</label>
                        <input type="text" name="shipping_province" x-model="form.shipping_province"
                               class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kode Pos</label>
                        <input type="text" name="shipping_postal_code" x-model="form.shipping_postal_code"
                               class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="font-semibold text-lg mb-4">Metode Pembayaran</h2>

                <div class="space-y-3">
                    {{-- VA Bank --}}
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Transfer Virtual Account</p>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['BCA', 'BNI', 'Mandiri', 'BRI', 'Permata'] as $bank)
                                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                       :class="{ 'border-black ring-1 ring-black': form.payment_channel === '{{ $bank }}' }">
                                    <input type="radio" name="payment_channel" value="{{ $bank }}"
                                           x-model="form.payment_channel"
                                           @change="form.payment_method = 'va_bank'" class="hidden">
                                    <span class="text-sm font-medium">{{ $bank }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- QRIS --}}
                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                           :class="{ 'border-black ring-1 ring-black': form.payment_method === 'qris' }">
                        <input type="radio" name="payment_channel" value="qris"
                               x-model="form.payment_channel"
                               @change="form.payment_method = 'qris'" class="hidden">
                        <span class="font-medium">QRIS</span>
                        <span class="text-xs text-gray-500">(GoPay, OVO, Dana, ShopeePay)</span>
                    </label>

                    {{-- E-Wallet --}}
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Dompet Digital</p>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['Gopay', 'OVO', 'Dana', 'ShopeePay'] as $wallet)
                                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                       :class="{ 'border-black ring-1 ring-black': form.payment_channel === '{{ $wallet }}' }">
                                    <input type="radio" name="payment_channel" value="{{ $wallet }}"
                                           x-model="form.payment_channel"
                                           @change="form.payment_method = 'e_wallet'" class="hidden">
                                    <span class="text-sm">{{ $wallet }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <input type="hidden" name="payment_method" x-model="form.payment_method">
            </div>
        </div>

        {{-- Kanan: Ringkasan --}}
        <div class="space-y-4">
            {{-- Kode Promo --}}
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <h3 class="font-semibold mb-3">Kode Promo</h3>
                <div class="flex gap-2">
                    <input type="text" x-model="promoCode" placeholder="Masukkan kode"
                           class="flex-1 border rounded-lg px-3 py-2 text-sm uppercase">
                    <button type="button" @click="applyPromo()"
                            class="bg-black text-white px-4 py-2 rounded-lg text-sm">
                        Pakai
                    </button>
                </div>
                <p x-text="promoMessage" :class="promoSuccess ? 'text-green-600' : 'text-red-500'"
                   class="text-xs mt-2" x-show="promoMessage"></p>
            </div>

            {{-- Ringkasan Order --}}
            <div class="bg-white rounded-xl p-5 shadow-sm sticky top-20">
                <h3 class="font-semibold mb-4">Ringkasan Pesanan</h3>
                <div class="space-y-2 text-sm">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between">
                            <span class="text-gray-600">
                                {{ $item->product->name }}
                                @if($item->variant) <span class="text-xs">({{ $item->variant->size }}/{{ $item->variant->color }})</span> @endif
                                × {{ $item->quantity }}
                            </span>
                            <span>Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <hr class="my-3">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ongkir</span>
                        <span>Rp 15.000</span>
                    </div>
                    <div x-show="discount > 0" class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span>- Rp <span x-text="formatCurrency(discount)"></span></span>
                    </div>
                </div>
                <hr class="my-3">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>Rp <span x-text="formatCurrency(total)"></span></span>
                </div>

                <button type="submit"
                        class="mt-4 w-full bg-black text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition">
                    Pesan Sekarang
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function checkoutApp() {
    return {
        form: {
            recipient_name: '{{ auth()->user()->name }}',
            recipient_phone: '{{ auth()->user()->phone ?? '' }}',
            shipping_address: '',
            shipping_city: '',
            shipping_province: '',
            shipping_postal_code: '',
            payment_method: '',
            payment_channel: '',
        },
        promoCode: '',
        promoMessage: '',
        promoSuccess: false,
        discount: 0,
        subtotal: {{ $cart->subtotal }},
        shippingCost: 15000,
        get total() {
            return Math.max(0, this.subtotal - this.discount + this.shippingCost);
        },
        formatCurrency(val) {
            return new Intl.NumberFormat('id-ID').format(val);
        },
        fillAddress(addr) {
            this.form.recipient_name    = addr.recipient_name;
            this.form.recipient_phone   = addr.phone;
            this.form.shipping_address  = addr.address;
            this.form.shipping_city     = addr.city;
            this.form.shipping_province = addr.province;
            this.form.shipping_postal_code = addr.postal_code;
        },
        async applyPromo() {
            const res = await fetch('{{ route('checkout.apply-promo') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ code: this.promoCode }),
            });
            const data = await res.json();
            if (res.ok) {
                this.promoMessage = data.message;
                this.promoSuccess = true;
                if (data.discount_type === 'percentage') {
                    this.discount = this.subtotal * (data.discount_value / 100);
                } else if (data.discount_type === 'fixed_amount') {
                    this.discount = data.discount_value;
                }
            } else {
                this.promoMessage = data.error;
                this.promoSuccess = false;
            }
        },
    }
}
</script>
@endpush
@endsection
```

### 8.5 Admin Dashboard

```blade
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Pendapatan Hari Ini', 'value' => 'Rp ' . number_format($stats['revenue_today'], 0, ',', '.'), 'icon' => '💰', 'color' => 'bg-green-50'],
            ['label' => 'Order Hari Ini', 'value' => $stats['orders_today'], 'icon' => '📦', 'color' => 'bg-blue-50'],
            ['label' => 'Menunggu Bayar', 'value' => $stats['pending_payment'], 'icon' => '⏳', 'color' => 'bg-yellow-50'],
            ['label' => 'Total Produk', 'value' => $stats['total_products'], 'icon' => '🏪', 'color' => 'bg-purple-50'],
        ] as $stat)
            <div class="bg-white rounded-xl p-5 shadow-sm border {{ $stat['color'] }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">{{ $stat['icon'] }}</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-lg">Order Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-3">Order #</th>
                    <th class="pb-3">Customer</th>
                    <th class="pb-3">Total</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 font-medium hover:underline">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="py-3">{{ $order->user->name }}</td>
                        <td class="py-3 font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="py-3">
                            @php
                                $statusColors = [
                                    'pending'          => 'bg-gray-100 text-gray-700',
                                    'awaiting_payment' => 'bg-yellow-100 text-yellow-700',
                                    'paid'             => 'bg-blue-100 text-blue-700',
                                    'processing'       => 'bg-indigo-100 text-indigo-700',
                                    'shipped'          => 'bg-purple-100 text-purple-700',
                                    'delivered'        => 'bg-green-100 text-green-700',
                                    'completed'        => 'bg-green-200 text-green-800',
                                    'cancelled'        => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

## 9. Routes

```php
<?php
// routes/web.php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────

Route::get('/', fn() => view('home'))->name('home');

Route::prefix('produk')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// ─── Auth ─────────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [Auth\LoginController::class, 'show'])->name('login');
    Route::post('/login', [Auth\LoginController::class, 'store']);
    Route::get('/register', [Auth\RegisterController::class, 'show'])->name('register');
    Route::post('/register', [Auth\RegisterController::class, 'store']);
});

Route::post('/logout', [Auth\LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// ─── Customer (auth) ──────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    // Waitlist
    Route::post('/produk/{product}/waitlist', [ProductController::class, 'joinWaitlist'])->name('products.waitlist');

    // Cart
    Route::prefix('keranjang')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/tambah', [CartController::class, 'add'])->name('add');
        Route::patch('/item/{item}', [CartController::class, 'update'])->name('update');
        Route::delete('/item/{item}', [CartController::class, 'remove'])->name('remove');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/apply-promo', [CheckoutController::class, 'applyPromo'])->name('apply-promo');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
    });

    // Akun
    Route::prefix('akun')->name('account.')->group(function () {
        Route::get('/pesanan', [App\Http\Controllers\Account\OrderController::class, 'index'])->name('orders');
        Route::get('/pesanan/{order}', [App\Http\Controllers\Account\OrderController::class, 'show'])->name('orders.show');
        Route::get('/profil', [App\Http\Controllers\Account\ProfileController::class, 'edit'])->name('profile');
        Route::put('/profil', [App\Http\Controllers\Account\ProfileController::class, 'update'])->name('profile.update');
        Route::resource('alamat', App\Http\Controllers\Account\AddressController::class);
    });
});

// ─── Admin ────────────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin|admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', Admin\ProductController::class);
    Route::resource('categories', Admin\CategoryController::class);
    Route::resource('orders', Admin\OrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::resource('promos', Admin\PromoCodeController::class)->except(['show', 'edit', 'update']);
    Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [Admin\ReportController::class, 'export'])->name('reports.export');

    // Hanya superadmin
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', Admin\UserController::class)->only(['index', 'show']);
        Route::patch('users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
        Route::patch('users/{user}/status', [Admin\UserController::class, 'toggleStatus'])->name('users.status');
        Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
```

```php
<?php
// routes/api.php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Midtrans Webhook (tidak perlu auth, verifikasi via signature)
Route::post('/webhook/midtrans', [PaymentController::class, 'webhook'])
    ->name('webhook.midtrans');

// Payment Status Polling
Route::get('/orders/{order}/payment-status', [PaymentController::class, 'status'])
    ->middleware('auth:sanctum')
    ->name('api.payment.status');
```

---

## 10. Middleware

### 10.1 RoleMiddleware (via Spatie)

```php
<?php
// Sudah di-handle Spatie Permission, register di bootstrap/app.php

// bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### 10.2 EnsureCartOwnership

```php
<?php
// app/Http/Middleware/EnsureCartOwnership.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCartOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $item = $request->route('item');

        if ($item && $item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        return $next($request);
    }
}
```

---

## 11. Services & Jobs

### 11.1 MidtransService

```php
<?php
// app/Services/MidtransService.php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$clientKey    = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction(Order $order, string $paymentMethod, string $paymentChannel): Payment
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'phone'      => $order->recipient_phone,
                'email'      => $order->user->email,
            ],
            'item_details' => $order->items->map(fn($item) => [
                'id'       => $item->product_id,
                'price'    => (int) $item->unit_price,
                'quantity' => $item->quantity,
                'name'     => $item->product_name,
            ])->toArray(),
        ];

        // Sesuaikan payment type
        $params['enabled_payments'] = $this->resolvePaymentChannels($paymentMethod, $paymentChannel);

        $response = Snap::createTransaction($params);

        return Payment::create([
            'order_id'           => $order->id,
            'midtrans_order_id'  => $order->order_number,
            'payment_type'       => $this->mapPaymentType($paymentMethod),
            'payment_channel'    => $paymentChannel,
            'gross_amount'       => $order->total_amount,
            'status'             => 'pending',
            'expires_at'         => now()->addHours(24),
            'midtrans_response'  => (array) $response,
        ]);
    }

    public function verifySignature(array $payload, string $serverKey): bool
    {
        $expected = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );
        return hash_equals($expected, $payload['signature_key'] ?? '');
    }

    private function resolvePaymentChannels(string $method, string $channel): array
    {
        return match ($method) {
            'va_bank'  => ['bca_va', 'bni_va', 'bri_va', 'mandiri_bill', 'permata_va'],
            'qris'     => ['qris'],
            'e_wallet' => ['gopay', 'ovo', 'dana', 'shopeepay'],
            default    => [],
        };
    }

    private function mapPaymentType(string $method): string
    {
        return match ($method) {
            'va_bank'  => 'bank_transfer',
            'qris'     => 'qris',
            'e_wallet' => 'gopay',
            default    => 'bank_transfer',
        };
    }
}
```

### 11.2 WhatsAppService

```php
<?php
// app/Services/WhatsAppService.php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $apiUrl;
    private string $token;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.url', 'https://api.fonnte.com/send');
        $this->token  = config('services.whatsapp.token');
    }

    public function sendPaymentInstruction(Order $order, Payment $payment): void
    {
        $phone = $order->recipient_phone;

        $message = "Halo *{$order->recipient_name}*! 👋\n\n";
        $message .= "Order kamu *#{$order->order_number}* sudah dibuat.\n\n";
        $message .= "💳 *Detail Pembayaran:*\n";

        if ($payment->va_number) {
            $message .= "No. Virtual Account: *{$payment->va_number}*\n";
            $message .= "Bank: *{$payment->payment_channel}*\n";
        } elseif ($payment->qr_code_url) {
            $message .= "Scan QR Code: {$payment->qr_code_url}\n";
        }

        $message .= "Total: *Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n";
        $message .= "Berlaku hingga: *" . $payment->expires_at?->format('d/m/Y H:i') . "*\n\n";
        $message .= "Segera lakukan pembayaran sebelum expired. Terima kasih! 🙏";

        $this->send($phone, $message, $order->id, 'payment_reminder');
    }

    public function sendOrderConfirmation(Order $order): void
    {
        $phone   = $order->recipient_phone;
        $message = "✅ *Pembayaran Diterima!*\n\n";
        $message .= "Halo *{$order->recipient_name}*, pembayaran order *#{$order->order_number}* sudah dikonfirmasi.\n\n";
        $message .= "📦 *Pesananmu sedang diproses* dan akan segera dikirim.\n\n";
        $message .= "Total: *Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n\n";
        $message .= "Terima kasih sudah belanja! 🛍️";

        $this->send($phone, $message, $order->id, 'order_confirmed');
    }

    public function sendPaymentFailed(Order $order): void
    {
        $phone   = $order->recipient_phone;
        $message = "❌ *Pembayaran Gagal*\n\n";
        $message .= "Halo *{$order->recipient_name}*, sayang sekali pembayaran untuk order *#{$order->order_number}* gagal/kadaluarsa.\n\n";
        $message .= "Silakan buat order baru atau hubungi kami jika ada pertanyaan.";

        $this->send($phone, $message, $order->id, 'payment_failed');
    }

    public function sendShippingUpdate(Order $order): void
    {
        $shipment = $order->shipment;
        $phone    = $order->recipient_phone;
        $message  = "🚚 *Pesanan Sedang Dikirim!*\n\n";
        $message  .= "Halo *{$order->recipient_name}*, order *#{$order->order_number}* sudah dikirim.\n\n";
        $message  .= "Kurir: *{$shipment->courier}*\n";
        $message  .= "No. Resi: *{$shipment->tracking_number}*\n\n";
        $message  .= "Cek status pengiriman di website kurir ya! 📍";

        $this->send($phone, $message, $order->id, 'shipping_update');
    }

    private function send(string $phone, string $message, ?int $orderId = null, string $type = 'general'): void
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->post($this->apiUrl, [
                    'target'  => $phone,
                    'message' => $message,
                ]);

            NotificationLog::create([
                'order_id'  => $orderId,
                'channel'   => 'whatsapp',
                'recipient' => $phone,
                'type'      => $type,
                'message'   => $message,
                'status'    => $response->successful() ? 'sent' : 'failed',
                'sent_at'   => now(),
            ]);

        } catch (\Exception $e) {
            Log::error("WhatsApp send failed: {$e->getMessage()}", compact('phone', 'type'));
            NotificationLog::create([
                'order_id'     => $orderId,
                'channel'      => 'whatsapp',
                'recipient'    => $phone,
                'type'         => $type,
                'message'      => $message,
                'status'       => 'failed',
                'error_message'=> $e->getMessage(),
            ]);
        }
    }
}
```

### 11.3 Jobs

```php
<?php
// app/Jobs/SendWaitlistNotification.php

namespace App\Jobs;

use App\Models\Product;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWaitlistNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Product $product) {}

    public function handle(WhatsAppService $whatsApp): void
    {
        $waitlists = $this->product->waitlists()
            ->where('notified', false)
            ->get();

        foreach ($waitlists as $waitlist) {
            $phone = $waitlist->phone ?? $waitlist->user?->phone;
            if (!$phone) continue;

            $message = "🎉 *Kabar Baik!*\n\n";
            $message .= "Produk *{$this->product->name}* yang kamu tunggu sudah tersedia kembali!\n\n";
            $message .= "Segera order sebelum kehabisan: " . route('products.show', $this->product->slug);

            $waitlist->update(['notified' => true, 'notified_at' => now()]);
        }
    }
}
```

---

## 12. API Endpoints

| Method | Endpoint | Auth | Deskripsi |
|---|---|---|---|
| GET | `/produk` | - | Daftar produk |
| GET | `/produk/{slug}` | - | Detail produk |
| POST | `/produk/{product}/waitlist` | auth | Join waitlist |
| GET | `/keranjang` | auth | Lihat keranjang |
| POST | `/keranjang/tambah` | auth | Tambah ke keranjang |
| PATCH | `/keranjang/item/{item}` | auth | Update qty |
| DELETE | `/keranjang/item/{item}` | auth | Hapus item |
| GET | `/checkout` | auth | Halaman checkout |
| POST | `/checkout/apply-promo` | auth | Apply kode promo |
| POST | `/checkout` | auth | Proses order |
| GET | `/checkout/success/{no}` | auth | Halaman sukses |
| GET | `/akun/pesanan` | auth | List order saya |
| GET | `/akun/pesanan/{order}` | auth | Detail order |
| POST | `/api/webhook/midtrans` | sig | Midtrans callback |
| GET | `/api/orders/{order}/payment-status` | sanctum | Cek status bayar |
| GET | `/admin` | admin | Dashboard |
| GET | `/admin/products` | admin | Daftar produk admin |
| POST | `/admin/products` | admin | Buat produk |
| PUT | `/admin/products/{id}` | admin | Edit produk |
| DELETE | `/admin/products/{id}` | admin | Hapus produk |
| GET | `/admin/orders` | admin | Semua order |
| PATCH | `/admin/orders/{order}/status` | admin | Update status order |
| GET | `/admin/users` | superadmin | Daftar user |
| PATCH | `/admin/users/{user}/role` | superadmin | Ganti role |
| GET | `/admin/reports` | admin | Laporan |
| GET | `/admin/settings` | superadmin | Pengaturan |

---

## 13. Sub-Agents / Parallel Task Breakdown

Untuk pengerjaan secara paralel, berikut pembagian task per role/agen:

### Agent A — Backend Core (Database & Models)

```
Tanggung Jawab:
├── Semua migrasi (tabel 1–25)
├── Semua Model Eloquent + Relationships
├── Seeders (RoleSeeder, UserSeeder, ProductSeeder)
├── Factories untuk testing
└── Database indexes & foreign keys

Estimasi: 3–4 hari
```

```php
// database/seeders/RoleSeeder.php
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
```

### Agent B — Payment & Notification

```
Tanggung Jawab:
├── MidtransService (integrasi penuh)
├── PaymentController + Webhook handler
├── WhatsAppService (semua tipe notif)
├── Jobs: SendWaitlistNotification, ProcessPaymentVerification
├── config/services.php (midtrans, whatsapp)
└── Tests: PaymentWebhookTest

Estimasi: 3–4 hari
```

```php
// config/services.php (tambahkan)
'midtrans' => [
    'server_key'   => env('MIDTRANS_SERVER_KEY'),
    'client_key'   => env('MIDTRANS_CLIENT_KEY'),
    'is_production'=> env('MIDTRANS_IS_PRODUCTION', false),
    'snap_url'     => env('MIDTRANS_SNAP_URL', 'https://app.sandbox.midtrans.com/snap/snap.js'),
],
'whatsapp' => [
    'url'   => env('WA_API_URL', 'https://api.fonnte.com/send'),
    'token' => env('WA_TOKEN'),
],
```

### Agent C — Frontend & Views

```
Tanggung Jawab:
├── layouts/app.blade.php, layouts/admin.blade.php
├── partials/ (navbar, footer, sidebar admin)
├── pages/home, products/index, products/show
├── cart/index, checkout/index, checkout/success
├── account/ (orders, profile, addresses)
├── admin/ (dashboard, products CRUD, orders, users, promos)
├── Tailwind CSS config + komponen
└── Alpine.js interaktivitas (cart counter, promo apply, dll)

Estimasi: 4–5 hari
```

### Agent D — Auth & Authorization

```
Tanggung Jawab:
├── LoginController, RegisterController
├── Spatie Permission setup + RoleMiddleware
├── Policies: OrderPolicy, ProductPolicy
├── Email Verification
├── Password Reset
└── Auth views (login, register, forgot-password)

Estimasi: 2 hari
```

```php
// app/Policies/OrderPolicy.php
namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }
}
```

### Agent E — Admin Panel & Reports

```
Tanggung Jawab:
├── Admin Dashboard (stats, charts)
├── Product CRUD admin + image upload
├── Order management + status update
├── User management (superadmin only)
├── Promo code management
├── Report/export (Excel via Laravel Excel)
└── Settings panel

Estimasi: 4 hari
```

```php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue_today'    => Order::paid()->whereDate('paid_at', today())->sum('total_amount'),
            'revenue_month'    => Order::paid()->whereMonth('paid_at', now()->month)->sum('total_amount'),
            'orders_today'     => Order::whereDate('created_at', today())->count(),
            'pending_payment'  => Order::where('status', 'awaiting_payment')->count(),
            'total_products'   => Product::where('status', 'published')->count(),
            'total_customers'  => User::role('user')->count(),
            'low_stock'        => Product::whereRaw('stock <= low_stock_threshold')->count(),
        ];

        $recentOrders = Order::with(['user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $revenueChart = Order::paid()
            ->selectRaw('DATE(paid_at) as date, SUM(total_amount) as total')
            ->where('paid_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'revenueChart'));
    }
}
```

### Ringkasan Timeline Paralel

```
Minggu 1:
  Agent A ─── Migrations + Models + Seeders
  Agent D ─── Auth + Spatie Permission setup

Minggu 2:
  Agent B ─── Midtrans + WhatsApp services
  Agent C ─── Layout + Product pages + Cart views
  Agent E ─── Admin panel structure

Minggu 3:
  Agent A ─── Testing & factories
  Agent B ─── Webhook testing + Job queues
  Agent C ─── Checkout flow + Account pages
  Agent E ─── Dashboard + Reports

Minggu 4:
  Semua ──── Integration testing, bug fix, staging deploy
```

---

## Environment Variables (.env)

```dotenv
APP_NAME="ShopX"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shopx
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Midtrans
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SNAP_URL=https://app.sandbox.midtrans.com/snap/snap.js

# WhatsApp (Fonnte)
WA_API_URL=https://api.fonnte.com/send
WA_TOKEN=your-fonnte-token

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@shopx.id"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Composer & NPM Dependencies

```json
// composer.json (require tambahan)
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^12.0",
    "spatie/laravel-permission": "^6.0",
    "midtrans/midtrans-php": "^2.5",
    "maatwebsite/excel": "^3.1",
    "intervention/image": "^3.0",
    "laravel/horizon": "^5.0",
    "laravel/sanctum": "^4.0"
  }
}
```

```json
// package.json (devDependencies)
{
  "devDependencies": {
    "@tailwindcss/forms": "^0.5",
    "alpinejs": "^3.13",
    "autoprefixer": "^10.4",
    "axios": "^1.6",
    "postcss": "^8.4",
    "tailwindcss": "^3.4",
    "vite": "^5.0",
    "@vitejs/plugin-laravel": "^1.0"
  }
}
```

---

*Dokumen ini adalah PRD teknis lengkap untuk platform e-commerce berbasis Laravel 12 dengan roles: superadmin, admin, user, dan guest — sesuai alur flowchart pembayaran via Midtrans dan notifikasi WhatsApp.*
