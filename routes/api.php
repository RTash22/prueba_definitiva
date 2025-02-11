<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Actualizar un producto (PUT)
//Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::apiResource('products', ProductController::class);

// Eliminar un producto (DELETE)
//::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Mostrar un producto (GET)
//Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');







