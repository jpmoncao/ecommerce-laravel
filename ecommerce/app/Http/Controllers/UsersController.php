<?php

namespace App\Http\Controllers;

use App\Http\Utils\ValidatorRequest;
use App\Models\User;
use App\Models\Carts;
use App\Rules\StrongPassword;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        // Cria objeto de validação
        $validate = new ValidatorRequest($request, [
            'name' => 'required|string|max:255',
            'born_date' => 'required|date',
            'address' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|cpf_ou_cnpj',
            'email' => 'required|string|max:255|email',
            'password' => ['required', 'string', 'min:8', new StrongPassword],
        ]);

        // Valida se dados enviados batem com o objeto, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        // Verifica permissão (Gate) para "access-user"
        if (!Gate::allows('access-user', $request->user()->id_user))
            // Se não houver permissão, retorna uma mensagem de inautorizado
            return response()->json(['message' => 'User not authorized!'], 403);

        // Cria objeto com atributos do usuário
        $attributes = [
            'name' => $request->name,
            'born_date' => $request->born_date,
            'address' => $request->address,
            'cpf_cnpj' => $request->cpf_cnpj,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        // Cria ou atualiza usuário
        $user = User::updateOrCreate(
            ['id_user' => $request->user()->id_user],
            $attributes
        );

        // Retorna o usuário com mensagem de sucesso
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
        // Verifica permissão (Gate) para "access-user"
        if (!Gate::allows('access-user', $request->user()->id_user))
            // Se não houver permissão, retorna uma mensagem de inautorizado
            return response()->json(['message' => 'User not authorized!'], 403);

        // Obtem usuário pelo id
        $user = User::find($id_user);

        // Caso não encontre, dispara uma mensagem de não encontrado
        if (!$user)
            return response()->json(['message' => 'User not found!'], 404);

        // Retorna usuário com mensagem de sucesso
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

    public function login(Request $request)
    {
        // Cria objeto de validação
        $validate = new ValidatorRequest($request, [
            'email' => 'required|string|max:255|email',
            'password' => ['required', 'string', 'min:8', new StrongPassword],
        ]);

        // Valida se dados enviados batem com o objeto, caso não, dispara erro
        $error = $validate->handleErrors();
        if ($error)
            return $error;

        // Obtém usuário pelo email
        $user = User::where('email', $request->email)->first();

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$user)
            return response()->json(['message' => 'User with this email not found!'], 404);

        // Verifica se a senha está correta
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password is not valid!'], 404);
        }

        // Autentica o usuário e inicia a sessão
        Auth::login($user);

        // Retorna resposta de sucesso
        return response()->json(['message' => 'Login successful!'], 203);
    }


    public function cartItems(string $user_id)
    {
        // Verifica permissão (Gate) para "access-user"
        if (!Gate::allows('access-user', $user_id))
            // Se não houver permissão, retorna uma mensagem de inautorizado
            return response()->json(['message' => 'User not authorized!'], 403);

        // Obtem usuário pelo id
        $user = User::find($user_id);

        // Caso não encontre, dispara uma mensagem de não encontrado
        if (!$user)
            return response()->json(['message' => 'User not found!'], 404);

        // Obtém carrinho pelo id do usuário
        $cart = Carts::with([
            'items' => function ($query) {
                $query->select('id_cart_item', 'cart_id', 'order_id', 'product_variation_id', 'quantity', 'created_at');
            }
        ])->where('id_cart', $user_id)->first();

        // Caso não encontre, dispara mensagem de não encontrado
        if (!$cart)
            return response()->json(['message' => 'Cart not found!'], 404);

        // Deixa os timestamps invisiveis e atribui carrinho ao usuário
        $user->cart = $cart->makeHidden(['created_at', 'updated_at']);

        // Retorna usuário com carrinho e itens com mensagem de sucesso
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
