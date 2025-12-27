<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->group(function () {
   Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
   //admin
   Route::get('/admin', [AdminController::class, 'admin']);
   Route::post('/shop', [ProductController::class, 'store']);
   Route::delete('/shop/{id}', [ProductController::class, 'destroy']);
   Route::get('/edit/{id}', [ProductController::class,'edit']);
   Route::post('/delete-extra-image', [ProductController::class, 'deleteExtraImage']);
   Route::put('/edit/{id}', [ProductController::class,'update']);
   //cart
   Route::get('/cart', [CartController::class, 'cart']);
   Route::post('/cart', [CartController::class, 'store']);
   Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});

Route::post('/firebase-auth', [AuthController::class, 'firebase']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

//product
Route::get('/shop', [ProductController::class, 'product']);
Route::get('/product', [HomeController::class, 'product']);
Route::get('/product_detail/{id}', [ProductController::class,'product_detail']);
Route::get('/search', [ProductController::class,'search']);

Route::middleware(['web'])->group(function () {

});


