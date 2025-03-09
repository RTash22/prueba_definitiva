<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SaleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Actualizar un producto (PUT)
//Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::apiResource('products', ProductController::class);

// Eliminar un producto (DELETE)
//Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Mostrar un producto (GET)
//Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('/upload-image', [ImageController::class, 'upload']);

// Rutas para el punto de venta
Route::prefix('sales')->group(function () {
    Route::get('/', [SaleController::class, 'index']);
    Route::post('/', [SaleController::class, 'store']);
    Route::get('/{sale}', [SaleController::class, 'show']);
    Route::delete('/{sale}', [SaleController::class, 'destroy']);
    Route::get('/report/by-date', [SaleController::class, 'getSalesByDate']);
    Route::get('/report/top-products', [SaleController::class, 'getTopProducts']);
});
