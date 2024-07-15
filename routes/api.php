<?php

use App\Http\Controllers\CartsController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductVariationsController;
use App\Http\Controllers\ProductStockEntriesController;
use App\Http\Controllers\UsersController;

use App\Http\Middleware\CheckTemporaryUser;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/**
 * GERENCIAMENTO DE ESTOQUE
 */

// Recursos
Route::resource('/products', ProductsController::class);
Route::resource('/variations', ProductVariationsController::class);
Route::resource('/entries/stock', ProductStockEntriesController::class);

// Relacionamentos
Route::get('/products/{product_id}/variations', [ProductsController::class, 'variations']);
Route::get('/variations/{variation_id}/product', [ProductVariationsController::class, 'product']);
Route::get('/variations/{variation_id}/stock', [ProductVariationsController::class, 'stock']);
Route::get('/variations/{variation_id}/entries/stock', [ProductVariationsController::class, 'stockEntries']);

/**
 * GERENCIAMENTO DE COMPRA
 */
Route::resource('/users', UsersController::class);
Route::resource('/carts', CartsController::class)->middleware(CheckTemporaryUser::class);
Route::resource('/cart-items', CartItemsController::class)->middleware(CheckTemporaryUser::class);