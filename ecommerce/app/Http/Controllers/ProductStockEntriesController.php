<?php

namespace App\Http\Controllers;

use App\Models\ProductStockEntries;
use App\Models\ProductVariations;
use App\Models\ProductStocks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cria objeto de validação dos dados
        $validate = new ValidatorRequest($request, [
            'product_variation_id' => 'required|string|max:36',
            'quantity' => 'required|numeric',
            'observation' => 'nullable|string|max:255',
        ]);

        // Valida se os dados enviados batem com o objeto, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        // Inicia transação
        DB::beginTransaction();
        try {
            // Obtém variação do produto pelo id
            $product_variation = ProductVariations::find($request->product_variation_id);

            // Caso não encontre, dispara mensagem de não encontrado
            if (!$product_variation)
                return response()->json(['message' => 'Product variation not found!'], 404);

            // Obtém estoque da variação pelo id
            $product_stock = ProductStocks::find($product_variation->id_product_variation);

            // Caso não encontre, dispara mensagem de não encontrado
            if (!$product_stock)
                return response()->json(['message' => 'Product stock not found!',], 404);

            // Cria entrada de estoque da variação de produto
            $product_stock_entry = ProductStockEntries::create($request->all());

            // Incrementa quantidade em estoque da variação
            $product_stock->update(['quantity' => $product_stock->quantity + $request->quantity]);

            // Salva a transação
            DB::commit();

            // Retorna entrada de estoque com mensagem de sucesso
            return response()->json([
                'message' => 'Product stock entry created successfully!',
                'data' => $product_stock_entry,
            ], 201);
        } catch (\Exception $e) {
            // Cancela transação
            DB::rollBack();

            // Lança exceção para ser tratada conforme necessário
            return response()->json(['message' => $e->getMessage() ?? 'An error occurred during the order creation process.'], 502);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $product_stock_entry_id)
    {
        // Obtém entrada de estoque pelo id
        $product_stock_entry = ProductStockEntries::find($product_stock_entry_id);

        // Retorna entrada de estoque com mensagem de sucesso
        return response()->json([
            'message' => 'Product stock entry listed successfully!',
            'data' => $product_stock_entry,
        ], 200);
    }
}
