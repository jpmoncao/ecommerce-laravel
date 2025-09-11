<?php

namespace App\Http\Controllers;

use App\Http\Utils\ValidatorRequest;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtém produtos
        $products = Products::get();

        // Retorna produtos com mensagem de sucesso
        return response()->json([
            'message' => 'Products listed successfully!',
            'data' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Cria objeto de validação
        $validate = new ValidatorRequest($request, [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        // Valida se os dados batem com a validação, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        // Cria produto
        $product = Products::create($request->all());

        // Retorna produto criado com mensagem de sucesso
        return response()->json([
            'message' => 'Products created successfully!',
            'data' => $product,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Obtém produto pelo id dele
        $product = Products::find($id);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product)
            return response()->json(['message' => 'Product not found!'], 404);

        // Retorna produto com mensagem de sucesso
        return response()->json([
            'message' => 'Product listed successfully!',
            'data' => $product,
        ], 200);
    }

    public function variations(int $product_id)
    {
        // Obtém o produto pelo id dele
        $product = Products::with('variants')->find($product_id);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product)
            return response()->json(['message' => 'Product not found!'], 404);

        // Retorna produto com mensagem de sucesso
        return response()->json([
            'message' => 'Products with variations listed successfully!',
            'data' => $product,
        ], 200);
    }
}
