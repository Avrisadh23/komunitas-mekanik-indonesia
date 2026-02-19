<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BengkelController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home Routes - No Cache
Route::middleware(['no.cache'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
    Route::get('/komunitas', [HomeController::class, 'komunitas'])->name('komunitas');
    Route::get('/bengkel', [HomeController::class, 'bengkel'])->name('bengkel');
});

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Dashboard Routes (Protected)
Route::middleware(['auth.admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// API Routes for Dynamic Data (Public)
Route::get('/api/stats', [DataController::class, 'getStats'])->name('api.stats');
Route::get('/api/gallery', [DataController::class, 'getGalleryItems'])->name('api.gallery');
Route::get('/api/sponsors', [DataController::class, 'getSponsorItems'])->name('api.sponsors');
Route::get('/api/products', [DataController::class, 'getProductItems'])->name('api.products');
Route::get('/api/bengkel', [DataController::class, 'getBengkelData'])->name('api.bengkel');
Route::get('/api/cities-by-province/{province}', [BengkelController::class, 'getCitiesByProvince'])->name('api.cities-by-province');

// API Routes for Admin CRUD (Protected) - Use /admin/api/ prefix to avoid conflicts with public /api/ routes
Route::middleware(['auth.admin'])->group(function () {
    // Gallery API
    Route::apiResource('admin/api/galleries', GalleryController::class);
    Route::post('admin/api/galleries/order/update', [GalleryController::class, 'updateOrder']);
    Route::patch('admin/api/galleries/{id}/toggle-active', [GalleryController::class, 'toggleActive']);

    // Sponsor API
    Route::apiResource('admin/api/sponsors', SponsorController::class);
    Route::post('admin/api/sponsors/order/update', [SponsorController::class, 'updateOrder']);
    Route::patch('admin/api/sponsors/{id}/toggle-active', [SponsorController::class, 'toggleActive']);

    // Product API
    Route::apiResource('admin/api/products', ProductController::class);
    Route::post('admin/api/products/order/update', [ProductController::class, 'updateOrder']);
    Route::patch('admin/api/products/{id}/toggle-active', [ProductController::class, 'toggleActive']);

    // Bengkel API
    Route::apiResource('admin/api/bengkels', BengkelController::class);
    Route::patch('admin/api/bengkels/{id}/toggle-active', [BengkelController::class, 'toggleActive']);
    Route::get('admin/api/bengkels/by-province/{province}', [BengkelController::class, 'byProvince']);
    Route::get('admin/api/provinces', [BengkelController::class, 'getProvinces']);
});
