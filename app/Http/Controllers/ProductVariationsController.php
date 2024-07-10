<?php

namespace App\Http\Controllers;

use App\Models\ProductVariations;
use App\Models\ProductStocks;
use Illuminate\Http\Request;
use App\Http\Utils\ValidatorRequest;

class ProductVariationsController extends Controller
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
        // Validação dos campos de entrada
        $validate = new ValidatorRequest($request, [
            'variation' => 'required|string|max:250',
            'amount' => 'required|numeric',
            'product_id' => 'required|integer',
        ]);

        // Tratamento de erros de validação
        $error = $validate->handleErrors();
        if ($error) {
            return $error;
        }

        // Criar a variação do produto
        $product_variation = ProductVariations::create($request->all());
        ProductStocks::create(["id_stock" => $product_variation->id_product_variation]);

        return response()->json([
            'message' => 'Product variation created successfully!',
            'data' => $product_variation,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $product_variation_id)
    {
        $product_variation = ProductVariations::find($product_variation_id);

        return response()->json([
            'message' => 'Product variation listed successfully!',
            'data' => $product_variation,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $product_variation_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $product_variation_id)
    {
        $product_variation = ProductVariations::find($product_variation_id);

        if (!$product_variation) {
            return response()->json([
                'message' => 'Product variation not found!',
            ], 404);
        }

        $validate = new ValidatorRequest($request, [
            'variant' => 'sometimes|required|string|max:250',
            'amount' => 'sometimes|required|numeric',
            'product_id' => 'sometimes|required|integer',
        ]);

        $error = $validate->handleErrors();
        if ($error) {
            return $error;
        }

        $product_variation->update($request->all());

        return response()->json([
            'message' => 'Product variation updated successfully!',
            'data' => $product_variation,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $product_variation_id)
    {
        $product_variation = ProductVariations::find($product_variation_id);

        if (!$product_variation) {
            return response()->json([
                'message' => 'Product variation not found!',
            ], 404);
        }

        $product_variation->delete();

        return response()->json([
            'message' => 'Product variation deleted successfully!',
        ], 200);
    }

    public function product(string $variation)
    {
        $product_variation = ProductVariations::find($variation);

        if (!$product_variation) {
            return response()->json([
                'message' => 'Product variation not found!',
            ], 404);
        }

        $product_variation->product();
        $product = $product_variation->product;

        return response()->json([
            'message' => 'Product owner of variation listed successfully!',
            'data' => $product,
        ], 200);


    }
    public function stock(string $variation)
    {
        $product_variation = ProductVariations::find($variation);

        if (!$product_variation) {
            return response()->json([
                'message' => 'Product variation not found!',
            ], 404);
        }

        $product_variation->stock();
        $stock = $product_variation->stock;

        return response()->json([
            'message' => 'Stock of variation listed successfully!',
            'data' => $stock,
        ], 200);

    }

    public function stockEntries(string $variation)
    {
        $product_variation = ProductVariations::find($variation);

        if (!$product_variation) {
            return response()->json([
                'message' => 'Product variation not found!',
            ], 404);
        }

        $product_variation->stockEntries();
        $stock_entries = $product_variation->stockEntries;

        return response()->json([
            'message' => 'Stock entries of variation listed successfully!',
            'data' => $stock_entries,
        ], 200);

    }
}
