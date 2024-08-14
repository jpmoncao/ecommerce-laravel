<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Orders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
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
        // Verifica se tem permissão (Gate) para "access-cart"
        if (!Gate::allows('access-cart', $request->user()->id_user))
            // Caso não, dispara mensagem de não autorizado
            return response()->json(['message' => 'Cart not authorized!'], 403);

        // Cria pedido
        $order = Orders::create(['cart_id' => $request->user()->id_user,]);

        // Atualiza os itens sem pedido passando o id do pedido gerado
        $count_items_updated = CartItems::where('cart_id', $request->user()->id_user)
            ->whereNull('order_id')
            ->update(['order_id' => $order->id_order]);

        // Caso nenhum item foi alterado, dispara mensagem de itens não encontrados
        if ($count_items_updated <= 0) {
            return response()->json([
                'message' => 'No items to add to the order.',
                'data' => [],
            ], 404);
        }

        // Obtém soma total do valor do carrinho
        $total_amount = CartItems::where('order_id', $order->id_order)
            ->join('product_variations', 'cart_items.product_variation_id', '=', 'product_variations.id_product_variation')
            ->sum(DB::raw('cart_items.quantity * product_variations.amount'));

        // Atualiza o total do valor do pedido
        $order->update(['total_amount' => $total_amount]);

        // Obtém o pedido pelo id
        $order = Orders::with([
            'items.productVariation' => function ($query) {
                $query->select(
                    "id_product_variation",
                    "variation",
                    "amount",
                    "product_id"
                );
            }
        ])->where('id_order', $order->id_order)->first();

        // Caso não encontre o pedido, dispara mensagem de não encontrado
        if (!$order)
            return response()->json(['message' => 'Order not found!'], 404);

        // Retorna pedido com mensagem de sucesso
        return response()->json([
            'message' => 'Order created successfully!',
            'data' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orders $orders)
    {
        //
    }
}
