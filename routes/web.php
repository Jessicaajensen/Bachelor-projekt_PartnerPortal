<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UploadController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/', function () {
    return view('home');
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/upload', [UploadController::class, 'create'])->name('uploads.create');
    Route::post('/upload', [UploadController::class, 'store'])->name('uploads.store');
});