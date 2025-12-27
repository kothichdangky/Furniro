<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\PaypalController;

// 💳 Các route xử lý PayPal phải đặt trước
Route::get('/paypal', [PaypalController::class, 'pay'])->name('payment');
Route::get('/success', [PaypalController::class, 'success'])->name('success');
Route::get('/error', [PaypalController::class, 'error'])->name('error');

// 🧾 Bắt tất cả route Vue SPA (đặt cuối cùng)
Route::get('/{any}', function () {
    return File::get(public_path('index.html'));
})->where('any', '.*');
