<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CartsController extends Controller
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_cart)
    {
        if (!Gate::allows('access-cart', $id_cart)) {
            return response()->json([
                'message' => 'Cart not authorized!',
            ], 403);
        }

        $cart = Carts::get()->where('id_cart', '=', $id_cart);

        return response()->json([
            'message' => 'Cart listed successfully!',
            'data' => $cart
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carts $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carts $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carts $cart)
    {
        //
    }
}
