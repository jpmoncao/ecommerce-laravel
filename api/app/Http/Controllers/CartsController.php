<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CartsController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id_cart)
    {
        // Verifica permissão (Gate) para "access-cart"
        if (!Gate::allows('access-cart', $id_cart))
            // Se não houver permissão,retorna uma mensagem de inautorizado
            return response()->json(['message' => 'Cart not authorized!'], 403);

        // Obtém o carrinho pelo id
        $cart = Carts::get()->where('id_cart', '=', $id_cart);

        // Retorna mensagem de sucesso com carrinho
        return response()->json([
            'message' => 'Cart listed successfully!',
            'data' => $cart
        ], 200);
    }
}
