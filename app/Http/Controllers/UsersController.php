<?php

namespace App\Http\Controllers;

use App\Http\Utils\ValidatorRequest;
use App\Models\User;
use App\Models\Carts;
use App\Rules\StrongPassword;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class UsersController extends Controller
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

        $validate = new ValidatorRequest($request, [
            'name' => 'required|string|max:255',
            'born_date' => 'required|date',
            'address' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|cpf_ou_cnpj',
            'email' => 'required|string|max:255|email',
            'password' => ['required', 'string', 'min:8', new StrongPassword],
        ]);

        $error = $validate->handleErrors();
        if ($error) {
            return $error;
        }

        if (!Gate::allows('access-user', $request->user()->id_user)) {
            return response()->json([
                'message' => 'User not authorized!',
            ], 403);
        }

        $attributes = [
            'name' => $request->name,
            'born_date' => $request->born_date,
            'address' => $request->address,
            'cpf_cnpj' => $request->cpf_cnpj,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        $user = User::updateOrCreate(
            ['id_user' => $request->user()->id_user],
            $attributes
        );

        return response()->json([
            'message' => 'User listed successfully!',
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_user, Request $request)
    {
        if (!Gate::allows('access-user', $request->user()->id_user)) {
            return response()->json([
                'message' => 'User not authorized!',
            ], 403);
        }

        $user = User::find($id_user);

        return response()->json([
            'message' => 'User listed with successfully!',
            'data' => $user,
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function cartItems(string $user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found!',
                'data' => []
            ], 404);
        }

        $cart = Carts::with([
            'items' => function ($query) {
                $query->select('id_cart_item', 'cart_id', 'order_id', 'product_variation_id', 'quantity', 'created_at');
            }
        ])->where('id_cart', $user_id)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found!',
                'data' => []
            ], 404);
        }

        $user->cart = $cart->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'message' => 'Cart user items listed successfully!',
            'data' => $user->only([
                'id_user',
                'name',
                'born_date',
                'address',
                'cpf_cnpj',
                'email',
                'email_verified_at',
                'remember_token',
                'created_at',
                'updated_at',
                'cart'
            ])
        ], 202);
    }

}
