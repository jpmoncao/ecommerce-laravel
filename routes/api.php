<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationsController;
use App\Http\Controllers\ProductStockEntriesController;
use App\Http\Controllers\ProductStocksController;
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
Route::resource('/entries/stock', ProductStockEntriesController::class);

// Relacionamentos
Route::get('/products/{product_id}/variations', [ProductController::class, 'variations']);
Route::get('/variations/{variation_id}/product', [ProductVariationsController::class, 'product']);
Route::get('/variations/{variation_id}/stock', [ProductVariationsController::class, 'stock']);
Route::get('/variations/{variation_id}/entries/stock', [ProductVariationsController::class, 'stockEntries']);