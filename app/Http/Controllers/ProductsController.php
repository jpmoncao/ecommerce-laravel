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
        $products = Products::get();

        return response()->json([
            'message' => 'Products listeds successfully!',
            'data' => $products,
        ], 201);
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
        $validate = new ValidatorRequest($request, [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $error = $validate->handleErrors();
        if ($error) {
            return $error;
        }

        $product = Products::create($request->all());

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
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Products not found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Products listed successfully!',
            'data' => $product,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $product)
    {
        //
    }

    public function variations(int $product_id)
    {
        $product = Products::with('variants')->find($product_id);

        if (!$product) {
            return response()->json([
                'message' => 'Products not found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Products with variations listed successfully!',
            'data' => $product,
        ], 200);
    }

}
