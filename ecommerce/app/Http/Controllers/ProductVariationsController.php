<?php

namespace App\Http\Controllers;

use App\Models\ProductVariations;
use App\Models\ProductStocks;
use Illuminate\Http\Request;
use App\Http\Utils\ValidatorRequest;

class ProductVariationsController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $product_variation_id)
    {
        // Obtém a variação pelo id
        $product_variation = ProductVariations::find($product_variation_id);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_variation)
            return response()->json(['message' => 'Product variation not found!'], 404);

        // Retorna a variação com uma mensagem de sucesso
        return response()->json([
            'message' => 'Product variation listed successfully!',
            'data' => $product_variation,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cria objeto de validação dos dados
        $validate = new ValidatorRequest($request, [
            'variation' => 'required|string|max:250',
            'amount' => 'required|numeric',
            'product_id' => 'required|integer',
        ]);

        // Valida se os dados batem com o objeto, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error) {
            return $error;
        }

        // Cria a variação do produto
        $product_variation = ProductVariations::create($request->all());

        // Cria estoque da variação
        ProductStocks::create(["id_stock" => $product_variation->id_product_variation]);

        // Retorna variação do produto com mensagem de sucesso
        return response()->json([
            'message' => 'Product variation created successfully!',
            'data' => $product_variation,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $product_variation_id)
    {
        // Cria objeto de validação
        $validate = new ValidatorRequest($request, [
            'variant' => 'sometimes|required|string|max:250',
            'amount' => 'sometimes|required|numeric',
            'product_id' => 'sometimes|required|integer',
        ]);

        // Valida se os dados enviados batem com o objeto, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        // Obtém a variação pelo id
        $product_variation = ProductVariations::find($product_variation_id);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_variation)
            return response()->json(['message' => 'Product variation not found!'], 404);

        // Atualiza a variação
        $product_variation->update($request->all());

        // Retorna a variação com mensagem de sucesso
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
        // Obtém a variação pelo id
        $product_variation = ProductVariations::find($product_variation_id);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_variation)
            return response()->json(['message' => 'Product variation not found!'], 404);

        // Apaga a variação
        $product_variation->delete();

        // Retorna mensagem de sucesso
        return response()->json(['message' => 'Product variation deleted successfully!'], 200);
    }

    public function product(string $variation)
    {
        // Obtém a variação pelo id
        $product_variation = ProductVariations::find($variation);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_variation)
            return response()->json(['message' => 'Product variation not found!'], 404);

        // Vincula variação com o produto
        $product_variation->product();

        // Obtém o produto da variação
        $product = $product_variation->product;

        // Retorna produto com mensagem de sucesso
        return response()->json([
            'message' => 'Product owner of variation listed successfully!',
            'data' => $product,
        ], 200);
    }

    public function stock(string $variation)
    {
        // Obtém a variação pelo id
        $product_variation = ProductVariations::find($variation);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_variation)
            return response()->json(['message' => 'Product variation not found!'], 404);

        // Vincula variação com o estoque
        $product_variation->stock();

        // Obtém o estoque
        $stock = $product_variation->stock;

        // Retorna o estoque da variação com mensagem de sucesso
        return response()->json([
            'message' => 'Stock of variation listed successfully!',
            'data' => $stock,
        ], 200);
    }

    public function stockEntries(string $variation)
    {
        // Obtém a variação pelo id
        $product_variation = ProductVariations::find($variation);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_variation)
            return response()->json(['message' => 'Product variation not found!'], 404);

        // Vincula variação com as entradas de estoque
        $product_variation->stockEntries();

        // Obtém as entradas de estoque
        $stock_entries = $product_variation->stockEntries;

        // Retorna as entradas de estoque da variação com mensagem de sucesso
        return response()->json([
            'message' => 'Stock entries of variation listed successfully!',
            'data' => $stock_entries,
        ], 200);
    }
}
