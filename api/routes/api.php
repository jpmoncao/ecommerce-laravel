<?php

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

// Recursos
Route::post('/login', [UsersController::class, 'login']);

Route::resource('/users', UsersController::class)->middleware(CheckTemporaryUser::class);
Route::resource('/carts', CartsController::class)->middleware(CheckTemporaryUser::class);
Route::resource('/cart-items', CartItemsController::class)->middleware(CheckTemporaryUser::class);

// Relacionamentos
Route::get('/users/{user_id}/cart-items', [UsersController::class, 'cartItems']);


/**
 * GERENCIAMENTO DE PEDIDO
 */

// Recursos
Route::resource('/orders', OrdersController::class);
