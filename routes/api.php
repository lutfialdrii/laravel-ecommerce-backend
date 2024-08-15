<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//  [ Both ]
Route::get('/orders/{id}', [OrderController::class, 'getOrderById'])->middleware('auth:sanctum');


// [ seller ]
Route::post('/seller/register', [AuthController::class, 'registerSeller']);
// Category
Route::post('/seller/category', [CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::get('/seller/categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');
// Product
Route::apiResource('/seller/products', ProductController::class)->middleware('auth:sanctum');
Route::post('/seller/products/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');
// Order
// Update-resi
Route::put('/seller/orders/{id}/update-resi', [OrderController::class, 'updateShippingNumber'])->middleware('auth:sanctum');
Route::get('/seller/orders', [OrderController::class, 'historyOrderSeller'])->middleware('auth:sanctum');


// [ auth ]
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// [ buyer ]
Route::post('/buyer/register', [AuthController::class, 'registerBuyer']);
// Addresses
Route::apiResource('buyer/addresses', AddressController::class)->middleware('auth:sanctum');
// Order
Route::post('/buyer/orders', [OrderController::class, 'createOrder'])->middleware('auth:sanctum');
Route::get('/buyer/histories', [OrderController::class, 'historyOrderBuyer'])->middleware('auth:sanctum');
Route::get('/buyer/orders/{id}/status', [OrderController::class, 'checkOrderStatus'])->middleware('auth:sanctum');
// Stores
Route::get('/buyer/stores', [StoreController::class, 'index'])->middleware('auth:sanctum');
// Products by ID Store
Route::get('/buyer/stores/{id}/products', [StoreController::class, 'productByStore'])->middleware('auth:sanctum');
Route::get('/buyer/stores/livestreaming', [StoreController::class, 'livestreaming'])->middleware('auth:sanctum');

// [ Midtrans ]
Route::post('/midtrans/callback', [CallbackController::class, 'callback']);
