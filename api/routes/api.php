<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductVariationsController;
use App\Http\Controllers\ProductStockEntriesController;
use App\Http\Controllers\UsersController;

use App\Http\Middleware\CheckTemporaryUser;

use Illuminate\Support\Facades\Route;

/**
 * GERENCIAMENTO DE ESTOQUE
 */

// Relacionamentos
Route::get('/products/variations', [ProductsController::class, 'productsWithVariations']);
Route::get('/products/{product_id}/variations', [ProductsController::class, 'variations']);
Route::get('/variations/{variation_id}/product', [ProductVariationsController::class, 'product']);
Route::get('/variations/{variation_id}/stock', [ProductVariationsController::class, 'stock']);
Route::get('/variations/{variation_id}/entries/stock', [ProductVariationsController::class, 'stockEntries']);

// Recursos
Route::resource('/products', ProductsController::class);
Route::resource('/variations', ProductVariationsController::class);
Route::resource('/entries/stock', ProductStockEntriesController::class);

Route::post('/entries/stock/batch', [ProductStockEntriesController::class, 'storeMultiple']);


/**
 * GERENCIAMENTO DE COMPRA
 */

// Recursos
Route::post('/guest', [AuthController::class, 'guest']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/users', UsersController::class);
    Route::resource('/carts', CartsController::class);
    Route::resource('/cart-items', CartItemsController::class);
});

// Relacionamentos
Route::get('/users/{user_id}/cart-items', [UsersController::class, 'cartItems']);


/**
 * GERENCIAMENTO DE PEDIDO
 */

// Recursos
Route::resource('/orders', OrdersController::class);
