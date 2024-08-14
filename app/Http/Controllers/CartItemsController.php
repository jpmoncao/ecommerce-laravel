<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Carts;
use App\Models\ProductStocks;
use App\Models\ProductVariations;
use App\Http\Utils\ValidatorRequest;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CartItemsController extends Controller
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
        // Cria objeto de validação
        $validate = new ValidatorRequest($request, [
            'product_variation_id' => 'required|string',
            'quantity' => 'required|numeric|min:1',
        ]);

        // Valida se os dados batem com a validação, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        // Verifica permissão (Gate) para "access-cart"
        if (!Gate::allows('access-cart', $request->user()->id_user))
            // Se não houver permissão, retorna uma mensagem de inautorizado
            return response()->json(['message' => 'Cart not authorized!'], 403);

        // Obtém a variação do produto pelo id passado
        $product_variation = ProductVariations::find($request->product_variation_id);
        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_variation)
            return response()->json(['message' => 'Product variation not found!'], 404);

        // Obtém a soma da quantidade da variação no carrinho
        $total_in_cart = CartItems::where('cart_id', $request->user()->id_user)
            ->where('product_variation_id', $request->product_variation_id)
            ->sum('quantity');

        // Obtém o estoque do produto    
        $product_stock = $product_variation->stock;

        // Se a quantidade em estoque for menor do que a demanda (quantidade no carrinho + quantidade da requisição)
        if ($product_stock->quantity < $request->quantity + $total_in_cart) {
            return response()->json([
                'message' => 'Product stock is insufficient!',
            ], 402);
        }

        // Obtém o carrinho do usuário logado
        $cart = Carts::where('id_cart', $request->user()->id_user)
            ->first();

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$cart)
            return response()->json(['message' => 'Cart not found or does not belong to the user!'], 404);

        // Adiciona item vinculando com o carrinho
        $cart_item = CartItems::create([
            'cart_id' => $request->user()->id_user,
            'product_variation_id' => $request->product_variation_id,
            'quantity' => $request->quantity,
        ]);

        // Obtém o estoque da variação do produto adicionado
        $product_stock = ProductStocks::find($cart_item->product_variation_id);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$product_stock)
            return response()->json(['message' => 'Product stock not found!'], 404);

        // Obtém o carrinho com seus itens vinculados
        $cart = Carts::with([
            'items' => function ($query) {
                $query->select('id_cart_item', 'cart_id', 'product_variation_id', 'quantity', 'created_at');
            }
        ])
            ->where('id_cart', $request->user()->id_user)
            ->first();

        // Torna os timestamps invisiveis
        $cart->makeHidden(['created_at', 'updated_at']);

        // Retorna o carrinho com uma mensagem de sucesso
        return response()->json([
            'message' => 'Product added to cart successfully!',
            'data' => $cart,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItems $orderItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItems $orderItems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItems $orderItems)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_cart_item, Request $request)
    {
        // Verifica permissão (Gate) para "access-cart"
        if (!Gate::allows('access-cart', $request->user()->id_user))
            // Se não houver permissão, retorna uma mensagem de inautorizado
            return response()->json(['message' => 'Cart not authorized!'], 403);

        // Obtém o item do carrinho
        $cart_item = CartItems::find($id_cart_item);

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$cart_item) {
            return response()->json([
                'message' => 'Product not found in the cart!',
            ], 404);
        }

        // Apaga o item
        $cart_item->delete();

        // Retorna mensagem de sucesso
        return response()->json(['message' => 'Product removed from the cart successfully!'], 200);
    }

}
