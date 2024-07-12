<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Carts;
use App\Models\Orders;
use App\Models\ProductStocks;
use App\Models\ProductVariations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Utils\ValidatorRequest;

class CartsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = new ValidatorRequest($request, [
            'product_variation_id' => 'required|string',
            'quantity' => 'required|numeric|min:1',
        ]);

        $error = $validate->handleErrors();
        if ($error) {
            return $error;
        }

        if (!Gate::allows('access-cart', $request->user()->id_user)) {
            return response()->json([
                'message' => 'Cart not authorized!',
            ], 403);
        }

        $product_variation = ProductVariations::find($request->product_variation_id);
        if (!$product_variation) {
            return response()->json([
                'message' => 'Product variation not found!',
            ], 404);
        }

        $product_stock = $product_variation->stock;
        if ($product_stock->quantity < $request->quantity) {
            return response()->json([
                'message' => 'Product stock is insufficient!',
            ], 402);
        }

        $cart = Carts::where('id_cart', $request->user()->id_user)
            ->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found or does not belong to the user!',
            ], 404);
        }

        $cart_item = CartItems::create([
            'cart_id' => $request->user()->id_user,
            'product_variation_id' => $request->product_variation_id,
            'quantity' => $request->quantity,
        ]);

        $cart = Carts::with('items')->get()->whereNull('cart_items.order_id');

        $product_stock = ProductStocks::find($cart_item->product_variation_id);
        if (!$product_stock) {
            return response()->json([
                'message' => 'Product stock not found!',
                'data' => [],
            ], 404);
        }
        $product_stock->update(['quantity' => $product_stock->quantity - $cart_item->quantity]);

        return response()->json([
            'message' => 'Product added to cart successfully!',
            'data' => $cart,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_cart)
    {
        if (!Gate::allows('access-cart', $id_cart)) {
            return response()->json([
                'message' => 'Cart not authorized!',
            ], 403);
        }

        $cart = Carts::get()->where('id_cart', '=', $id_cart);

        return response()->json([
            'message' => 'Cart listed successfully!',
            'data' => $cart
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carts $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carts $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carts $cart)
    {
        //
    }
}
