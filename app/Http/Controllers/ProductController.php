<?php

namespace App\Http\Controllers;

use App\Http\Utils\ValidatorRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::findAll();

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

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Product created successfully!',
            'data' => $product,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = Product::with('variants')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Product listed successfully!',
            'data' => $product,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
