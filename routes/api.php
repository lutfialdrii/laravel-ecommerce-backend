<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// buyer
Route::post('/buyer/register', [AuthController::class, 'registerBuyer']);
