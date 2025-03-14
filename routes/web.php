<?php
// routes/web.php

use App\Http\Controllers\ProductDescriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductDescriptionController::class, 'index'])->name('products.index');
Route::post('/products', [ProductDescriptionController::class, 'store'])->name('products.store');
Route::delete('/products/{id}', [ProductDescriptionController::class, 'deleteProduct'])->name('products.delete');
Route::delete('/categories/{id}', [ProductDescriptionController::class, 'deleteCategory'])->name('categories.delete');
Route::delete('/images/{id}', [ProductDescriptionController::class, 'deleteImage'])->name('images.delete');
