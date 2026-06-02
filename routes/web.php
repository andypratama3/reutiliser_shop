<?php

use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/welcome', function () {
    return view('welcome');
});

// Landing Pages
Route::get('/', [LandingController::class, 'home']);
Route::get('/about', [LandingController::class, 'about']);
Route::get('/shop', [LandingController::class, 'shop']);
Route::get('/product/{id}', [LandingController::class, 'product']);
Route::get('/search', [LandingController::class, 'search']);
Route::get('/wishlist', [LandingController::class, 'wishlist']);
Route::get('/journal', [LandingController::class, 'journal']);
Route::get('/journal/{slug}', [LandingController::class, 'journalSingle']);
Route::get('/lookbook', [LandingController::class, 'lookbook']);
Route::get('/faq', [LandingController::class, 'faq']);
Route::get('/contact', [LandingController::class, 'contact']);
Route::get('/sustainability', [LandingController::class, 'sustainability']);
Route::get('/checkout', [LandingController::class, 'checkout']);
Route::get('/success', [LandingController::class, 'success']);
Route::get('/legal/{type}', [LandingController::class, 'legal']);


 Route::middleware('guest')->group(function () {
     Route::get('/login', [LoginController::class, 'show'])->name('login');
     Route::post('/login', [LoginController::class, 'store']);
     Route::get('/register', [RegisterController::class, 'show'])->name('register');
     Route::post('/register', [RegisterController::class, 'store']);
 });
 Route::post('/logout', [LoginController::class, 'destroy'])
     ->middleware('auth')
     ->name('logout');
 // Customer Products
 Route::get('/products', [ProductController::class, 'index'])->name('products.index');
 Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
 // Cart
 Route::middleware('auth')->group(function () {
     Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
     Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
     Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
     Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');
 });
 // Checkout
 Route::middleware('auth')->group(function () {
     Route::get('/checkout/info', [CheckoutController::class, 'index'])->name('checkout.index');
     Route::post('/checkout/promo', [CheckoutController::class, 'applyPromo'])->name('checkout.promo');
     Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
     Route::get('/checkout/{orderNumber}/success', [CheckoutController::class, 'success'])->name('checkout.success');
 });
 // Account
 Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
     Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders.index');
     Route::get('/orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
     Route::get('/alamat', [AddressController::class, 'index'])->name('alamat.index');
     Route::get('/alamat/create', [AddressController::class, 'create'])->name('alamat.create');
     Route::post('/alamat', [AddressController::class, 'store'])->name('alamat.store');
     Route::get('/alamat/{alamat}/edit', [AddressController::class, 'edit'])->name('alamat.edit');
     Route::put('/alamat/{alamat}', [AddressController::class, 'update'])->name('alamat.update');
     Route::delete('/alamat/{alamat}', [AddressController::class, 'destroy'])->name('alamat.destroy');
 });
 // Admin
 Route::middleware(['auth', 'role:superadmin|admin'])->prefix('admin')->name('admin.')->group(function () {
         Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
         Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
         Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
         Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
         Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
         Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
         Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

         Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
         Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
         Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
         Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
         Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
         Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
         Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
         Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
         Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
         Route::get('/users', [UserController::class, 'index'])->name('users.index');
         Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
         Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
         Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
         Route::get('/promos', [PromoCodeController::class, 'index'])->name('promos.index');
         Route::post('/promos', [PromoCodeController::class, 'store'])->name('promos.store');
         Route::delete('/promos/{promoCode}', [PromoCodeController::class, 'destroy'])->name('promos.destroy');
         Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
         Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
         Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
         Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
     });

