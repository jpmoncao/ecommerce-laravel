<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Financials;
use App\Models\Orders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verifica se tem permissão (Gate) para "access-cart"
        if (!Gate::allows('access-cart', $request->user()->id_user)) {
            throw new \Exception('Cart not authorized!');
        }

        try {
            // Inicia transação
            DB::beginTransaction();

            // Cria pedido
            $order = Orders::create(['cart_id' => $request->user()->id_user]);

            // Verifica se há itens no carrinho sem pedido associado
            $count_items_to_update = CartItems::where('cart_id', $request->user()->id_user)
                ->whereNull('order_id')
                ->count();

            // Caso não haja itens a serem atualizados, dispara exceção
            if ($count_items_to_update <= 0) {
                throw new \Exception('No items to add to the order.');
            }

            // Atualiza os itens sem pedido passando o id do pedido gerado
            CartItems::where('cart_id', $request->user()->id_user)
                ->whereNull('order_id')
                ->update(['order_id' => $order->id_order]);

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

            // Caso não encontre o pedido, dispara exceção
            if (!$order) {
                throw new \Exception('Order not found!');
            }

            // Cria financeiro
            Financials::create(['order_id' => $order->id_order]);

            // Confirma transação
            DB::commit();

            // Retorna pedido com mensagem de sucesso
            return response()->json([
                'message' => 'Order created successfully!',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            // Cancela transação
            DB::rollBack();

            // Lança exceção para ser tratada conforme necessário
            return response()->json(['message' => $e->getMessage() ?? 'An error occurred during the order creation process.'], 502);
        }
    }
}
