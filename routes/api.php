<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BengkelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::get('/gallery', [DataController::class, 'getGalleryItems']);
Route::get('/sponsors', [DataController::class, 'getSponsorItems']);
Route::get('/products', [DataController::class, 'getProductItems']);
Route::get('/stats', [DataController::class, 'getStats']);
Route::get('/cities-by-province/{province}', [BengkelController::class, 'getCitiesByProvince']);
