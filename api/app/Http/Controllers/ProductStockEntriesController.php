<?php

namespace App\Http\Controllers;

use App\Models\ProductStockEntries;
use App\Models\ProductVariations;
use App\Models\ProductStocks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Utils\ValidatorRequest;
use Illuminate\Support\Str;

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
        ], 200);
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

    public function storeMultiple(Request $request)
    {
        $variations = $request->input('variations');

        if (!is_array($variations) || empty($variations))
            return response()->json(['message' => 'No variations provided'], 422);

        foreach ($variations as $v)
            if (!isset($v['product_variation_id'], $v['quantity']))
                return response()->json(['message' => 'Invalid variation data'], 422);

        DB::beginTransaction();
        try {
            $entries = array_map(fn($v) => [
                'id_product_stock_entry' => (string) Str::uuid(),
                'product_variation_id' => $v['product_variation_id'],
                'quantity' => $v['quantity'],
                'observation' => $v['observation'] ?? null,
            ], $variations);

            ProductStockEntries::insert($entries);

            $ids = array_column($variations, 'product_variation_id');
            $quantities = collect($variations)->keyBy('product_variation_id')->map(fn($v) => $v['quantity']);

            $cases = [];
            foreach ($quantities as $id => $qty)
                $cases[] = "WHEN '{$id}' THEN quantity + {$qty}";

            $cases_sql = implode(" ", $cases);
            $ids_sql = implode(',', array_map(fn($id) => "'$id'", $ids));

            DB::update("UPDATE product_stocks SET quantity = CASE id_stock {$cases_sql} END WHERE id_stock IN ({$ids_sql})");
            DB::commit();

            return response()->json([
                'message' => 'Stock entries created successfully!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
