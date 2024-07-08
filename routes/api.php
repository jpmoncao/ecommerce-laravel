<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/**
 * GERENCIAMENTO DE ESTOQUE
 */

// Recursos
Route::resource('/products', ProductController::class);
Route::resource('/variations', ProductVariationsController::class);
// Route::resource('/stocks', ProductStocks::class);
// Route::resource('/entries/stock', ProductStockEntries::class);

// Relacionamentos
Route::get('/products/{product_id}/variations', [ProductController::class, 'variations']);
Route::get('/variations/{variation_id}/product', [ProductVariationsController::class, 'product']);