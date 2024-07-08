<?php

namespace App\Http\Controllers;

use App\Models\ProductStockEntries;
use App\Models\ProductVariations;
use App\Models\ProductStocks;
use Illuminate\Http\Request;
use App\Http\Utils\ValidatorRequest;

class ProductStockEntriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products_stock_entries = ProductStockEntries::get();

        return response()->json([
            'message' => 'Products stock entries listeds successfully!',
            'data' => $products_stock_entries,
        ], 201);
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
            'product_variation_id' => 'required|string|max:36',
            'quantity' => 'required|numeric',
            'observation' => 'nullable|string|max:255', // Adicionado nullable para o campo observation
        ]);

        $error = $validate->handleErrors();
        if ($error) {
            return $error;
        }

        $product_variation = ProductVariations::find($request->product_variation_id);
        if (!$product_variation) {
            return response()->json([
                'message' => 'Product variation not found!',
                'data' => [],
            ], 404);
        }

        $product_stock = ProductStocks::find($product_variation->id_product_variation);
        if (!$product_stock) {
            return response()->json([
                'message' => 'Product stock not found!',
                'data' => [],
            ], 404);
        }

        $product_stock_entry = ProductStockEntries::create($request->all());
        $product_stock->update(['quantity' => $product_stock->quantity + $request->quantity]);

        return response()->json([
            'message' => 'Product stock entry created successfully!',
            'data' => $product_stock_entry,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductStockEntries $productStockEntries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductStockEntries $productStockEntries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductStockEntries $productStockEntries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductStockEntries $productStockEntries)
    {
        //
    }
}
