<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// [ seller ]
Route::post('/seller/register', [AuthController::class, 'registerSeller']);
// Category
Route::post('/seller/category', [CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::get('/seller/categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');
// Product
Route::resource('/seller/products', ProductController::class)->middleware('auth:sanctum');
Route::post('/seller/products/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');
// Addresses
Route::resource('buyer/addresses', AddressController::class)->middleware('auth:sanctum');


// [ auth ]
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// [ buyer ]
Route::post('/buyer/register', [AuthController::class, 'registerBuyer']);
